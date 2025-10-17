<?php

namespace Newnet\Cms\Services;

use OpenAI;

class ChatGPTService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(setting('chatgpt_api_key', config('cms.cms.translate.openai_api_key')));
    }

    /**
     * Handles the content based on the given prompt.
     *
     * @param string $prompt The prompt to base the content handling on.
     * @param mixed $content The content to be handled.
     * @return mixed The result after handling the content based on the prompt.
     */
    public function handleContentBaseOnPrompt($prompt, $content)
    {
        $chatGptModel = setting('chatgpt_model', 'gpt-3.5-turbo');
        if (strlen($content) > 4000) {
            $chunks = str_split($content, 4000);
            $contentChunks = [];
            foreach ($chunks as $chunk) {
                $response = $this->client->chat()->create([
                    'model' => $chatGptModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $prompt],
                        ['role' => 'user', 'content' => utf8_encode($chunk)],
                    ],
                ]);

                $contentChunks[] = $response['choices'][0]['message']['content'];
            }
            return implode('', $contentChunks);
        }
        $response = $this->client->chat()->create([
            'model' => $chatGptModel,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $prompt,
                ],
                [
                    'role' => 'user',
                    'content' => utf8_encode($content),
                ],
            ],
        ]);

        return $response['choices'][0]['message']['content'] ?? null;
    }
}
