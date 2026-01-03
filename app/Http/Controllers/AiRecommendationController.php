<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;

class AiRecommendationController extends Controller
{
    public function recommend(Request $request, AIService $ai)
    {
        $payload = $request->input('payload', []);
        $template = resource_path('prompts/recommendation_template.txt');

        try {
            $result = $ai->recommendFromPayload($payload, $template);
            return response()->json(['success' => true, 'result' => $result]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
