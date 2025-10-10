<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class WordFilter
{
    public static function filter($message)
    {
        // If message is empty, return it as is
        if (empty($message)) {
            return $message;
        }

        // Get bad words from our config file
        $badWords = config('badwords.words');

        // Log the original message (for debugging)
        Log::info('Message before filtering: ' . $message);

        // Replace each bad word with first and last letter visible
        foreach ($badWords as $word) {
            // Skip if the bad word is empty or too short
            if (empty($word) || strlen($word) <= 2) continue;

            // Create the pattern to match the word case-insensitively
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            
            // Replace matched words with censored version
            $message = preg_replace_callback($pattern, function($match) {
                $word = $match[0];
                $len = strlen($word);
                
                // Keep original case of first and last letters
                $first = $word[0];
                $last = $word[$len - 1];
                $middle = str_repeat('*', $len - 2);
                
                return $first . $middle . $last;
            }, $message);
        }

        // Log the filtered message (for debugging)
        Log::info('Message after filtering: ' . $message);

        return $message;
    }
}
