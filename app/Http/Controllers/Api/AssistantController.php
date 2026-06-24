<?php

namespace App\Http\Controllers\Api;

use App\Services\StoreAssistantService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function respond(Request $request, StoreAssistantService $assistant)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        return response()->json(
            $assistant->respond(
                $validated['message'],
                $request->user(),
                $validated['limit'] ?? 5,
            )
        );
    }
}
