<?php

namespace App\Service;

use OpenAI;

class OpenAiService
{
    public static function chat(string $messages)
    {
        $client = OpenAI::client('');
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $messages],
                ['role' => 'user', 'content' => $messages],

            ],
        ]);

        return $result->choices[0]->message->content;
    }
}
