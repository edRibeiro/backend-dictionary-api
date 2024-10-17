<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordResource;
use App\Models\Word;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WordController extends Controller
{
    function index(): JsonResponse
    {
        $wordCursorPaginated = Word::where(function (Builder $query) {
            if (request()->has('search')) {
                $query->where('word', 'LIKE', request()->query('search') . "%");
            }
        })->orderBy('word')->select('word')->cursorPaginate(request()->query('limit', 15));
        $responseArray = $wordCursorPaginated->toArray();
        $responseArray['data'] = Arr::map($responseArray['data'], function (array $item, int $key) {
            return $item['word'];
        });
        return response()->json($responseArray);
    }

    function show(string $word)
    {
        $cacheKey = 'word_' . $word;
        try {
            $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($word) {
                return Word::with(['phonetics', 'meanings' => ['definitions' => ['synonyms', 'antonyms'], 'synonyms', 'antonyms'], 'sourceUrls'])->where('word', '=', $word)->firstOrFail();
            });
        } catch (Exception $cacheError) {
            Log::warning('Cache error in Dictionary index: ' . $cacheError->getMessage());
            try {
                $data = Word::with(['phonetics', 'meanings' => ['definitions' => ['synonyms', 'antonyms'], 'synonyms', 'antonyms'], 'sourceUrls'])->where('word', '=', $word)->firstOrFail();
            } catch (Exception $error) {
                Log::critical('Dictionary index error: ' . $error->getMessage());
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }
        }
        $user = $data->users()->where('user_id', '=', auth()->user()->id)->first();
        if (!$user) {
            $data->users()->attach(auth()->user()->id);
        }
        return new WordResource($data);
    }
}
