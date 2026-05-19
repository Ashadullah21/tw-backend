<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Store a new contact message.
     *
     * POST /api/contact
     */
    public function store(Request $request): JsonResponse
    {
        // Set up validator manually to control JSON response shape on failure
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Create the message in database
        ContactMessage::create($validator->validated());

        return response()->json([
            'message' => 'Thank you, your message was sent.',
        ], 200);
    }
}
