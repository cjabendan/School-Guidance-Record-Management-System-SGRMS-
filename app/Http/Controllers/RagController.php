<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessDocument;

class RagController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id'   => 'required|string',
            'file' => 'required|file|mimes:txt,pdf'
        ]);

        $id   = $request->input('id');
        $file = $request->file('file');

        // Extract text
        if ($file->getClientOriginalExtension() === 'txt') {
            $text = file_get_contents($file->getRealPath());
        } else {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($file->getRealPath());
            $text   = $pdf->getText();
        }

        // Dispatch background job
        ProcessDocument::dispatch($id, $text);

        return response()->json([
            'message' => "✅ Document [$id] uploaded. Processing in background."
        ]);
    }
}
