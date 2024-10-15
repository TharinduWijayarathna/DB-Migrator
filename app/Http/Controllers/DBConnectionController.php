<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDBConnectionRequest;
use App\Http\Requests\UpdateDBConnectionRequest;
use App\Http\Resources\DBConnection\FilterDBConnectionsResource;
use App\Models\DBConnection;
use Inertia\Inertia;

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
    public function all()
    {
        $query = DBConnection::query();
        return FilterDBConnectionsResource::collection($query->paginate());
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
        DBConnection::where('id', '!=', $dBConnection->id)->update(['is_active' => false]);
        $dBConnection->update(['is_active' => true]);
        return $dBConnection;
    }

    public function deactivate(DBConnection $dBConnection)
    {
        $dBConnection->update(['is_active' => false]);
        return $dBConnection;
    }
}
