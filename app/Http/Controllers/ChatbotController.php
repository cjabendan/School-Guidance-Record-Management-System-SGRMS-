<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudstudio\Ollama\Facades\Ollama;

class ChatbotController extends Controller
{
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateResponse(Request $request)
    {
       
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->input('prompt');

        try {
            $response = Ollama::agent('You are Gabby, a helpful and friendly assistant. Keep your answers concise.')
                              ->prompt($userPrompt)
                              ->model(env('OLLAMA_MODEL', 'llama3.1b')) // Use the model from .env
                              ->ask();

            return response($response['response']);

        } catch (\Exception $e) {
            return response('Oops! Something went wrong on the server.', 500);
        }
    }
}