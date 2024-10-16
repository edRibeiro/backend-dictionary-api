<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function me(): JsonResponse
    {
        return response()->json(User::find(auth()->user()->id));
    }

    function history(): JsonResponse
    {
        return response()->json(['message' => 'Not Implemented'], 501);
    }

    function favorites(): JsonResponse
    {
        return response()->json(['message' => 'Not Implemented'], 501);
    }
}
