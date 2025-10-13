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
        $faqs = FaqQuestion::all(['id', 'question', 'answer']);
        
        // First, try a direct database search for exact/partial matches
        $directMatch = $faqs->first(function($faq) use ($question) {
            return str_contains(strtolower($faq->question), strtolower($question)) || 
                   str_contains(strtolower($faq->answer), strtolower($question));
        });
        
        if ($directMatch) {
            \Log::info("Direct match found for query '{$question}': {$directMatch->question}");
            return [
                'answer' => "**{$directMatch->question}**\n\n{$directMatch->answer}",
                'faq_id' => $directMatch->id,
            ];
        }
        
        $faqListText = "Here are the existing FAQs:\n";
        foreach ($faqs as $faq) {
            $faqListText .= "FAQ ID: {$faq->id}\n";
            $faqListText .= "Question: {$faq->question}\n";
            $faqListText .= "Answer: {$faq->answer}\n\n";
        }
        
        // Debug: Log the search query and FAQ list for troubleshooting
        \Log::info("FAQ Search Query: " . $question);
        \Log::info("No direct match found, sending to OpenAI. FAQ count: " . $faqs->count());
        $systemPrompt = "You are a Yonder FAQ assistant for the university marketplace. Your job is to respond **only using the FAQs in the database**. 

            MATCHING RULES:
            - Search through BOTH questions AND answers for ANY partial word matches
            - If the user searches for 'should', match FAQs containing 'should', 'shouldn't', 'shouldnt', etc.
            - If the user searches for 'buy', match FAQs containing 'buy', 'buying', 'purchase', etc.
            - Be VERY LIBERAL in matching - even if just part of a word matches, include it
            - Look for the search term ANYWHERE in the question or answer text
            
            RESPONSE FORMAT:
            - Return the MOST RELEVANT FAQ that contains any part of the search term
            - Include the FAQ ID in the format: [FAQ_ID: <id>]
            - BOLD the FAQ question
            - If absolutely no FAQ contains any part of the search term, say 'No relevant FAQs found'
            
            IMPORTANT: Be generous with matches. If searching for 'should' and there's a FAQ with 'shouldn't show', that's a match!

            FAQ List:
            " . $faqListText . "

            Examples of matching:

            User searches: 'should'
            FAQ exists: 'shouldn't show' 
            Result: MATCH! Return that FAQ because 'should' is contained in 'shouldn't'

            User searches: 'buy'  
            FAQ exists: 'How do I buy something?'
            Result: MATCH! Return that FAQ

            User searches: 'sell'
            FAQ exists: 'What can I sell?'  
            Result: MATCH! Return that FAQ

            Format example:
            **FAQ Question Here**
            FAQ answer here [FAQ_ID: X]

            CRITICAL: If the search term appears ANYWHERE in a question or answer (even as part of another word), it's a match!
            ";
        try {
        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $question],
            ],
            'max_tokens' => 300,
        ]);
        $content = $response->choices[0]->message->content ?? 'Sorry, I could not find an answer.';
        
        // Debug: Log OpenAI response
        \Log::info("OpenAI Response for query '{$question}':", ['response' => $content]);
        
        } catch (\Exception $e) {
            // Handle errors gracefully
            $content = 'Sorry, the FAQ assistant is temporarily unavailable.';
            $faqId = null;
            \Log::error("OpenAI Error for query '{$question}':", ['error' => $e->getMessage()]);
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
