<?php

namespace App\Http\Controllers;

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
}
