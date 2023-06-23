<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIController extends Controller
{

  public function index(Request $request)
  {
    $search = $request->message;

    $data = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),

    ])
      ->withOptions(['verify' => false])
      ->post("https://api.openai.com/v1/chat/completions", [
        "model" => "gpt-3.5-turbo",
        'messages' => [
          [
            "role" => "user",
            "content" => $search
          ],

        ],
        'temperature' => 0.5,
        "max_tokens" => 200,
        "top_p" => 1.0,
        "frequency_penalty" => 0.52,
        "presence_penalty" => 0.5,
        "stop" => ["11."],
      ])
      ->json();
      if($data['error'])
      {
        return response()->json(['message'=>$data['error']['message'],'success'=>false]);
      }
      else
      {
        return response()->json($data['error']['message'], 200, array(), JSON_PRETTY_PRINT);
      }
  }

  public function chat(Request $request)
    {
        // Get the user's message from the request
        // $message = $request->input('message');
        $message = 'When will ios 17 release?';

        // Set the OpenAI Chat API endpoint
        $url = 'https://api.openai.com/v1/chat/completions';

        // Set the request payload
        $payload = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $message],
            ],
        ];

        // Create a new Guzzle HTTP client
        $client = new Client(['verify' => false]);

        try {
            // Send a POST request to the OpenAI Chat API
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            // Decode the response JSON
            $data = json_decode($response->getBody(), true);

            // Get the chatbot's response
            $chatResponse = $data['choices'][0]['message']['content'];

            // Return the chat response to the client
            return response()->json([
                'message' => $chatResponse,
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
