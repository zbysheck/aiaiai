<?php

namespace AIAIAI;

use GuzzleHttp\Client;

class AIClient {
    public function askOpenAI(string $apiKey, string $prompt): string {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? '';
    }

    public function askClaude(string $apiKey, string $prompt): string {
        $client = new Client();
        $response = $client->post('https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'claude-3-opus-20240229',
                'max_tokens' => 1024,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['content'][0]['text'] ?? '';
    }

    public function askGemini(string $apiKey, string $prompt): string {
        $client = new Client();
        $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$apiKey", [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'contents' => [[
                    'parts' => [['text' => $prompt]]
                ]]
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}
