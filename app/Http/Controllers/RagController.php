<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RagService;
use Smalot\PdfParser\Parser;

class RagController extends Controller
{
    public function store(Request $request, RagService $rag)
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
            $parser = new Parser();
            $pdf    = $parser->parseFile($file->getRealPath());
            $text   = $pdf->getText();
        }

        // Store with chunking
        $rag->addDocument($id, $text);

        return response()->json([
            'message' => "✅ Document [$id] uploaded, chunked, and added to Pinecone."
        ]);
    }
}
