<?php

namespace App\Http\Controllers;

use App\Models\DBConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Database\QueryException;

class QueryController extends Controller
{
    public function index()
    {
        return Inertia::render('Query/Index');
    }

    public function run(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $connection = DBConnection::where('is_active', 1)->first();

        if (!$connection) {
            return response()->json([
                'success' => false,
                'error' => 'No active database connection found.'
            ], 400);
        }

        // Configure the dynamic connection
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
            // Use the dynamic connection to run the query
            $results = DB::connection('mysql_static')->select($request->input('query'));

            return response()->json([
                'success' => true,
                'results' => $results
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
