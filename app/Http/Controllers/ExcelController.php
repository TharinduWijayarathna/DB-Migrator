<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ExcelController extends Controller
{
    public function index()
    {
        return Inertia::render('ExcelImport/Index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('excel', $fileName);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file' => $fileName
        ]);
    }

    public function export()
    {
        return response()->json([
            'success' => true,
            'message' => 'Export functionality is not yet implemented.'
        ]);
    }
}
