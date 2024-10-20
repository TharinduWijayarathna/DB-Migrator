<?php

namespace App\Http\Controllers;

use App\Models\DBConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BackupRestoreController extends Controller
{
    public function index()
    {
        return Inertia::render('BackupRestore/Index');
    }

    public function getDatabaseTableNames()
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

        // Use the 'mysql_static' connection to query the tables
        $tables = DB::connection('mysql_static')->select('SHOW TABLES');

        // Extract only the table names from the results
        $tableNames = array_map(function ($table) {
            // Adjust this according to the key name of the table in your result
            $tableName = array_values((array)$table)[0];
            return $tableName;
        }, $tables);

        return response()->json([
            'success' => true,
            'tables' => $tableNames
        ]);

        return response()->json([
            'success' => true,
            'tables' => $tables
        ]);
    }

    public function fullBackup()
    {
        $file = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
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


        $command = sprintf(
            'mysqldump -u%s -p%s -h %s %s > %s',
            $connection->username,
            $connection->password,
            $connection->host,
            $connection->database,
            storage_path('app/public/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function fullBackupNoData()
    {
        $file = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
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

        $command = sprintf(
            'mysqldump -u%s -p%s -h %s --no-data %s > %s',
            $connection->username,
            $connection->password,
            $connection->host,
            $connection->database,
            storage_path('app/public/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function selectiveBackup(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
        ]);

        $file = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
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

        $tables = implode(' ', $request->input('tables'));

        $command = sprintf(
            'mysqldump -u%s -p%s -h %s %s %s > %s',
            $connection->username,
            $connection->password,
            $connection->host,
            $connection->database,
            $tables,
            storage_path('app/public/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function selectiveBackupNoData(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
        ]);

        $file = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
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

        $tables = implode(' ', $request->input('tables'));

        $command = sprintf(
            'mysqldump -u%s -p%s -h %s --no-data %s %s > %s',
            $connection->username,
            $connection->password,
            $connection->host,
            $connection->database,
            $tables,
            storage_path('app/public/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function deleteBackup(Request $request)
    {
        $request->validate([
            'file' => 'required|string'
        ]);

        $file = $request->input('file');

        if (file_exists(storage_path('app/public/' . $file))) {
            unlink(storage_path('app/public/' . $file));
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:50000' // 50MB max file size
        ]);

        $connection = DBConnection::where('is_active', 1)->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'error' => 'No active database connection found.'
            ], 400);
        }

        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->path();

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

        try {
            DB::connection('mysql_static')->unprepared(file_get_contents($filePath));

            return response()->json([
                'success' => true,
                'message' => 'Database restored successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Database restore failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to restore database. Please check the logs for more information.'
            ], 500);
        } finally {
            // Clean up the temporary uploaded file
            @unlink($filePath);
        }
    }
}
