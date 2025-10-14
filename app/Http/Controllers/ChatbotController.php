<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudstudio\Ollama\Facades\Ollama;
use App\Services\RagService;
use App\Models\ChatbotSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Generate a response for the chatbot using RAG and Ollama.
     *
     * Steps:
     * 1. Violent/Harm detection and redaction
     * 2. Greeting / small talk handling
     * 3. Retrieve context from RAG (Pinecone)
     * 4. Hybrid topic filtering (RAG-driven)
     * 5. Violent topic sorting
     * 6. Combine context + recent conversation
     * 7. Generate chatbot response
     */
    public function generateResponse(Request $request, RagService $rag)
    {
        $request->validate(['prompt' => 'required|string']);
        $userPrompt = $request->input('prompt');
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        // -------------------------------
        // Get or create chat session
        // -------------------------------
        $chatSession = ChatbotSession::firstOrCreate(
            ['user_id' => $userId, 'session_id' => $sessionId],
            ['messages' => []]
        );
        $messages = $chatSession->messages ?? [];
        $lastBotMessage = end($messages)['content'] ?? '';
        $waitingForOverviewConfirmation = str_contains($lastBotMessage, 'Would you like me to explain what’s generally covered instead?');

        // -------------------------------
        // 1. Violent / Harm Detection
        // -------------------------------
        $violentPattern = '/\b(punch|hit|stab|beat|hurt|kill|attack|harm|smash|fight)\b/i';
        $intentPattern  = '/\b(i will|i\'ll|i am going to|i might|i want to (hurt|punch|kill|attack|harm))\b/i';
        $isViolentQuestion = preg_match($violentPattern, $userPrompt);
        $isExpressingIntent = preg_match($intentPattern, $userPrompt);

        // Redact any violent intent before logging or storing
        $redactedPrompt = $isExpressingIntent
            ? preg_replace($intentPattern, '[REDACTED INTENT]', $userPrompt)
            : $userPrompt;

        $messages[] = ['role' => 'user', 'content' => $redactedPrompt];

        if ($isExpressingIntent) {
            $botReply = "If you or someone else is in immediate danger, please contact local emergency services now.\n\n"
                . "For incidents at school, speak directly with a counselor or administrator.\n\n"
                . "I cannot assist with harm-related actions, but I can connect you to people who can help you stay safe and calm.";

            Log::warning('Chatbot flagged violent intent', [
                'session' => $sessionId,
                'user_id' => $userId,
                'prompt' => $userPrompt
            ]);

            $messages[] = ['role' => 'assistant', 'content' => $botReply];
            $chatSession->update(['messages' => $messages, 'last_activity' => now()]);
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

                $messages[] = ['role' => 'assistant', 'content' => $botReply];
                $chatSession->update(['messages' => $messages, 'last_activity' => now()]);
                return response($botReply, 200);
            } catch (\Exception $e) {
                Log::error('Greeting handling failed', ['error' => $e->getMessage()]);
                return response('Hello! How can I assist you today?', 200);
            }
        }

        // -------------------------------
        // 3. RAG Retrieval with Pinecone
        // -------------------------------
        $allowedTopics = ['policy', 'discipline', 'bullying', 'guidance', 'attendance', 'suspension', 'counseling', 'school rules'];

        try {
            $docs = $rag->retrieve($userPrompt, 10, $allowedTopics);
        } catch (\Exception $e) {
            Log::error('RAG retrieval failed', ['error' => $e->getMessage()]);
            $docs = [];
        }

        // -------------------------------
        // 4. Hybrid Topic Filter (RAG-driven)
        // -------------------------------
        $isRelevant = false;

        // 4a. If RAG returned chunks, consider the query relevant
        if (!empty($docs)) {
            $isRelevant = true;
        } else {
            // 4b. Fallback: check if user prompt contains any allowed topic keywords
            foreach ($allowedTopics as $topic) {
                if (stripos($userPrompt, $topic) !== false) {
                    $isRelevant = true;
                    break;
                }
            }
        }

        // 4c. If still not relevant and user hasn't confirmed overview, block response
        if (!$isRelevant && !$waitingForOverviewConfirmation) {
            $botReply = "I'm sorry, I can only answer questions related to school policies or student guidance.";
            $messages[] = ['role' => 'assistant', 'content' => $botReply];
            $chatSession->update(['messages' => $messages]);
            return response($botReply, 200);
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
        // 6. Combine context + recent conversation
        // -------------------------------
        $context = implode("\n\n---\n\n", $docs);
        $recentConversation = collect($messages)->take(-6)->map(fn($msg) => $msg['content'])->implode("\n");

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
Answer clearly in 2–4 sentences based only on CONTEXT.
Use short paragraphs separated by new lines.
If the answer isn’t in the CONTEXT, say:
"I'm sorry, I don’t see that information in the school’s policy.\n\nWould you like me to explain what’s generally covered instead?"
No markdown or asterisks.
SYS;

        $combinedPrompt = "CONTEXT:\n{$context}\n\nPREVIOUS CONVERSATION:\n{$recentConversation}\n\nUSER QUESTION:\n{$userPrompt}";

        // -------------------------------
        // 7. Generate bot response
        // -------------------------------
        try {
            $response = Ollama::agent($systemInstruction)
                ->prompt($combinedPrompt)
                ->model(env('OLLAMA_MODEL', 'gemma3:1b'))
                ->ask();

            $botReply = $response['response'] ?? 'Sorry, I could not generate a response.';
            $botReply = preg_replace('/^(ASSISTANT:|AI:|GABBY:)\s*/i', '', trim($botReply));
            $botReply = str_replace('**', '', $botReply);
            $botReply = preg_replace('/^\s*\d+\.\s*/m', '• ', $botReply);

            $messages[] = ['role' => 'assistant', 'content' => $botReply];
            if (count($messages) > 50) {
                $messages = array_slice($messages, -30);
            }

            $chatSession->update(['messages' => $messages, 'last_activity' => now()]);
            return response($botReply, 200);
        } catch (\Exception $e) {
            Log::error('Chatbot error', ['error' => $e->getMessage()]);
            return response('Oops! Something went wrong on the server.', 500);
        }
    }

    /**
     * Clear the current chat session memory.
     * Deletes all messages for the current session.
     */
    public function clearSession(Request $request)
    {
        $sessionId = $request->session()->getId();
        ChatbotSession::where('session_id', $sessionId)->delete();
        return response()->json(['message' => 'Chat memory cleared.']);
    }
}
