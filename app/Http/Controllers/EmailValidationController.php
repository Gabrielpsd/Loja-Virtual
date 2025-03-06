<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmailValidationController extends Controller
{
    public function validateEmail(Request $request)
    {
        $email = $request->input('email');
        $apiKey = env('ABSTRACT_API_KEY'); // Store your API key in .env

        $response = Http::get('https://emailvalidation.abstractapi.com/v1/', [
            'api_key' => $apiKey,
            'email' => $email,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $isValid = $data['is_valid_format']['value'] && $data['deliverability'] === 'DELIVERABLE';
            return response()->json([
                'is_valid' => $isValid,
                'details' => $data
            ]);
        }

        return response()->json(['error' => 'API request failed'], 500);
    }
}
