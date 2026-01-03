<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Send a payload to the prompt template and return decoded JSON (or throw).
     *
     * @param array $payload  // the data the AI will consume (employee array etc)
     * @param string $templatePath  // resource_path('prompts/recommendation_template.txt')
     * @param string $model
     * @return array
     * @throws \RuntimeException
     */
    public function recommendFromPayload(array $payload, string $templatePath, string $model = null): array
    {
        $model = $model ?? env('OPENAI_MODEL', 'gpt-3.5-turbo');

        // 1) load template and inject payload JSON
        $template = file_get_contents($templatePath);
        $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // replace placeholder (adjust the placeholder name as in your file)
        $prompt = str_replace('{{payload_json}}', $jsonPayload, $template);

        // call Prism
        $raw = app('prism')
            ->text()
            ->using(env('PRISM_PROVIDER', 'openai'), $model)
            ->withPrompt($prompt)
            ->asText(); // may return string OR an object

        // ---------- Normalize to string (handle Prism Response object) ----------
        $rawText = null;

        if (is_string($raw)) {
            $rawText = $raw;
        } elseif (is_object($raw)) {
            // 1) Common: Prism Response has a public 'text' property
            if (property_exists($raw, 'text') && is_string($raw->text)) {
                $rawText = $raw->text;
            }
            // 2) Fallback: Prism Response has a 'steps' collection -> first()->text
            elseif (property_exists($raw, 'steps') && is_object($raw->steps)) {
                try {
                    $first = null;
                    if (method_exists($raw->steps, 'first')) {
                        $first = $raw->steps->first();
                    } elseif (is_array($raw->steps) && count($raw->steps)) {
                        $first = $raw->steps[0];
                    }
                    if ($first && (property_exists($first, 'text') && is_string($first->text))) {
                        $rawText = $first->text;
                    }
                } catch (\Throwable $e) {
                    // continue to other fallbacks
                }
            }

            // 3) If still null, try method accessors
            if ($rawText === null) {
                if (method_exists($raw, 'getText')) {
                    $rawText = $raw->getText();
                } elseif (method_exists($raw, 'text')) {
                    $rawText = $raw->text();
                } elseif (method_exists($raw, '__toString')) {
                    $rawText = (string) $raw;
                } else {
                    // last resort: stringify the object
                    $rawText = print_r($raw, true);
                }
            }
        } else {
            // other types, cast to string
            $rawText = (string) $raw;
        }

        // ---------- Parse JSON robustly ----------
        $decoded = json_decode($rawText, true);

        // 1) preg_match curly braces block fallback
        if (!is_array($decoded) && preg_match('/\{.*\}/s', $rawText, $m)) {
            $maybe = json_decode($m[0], true);
            if (is_array($maybe)) $decoded = $maybe;
        }

        // 2) last-resort: first '{' to last '}' extraction
        if (!is_array($decoded)) {
            $start = strpos($rawText, '{');
            $end = strrpos($rawText, '}');
            if ($start !== false && $end !== false && $end > $start) {
                $maybeJson = substr($rawText, $start, $end - $start + 1);
                $maybe = json_decode($maybeJson, true);
                if (is_array($maybe)) $decoded = $maybe;
            }
        }

        // final check — if still not array, log and throw
        if (!is_array($decoded)) {
            Log::error('AI returned invalid JSON', ['raw' => $rawText]);
            throw new \RuntimeException('Invalid JSON from AI');
        }

        return $decoded;
    }
}
