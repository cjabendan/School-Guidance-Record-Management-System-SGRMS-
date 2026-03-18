<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Services\RagService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Generate a response for the chatbot using RAG and Ollama.
     * This function now handles only the current request/response cycle,
     * with no persistence of chat history.
     */
    
    public function generateResponse(Request $request, RagService $rag)
    {
        // Keep web requests responsive; long-running indexing should be queued (see ProcessDocument job).
        set_time_limit((int) env('CHATBOT_MAX_EXECUTION_SEC', 30));
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->json('prompt');
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        // -------------------------------
        // Session / History Removal
        // Since no history is stored, these are simplified:
        // -------------------------------
        $redactedPrompt = $userPrompt;
        $isViolentQuestion = false;
        $isExpressingIntent = false;
        $waitingForOverviewConfirmation = false; // Always false as no history is tracked

        // We still check if the user is expressing violent intent before logging the prompt.

        // -------------------------------
        // 1. Violent / Harm Detection
        // -------------------------------
        $violentPattern = '/\b(punch|hit|stab|beat|hurt|kill|attack|harm|smash|fight)\b/i';
        $intentPattern     = '/\b(i will|i\'ll|i am going to|i might|i want to (hurt|punch|kill|attack|harm))\b/i';
        $isViolentQuestion = preg_match($violentPattern, $userPrompt);
        $isExpressingIntent = preg_match($intentPattern, $userPrompt);

        // Redact any violent intent before logging
        $redactedPrompt = $isExpressingIntent
            ? preg_replace($intentPattern, '[REDACTED INTENT]', $userPrompt)
            : $userPrompt;

        // No messages array is persisted, so we don't need to add the user message here.

        if ($isExpressingIntent) {
            $botReply = "If you or someone else is in immediate danger, please contact local emergency services now.\n\n"
                . "For incidents at school, speak directly with a counselor or administrator.\n\n"
                . "I cannot assist with harm-related actions, but I can connect you to people who can help you stay safe and calm.";

            Log::warning('Chatbot flagged violent intent', [
                'session' => $sessionId,
                'user_id' => $userId,
                'prompt' => $userPrompt
            ]);

            return response($botReply, 200);
        }

        // -------------------------------
        // 2. Greetings / Small Talk
        // -------------------------------
        $greetingPattern = '/\b(hi|hello|hey|good\s?(morning|afternoon|evening)|how are you|what\'s up|sup)\b/i';
        if (preg_match($greetingPattern, strtolower($userPrompt))) {
            $systemInstruction = <<<SYS
You are Gabby, a friendly and professional school assistant.
If greeted, respond politely.
If asked how you are, reply: "I'm just a school assistant, but I'm ready to help you with any school policy questions."
Avoid long responses or personal stories. No markdown or asterisks.
SYS;
            try {
                $response = Ollama::agent($systemInstruction)
                    ->prompt($userPrompt)
                    ->model('gemma3:1b')
                    ->ask();

                $botReply = trim($response['response'] ?? 'Hello! How can I assist you today?');
                $botReply = preg_replace('/^(ASSISTANT:|AI:|GABBY:)\s*/i', '', $botReply);
                $botReply = str_replace('**', '', $botReply);

                return response($botReply, 200);
            } catch (\Exception $e) {
                Log::error('Greeting handling failed', ['error' => $e->getMessage()]);
                return response('Hello! How can I assist you today?', 200);
            }
        }

        // -------------------------------
        // 3. RAG Retrieval with Pinecone
        // -------------------------------
        // ChatbotController.php line ~117
        $allowedTopics = [
            'policy',
            'discipline',
            'bullying',
            'guidance',
            'attendance',
            'suspension',
            'counseling',
            'school rules',
            // New Academic/Learning topics:
            'academic',
            'cheating',
            'plagiarism',
            'grading',
            'examinations'
        ];

        try {
            $docs = $rag->retrieve($userPrompt, 4, $allowedTopics);
        } catch (\Exception $e) {
            Log::error('RAG retrieval failed', ['error' => $e->getMessage()]);
            $docs = [];
        }

        // -------------------------------
        // 4. Relevance Check (Simplified)
        // -------------------------------
        // If no documents were retrieved, check if the query is totally irrelevant to policies.
        if (empty($docs) && !$waitingForOverviewConfirmation) {
            $isTotallyIrrelevant = true;
            $allowedKeywords = array_map('strtolower', $allowedTopics); // Use the topics as keywords

            // If the prompt contains any policy keyword, it's NOT totally irrelevant.
            foreach ($allowedKeywords as $topic) {
                if (stripos($userPrompt, $topic) !== false) {
                    $isTotallyIrrelevant = false;
                    break;
                }
            }

            // Also check for 'disrespect' or 'authority' since they are key terms
            if (stripos($userPrompt, 'disrespect') !== false || stripos($userPrompt, 'authority') !== false) {
                $isTotallyIrrelevant = false;
            }

            if ($isTotallyIrrelevant) {
                // The query is NOT related to school policy (e.g., "what is the best movie?"). BLOCK.
                $botReply = "I'm sorry, I can only answer questions related to school policies or student guidance.";
                return response($botReply, 200);
            }
            // If the query is policy-related but RAG failed ($docs is empty), 
            // we proceed to the LLM, which will use its system instruction fallback.
        }

        // -------------------------------
        // 5. Violent Topic Sorting
        // -------------------------------
        if ($isViolentQuestion) {
            usort($docs, function ($a, $b) {
                $ka = substr_count(strtolower($a), 'discipline') + substr_count(strtolower($a), 'suspend') + substr_count(strtolower($a), 'expel');
                $kb = substr_count(strtolower($b), 'discipline') + substr_count(strtolower($b), 'suspend') + substr_count(strtolower($b), 'expel');
                return $kb <=> $ka;
            });
        }

        // -------------------------------
        // 6. Combine context
        // -------------------------------
        $context = implode("\n\n---\n\n", $docs);
        $maxContextChars = (int) env('RAG_MAX_CONTEXT_CHARS', 4000);
        if (strlen($context) > $maxContextChars) {
            $context = substr($context, 0, $maxContextChars);
        }
        $recentConversation = ''; // History is eliminated

        $systemInstruction = $isViolentQuestion
            ? <<<SYS
You are Gabby, a calm and factual school assistant.
When users ask about physical or emotional harm, respond factually using school policy.
Describe procedures like investigation, counseling, parental notification, suspension, or expulsion.
Never describe methods of violence.
If a user expresses intent to harm, tell them to contact emergency services.
Keep your answer brief (3–6 sentences) in short paragraphs. No markdown or asterisks.
SYS
            : <<<SYS
You are Gabby, a concise and friendly school policy assistant.
YOUR SOLE FUNCTION is to summarize and synthesize the provided CONTEXT. You have NO independent knowledge.
Answer the user's question using ONLY the provided CONTEXT. Be factual.
Keep your final answer to 3-5 sentences in short paragraphs. No markdown or asterisks.
If the CONTEXT does not contain the answer, and ONLY if it does not, you must respond with: "I'm sorry, I don’t see that information in the school’s policy."
SYS;

        // NOTE: PREVIOUS CONVERSATION section is removed from the prompt as there is no history.
        $combinedPrompt = <<<PROMPT
Using ONLY the context below, answer the user's question. 
If the context does not contain the answer, ONLY say: "I'm sorry, I don’t see that information in the school’s policy."

CONTEXT:
{$context}

USER QUESTION:
{$userPrompt}
PROMPT;

        // -------------------------------
        // 7. Generate bot response
        // -------------------------------
        try {
            // Ensure Ollama request is wrapped for error checking
            $response = Ollama::agent($systemInstruction)
                ->prompt($combinedPrompt)
                ->model(env('OLLAMA_MODEL', 'gemma3:1b'))
                ->ask();

            $botReply = $response['response'] ?? 'Sorry, I could not generate a response.';
            $botReply = preg_replace('/^(ASSISTANT:|AI:|GABBY:)\s*/i', '', trim($botReply));
            $botReply = str_replace('**', '', $botReply);
            $botReply = preg_replace('/^\s*\d+\.\s*/m', '• ', $botReply);

            // CRITICAL: Add a check here. If the LLM generates a very short or generic response,
            // it may have failed to process the context.
            if (str_contains(strtolower($botReply), "sorry") || str_contains(strtolower($botReply), "could not") || strlen($botReply) < 10) {
                 Log::warning('LLM generated a fallback response despite RAG content.', [
                     'prompt' => $userPrompt,
                     'context_len' => strlen($context),
                     'llm_output' => $botReply
                 ]);
                 // Re-throw or use a better error message if necessary, but we will rely on the next fix.
            }

            return response($botReply, 200);
        } catch (\Exception $e) {
            // If the error is an Ollama timeout or connection issue
            Log::error('Chatbot error during LLM generation.', ['error' => $e->getMessage()]);
            // Ensure we return the 500 status so the front-end shows the "Oops!" error, 
            // confirming a server/LLM failure, not a RAG failure.
            return response('Oops! Something went wrong on the server.', 500); 
        }
    }
}
