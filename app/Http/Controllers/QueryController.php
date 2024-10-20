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
            $query = trim($request->input('query'));
            $queryType = strtoupper(strtok($query, ' '));

            switch ($queryType) {
                case 'SELECT':
                    $results = DB::connection('mysql_static')->select($query);
                    return response()->json([
                        'success' => true,
                        'type' => 'select',
                        'results' => $results,
                        'affectedRows' => count($results)
                    ]);
                case 'INSERT':
                case 'UPDATE':
                case 'DELETE':
                    $affectedRows = DB::connection('mysql_static')->affectingStatement($query);
                    return response()->json([
                        'success' => true,
                        'type' => strtolower($queryType),
                        'affectedRows' => $affectedRows
                    ]);
                default:
                    $result = DB::connection('mysql_static')->statement($query);
                    return response()->json([
                        'success' => true,
                        'type' => 'other',
                        'result' => $result
                    ]);
            }
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
