<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrismTestController extends Controller
{
    public function quickTest(Request $request)
    {
        // simple inline prompt
        $reply = app('prism')
            ->text()
            ->using('openai', env('OPENAI_MODEL','gpt-3.5-turbo'))
            ->withPrompt('Give one short training recommendation for a Loan Officer.')
            ->asText();

        return response()->json(['ai' => $reply]);
    }
}
