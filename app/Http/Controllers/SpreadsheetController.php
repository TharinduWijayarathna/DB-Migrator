<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SpreadsheetController extends Controller
{
    public function index()
    {
        return Inertia::render('Spreadsheet/Index');
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file'
    //     ]);

    //     $file = $request->file('file');

    //     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //     $spreadsheet = $reader->load($file->getPathname());
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $rows = $sheet->toArray();

    //     return response()->json([
    //         'success' => true,
    //         'rows' => $rows
    //     ]);
    // }

    // public function export()
    // {
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $sheet->setCellValue('A1', 'Hello World !');

    //     $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //     $writer->save('hello_world.xlsx');

    //     return response()->download('hello_world.xlsx');
    // }
}
