<?php

namespace App\Services;

use OpenAI;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\FaqQuestion;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        if (app()->environment('local')) {
            // Local only: disable SSL verification
            $guzzle = new GuzzleClient(['verify' => false]);

            $this->client = OpenAI::factory()
                ->withApiKey(env('OPENAI_API_KEY'))
                ->withHttpClient($guzzle)
                ->make();
        } else {
            // Production: SSL verification enabled
            $this->client = OpenAI::client(env('OPENAI_API_KEY'));
        }
    }

    public function askFAQ(string $question): array
    {
        // Fetch all FAQs with id and question
        $faqs = FaqQuestion::all(['id', 'question']);
        $faqListText = "Here are the existing FAQs:\n";
        foreach ($faqs as $faq) {
            $faqListText .= "{$faq->id}. {$faq->question}\n";
        }
        $systemPrompt = "You are a Yonder FAQ assistant for the university marketplace. Your job is to respond **only using the FAQs in the database**. 
            - Use the provided FAQ list below as your source. 
            - If a user question matches one or more FAQs, respond with each matched FAQ **question and answer**, clearly separated. Include the FAQ ID in the format: [FAQ_ID: <id>]. 
            - If multiple FAQs match, list **all matches** in order of relevance. 
            - If something in the user's question is **loosely related** to any FAQ, add it at the bottom under 'Related FAQs' with its answer. Do not add unrelated advice. 
            - Never provide answers not in the FAQs unless explicitly instructed.

            FAQ List:
            " . $faqListText . "

            Examples of the format you must follow:

            User Question: 'how to buy and who can join?'

            Response:
            How do I buy something?
            Find the item you want, click 'Buy' or 'Add to Cart,' choose your payment method, and confirm your purchase. Once confirmed, arrange with the seller for payment and pickup or delivery. [FAQ_ID: 12]

            Who can join?
            Yonder is exclusively for our university community, so only those with a verified university email can use it. Students, faculty, and staff can access it. No outsiders allowed. [FAQ_ID: 7]

            If any related FAQs exist, add them under 'Related FAQs' at the end(ONLY SAY THE RELATED QUESTION, NOT THE ANSWER, if no related FAQs show nothing not even the 'Related FAQs'.
            BOLD the QUESTION
            ";
        try {
        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $question],
            ],
            'max_tokens' => 200,
        ]);
        $content = $response->choices[0]->message->content ?? 'Sorry, I could not find an answer.';
        } catch (\Exception $e) {
            // Handle errors gracefully
            $content = 'Sorry, the FAQ assistant is temporarily unavailable.';
            $faqId = null;
        }
        // Try to extract FAQ_ID from GPT response
        // Extract FAQ_ID from GPT response
        if (!isset($faqId)) {
            preg_match('/\[FAQ_ID:\s*(\d+)\]/', $content, $matches);
            $faqId = $matches[1] ?? null;
        }
        // Remove the [FAQ_ID: ...] tag from the answer text
        $answerText = preg_replace('/\[FAQ_ID:\s*\d+\]/', '', $content);
        $answerText = trim($answerText);
        return [
            'answer' => $answerText,
            'faq_id' => $faqId,
        ];
    }
}
