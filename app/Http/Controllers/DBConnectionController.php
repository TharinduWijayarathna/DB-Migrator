<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDBConnectionRequest;
use App\Http\Requests\UpdateDBConnectionRequest;
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
        //
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
}
