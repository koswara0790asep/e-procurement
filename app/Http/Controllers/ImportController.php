<?php

namespace App\Http\Controllers;

use App\Jobs\ImportProductsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:51200', // 50MB max
        ]);

        $path = $request->file('file')->store('imports');

        // ImportProductsJob::dispatch($path, $request->user()->id);
        dispatch(new ImportProductsJob($path, $request->user()->id));


        return response()->json([
            'message' => 'Import is being processed in the background.',
            'file' => $path
        ]);
    }
}

