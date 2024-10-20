<?php

namespace App\Http\Controllers;

use App\Models\DBConnection;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            'connection' => 'required|integer|exists:db_connections,id',
        ]);

        $connection = DBConnection::findOrFail($request->connection);

        $results = $connection->query($request->query);

        return response()->json($results);
    }
}
