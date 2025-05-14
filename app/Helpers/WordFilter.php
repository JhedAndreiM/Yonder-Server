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

        // Replace each bad word with asterisks
        foreach ($badWords as $word) {
            // Skip if the bad word is empty
            if (empty($word)) continue;

            // Create asterisks of the same length as the bad word
            $asterisks = str_repeat('*', strlen($word));
            
            // Replace the bad word with asterisks (case insensitive)
            $message = str_ireplace($word, $asterisks, $message);
        }

        // Log the filtered message (for debugging)
        Log::info('Message after filtering: ' . $message);

        return $message;
    }
}
