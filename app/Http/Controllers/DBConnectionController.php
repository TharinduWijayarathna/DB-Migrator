<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDBConnectionRequest;
use App\Http\Requests\UpdateDBConnectionRequest;
use App\Http\Resources\DBConnection\FilterDBConnectionsResource;
use App\Models\DBConnection;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

class DBConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('DBConnection/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDBConnectionRequest $request)
    {
        return DBConnection::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function all(HttpRequest $request)
    {
        $query = DBConnection::query();

        if (!empty($request->input('username'))) {
            $query->where('name', 'like', '%' . $request->input('username') . '%');
        }
        if (!empty($request->input('host'))) {
            $query->where('host', 'like', '%' . $request->input('host') . '%');
        }
        if (!empty($request->input('port'))) {
            $query->where('port', 'like', '%' . $request->input('port') . '%');
        }
        if (!empty($request->input('database'))) {
            $query->where('database', 'like', '%' . $request->input('database') . '%');
        }

        $payload = QueryBuilder::for($query)
            ->allowedSorts(['id', 'name'])
            ->orderBy('id', 'desc')
            ->paginate(request('per_page', config('basic.pagination_per_page')));
        return FilterDBConnectionsResource::collection($payload);
    }

    /**
     * Display the specified resource.
     */
    public function show(DBConnection $dBConnection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DBConnection $dBConnection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDBConnectionRequest $request, DBConnection $dBConnection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DBConnection $dBConnection)
    {
        //
    }

    public function activate(DBConnection $dBConnection)
    {
        try {
            // Validate the DBConnection object
            $validator = Validator::make($dBConnection->toArray(), [
                'host' => 'required|string',
                'port' => 'required|integer|between:1,65535',
                'database' => 'required|string',
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            Config::set('database.connections.mysql', [
                'driver' => 'mysql',
                'host' => $dBConnection->host,
                'port' => $dBConnection->port,
                'database' => $dBConnection->database,
                'username' => $dBConnection->username,
                'password' => $dBConnection->password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
                'options' => [
                    \PDO::ATTR_TIMEOUT => 5,
                ],
            ]);

            try {
                DB::purge('mysql');
                $connection = DB::connection('mysql')->getPdo();
                $connection->query('SELECT 1');
            } catch (\PDOException $e) {
                throw new \Exception('Database connection failed: ' . $e->getMessage());
            }

            DBConnection::where('id', '!=', $dBConnection->id)->update(['is_active' => false]);
            $dBConnection->update(['is_active' => true]);
            return $dBConnection;
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function deactivate(DBConnection $dBConnection)
    {
        try {
            if (!$dBConnection->is_active) {
                return response()->json(['message' => 'Connection is already inactive'], 400);
            }

            $dBConnection->update(['is_active' => false]);
            return $dBConnection;
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deactivating the connection: ' . $e->getMessage()], 500);
        }
    }
}
