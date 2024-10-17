<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    function me(): JsonResponse
    {
        return response()->json(User::find(auth()->user()->id));
    }

    function history(): JsonResponse
    {
        $history = Word::join('user_word', 'words.id', '=', 'user_word.word_id')
            ->where('user_word.user_id', '=', auth()->user()->id)
            ->select('words.word', 'user_word.created_at as added')
            ->cursorPaginate();
        return response()->json($history->toArray(), Response::HTTP_OK);
    }

    function favorites(): JsonResponse
    {
        $history = Word::join('favorites', 'words.id', '=', 'favorites.word_id')
            ->where('favorites.user_id', '=', auth()->user()->id)
            ->select('words.word', 'favorites.created_at as added')
            ->cursorPaginate();
        return response()->json($history->toArray(), Response::HTTP_OK);
    }
}
