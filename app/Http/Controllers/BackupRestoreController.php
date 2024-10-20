<?php

namespace App\Http\Controllers;

use App\Models\DBConnection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackupRestoreController extends Controller
{
    public function index()
    {
        return Inertia::render('BackupRestore/Index');
    }

    public function full_backup()
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
            storage_path('app/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function full_backup_without_data()
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
            storage_path('app/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function backup_provided_tables(Request $request)
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
            storage_path('app/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }

    public function backup_provided_tables_without_data(Request $request)
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
            storage_path('app/' . $file)
        );

        exec($command);

        return response()->json([
            'success' => true,
            'file' => $file
        ]);
    }
}
