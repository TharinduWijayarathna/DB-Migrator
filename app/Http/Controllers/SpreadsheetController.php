<?php

namespace App\Http\Controllers;

use App\Models\DBConnection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpreadsheetController extends Controller
{
    public function index()
    {
        return Inertia::render('Spreadsheet/Index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'table' => 'required|string'
        ]);

        $file = $request->file('file');
        $table = $request->input('table');

        // Get file extension to determine format
        $fileExtension = $file->getClientOriginalExtension();

        if ($fileExtension === 'csv') {
            $reader = new ReaderCsv();
        } else {
            $reader = new ReaderXlsx();
        }

        // Load spreadsheet and get the first sheet
        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();

        // Convert sheet rows to an array
        $rows = $sheet->toArray();

        // You can map data here based on the table structure
        $data = $this->mapDataToTable($rows, $table);

        // Insert data into the provided table
        $this->importDataToTable($table, $data);

        return response()->json([
            'success' => true,
            'message' => 'Data imported successfully'
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'table' => 'required|string'
        ]);

        $table = $request->input('table');

        // Get data from the provided table
        $data = $this->getDataForTable($table);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write the data to the spreadsheet
        $this->writeDataToSpreadsheet($sheet, $data);

        // Determine the format of the file (csv or xlsx)
        $fileExtension = $request->input('format', 'xlsx');

        if ($fileExtension === 'csv') {
            $writer = new WriterCsv($spreadsheet);
            $fileName = 'exported_data.csv';
        } else {
            $writer = new WriterXlsx($spreadsheet);
            $fileName = 'exported_data.xlsx';
        }

        // Save the file temporarily and return it for download
        $tempFile = storage_path('app/public/' . $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    private function mapDataToTable(array $rows, string $table): array
    {
        // Get the actual columns of the database table
        $tableColumns = DB::connection('mysql_static')->getSchemaBuilder()->getColumnListing($table);

        // Use the first row of the spreadsheet as the column names
        $spreadsheetColumns = array_shift($rows);

        // Check if the columns from the spreadsheet match the table columns
        $mappedColumns = array_intersect($spreadsheetColumns, $tableColumns);

        if (empty($mappedColumns)) {
            throw new \Exception("The spreadsheet columns do not match the table columns.");
        }

        $data = [];
        foreach ($rows as $row) {
            // Map the rows to the actual table columns
            $data[] = array_combine($mappedColumns, array_slice($row, 0, count($mappedColumns)));
        }

        return $data;
    }


    private function importDataToTable(string $table, array $data)
    {
        $connection = DBConnection::where('is_active', 1)->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'error' => 'No active database connection found.'
            ], 400);
        }

        config([
            "database.connections.mysql_static" => [
                'driver' => 'mysql',
                'host' => $connection->host,
                'port' => $connection->port,
                'database' => $connection->database,
                'username' => $connection->username,
                'password' => $connection->password,
            ]
        ]);

        return DB::connection('mysql_static')->table($table)->insert($data);
    }

    private function getDataForTable(string $table): array
    {
        $connection = DBConnection::where('is_active', 1)->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'error' => 'No active database connection found.'
            ], 400);
        }

        config([
            "database.connections.mysql_static" => [
                'driver' => 'mysql',
                'host' => $connection->host,
                'port' => $connection->port,
                'database' => $connection->database,
                'username' => $connection->username,
                'password' => $connection->password,
            ]
        ]);

        // Convert result to array
        return DB::connection('mysql_static')->table($table)->get()->toArray();
    }


    private function writeDataToSpreadsheet(Worksheet $sheet, array $data): void
    {
        if (!empty($data)) {
            // Extract the column names from the first row of the data
            $columnNames = array_keys((array) $data[0]);

            // Write the column names as headers in the first row
            $sheet->fromArray($columnNames, null, 'A1');

            // Start writing data from the second row
            $sanitizedData = array_map(function ($row) {
                if (is_object($row)) {
                    $row = (array)$row;
                }

                return array_map(function ($cell) {
                    return is_scalar($cell) ? (string)$cell : json_encode($cell);
                }, $row);
            }, $data);

            // Write sanitized data starting from the second row
            $sheet->fromArray($sanitizedData, null, 'A2');
        }
    }
}
