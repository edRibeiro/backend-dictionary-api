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
        $data = Word::where(function (Builder $query) {
            if (request()->has('search')) {
                $query->where('word', 'LIKE', request()->query('search') . "%");
            }
        })->orderBy('word')->select('word')->cursorPaginate(request()->query('limit', 15));
        $responseArray = $data->toArray();
        $responseArray['data'] = Arr::map($responseArray['data'], function (array $item, int $key) {
            return $item['word'];
        });
        return response()->json($responseArray, 200, ['X-Cache' => 'MISS']);
    }

    function show(string $word)
    {
        $cacheKey = 'word_' . $word;
        $readCache = 'HIT';
        try {
            $data = Cache::get($cacheKey);
            if (!$data) throw new Exception("MISS Cache item", 1);
        } catch (Exception $cacheError) {
            Log::warning('Cache error in Dictionary index: ' . $cacheError->getMessage());
            try {
                $data = Word::with(['phonetics', 'meanings' => ['definitions' => ['synonyms', 'antonyms'], 'synonyms', 'antonyms'], 'sourceUrls'])->where('word', '=', $word)->firstOrFail();
                Cache::put($cacheKey, $data);
                $readCache = 'MISS';
            } catch (Exception $error) {
                Log::critical('Dictionary index error: ' . $error->getMessage());
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }
        }

        $user = $data->viewer()->where('user_id', '=', auth()->user()->id)->first();
        if (!$user) {
            $data->viewer()->attach(auth()->user()->id);
        }
        return (new WordResource($data))->response()
            ->header('X-Cache', $readCache)
        ;
    }

    function favorite(string $word): JsonResponse
    {
        try {
            $wordData = Word::where('word', '=', $word)->firstOrFail();
            $user = $wordData->users()->where('user_id', '=', auth()->user()->id)->first();
            if (!$user) {
                $wordData->users()->attach(auth()->user()->id);
            }
            return response()->noContent();
        } catch (Exception $error) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ], Response::HTTP_NOT_FOUND);
        }
    }

    function unfavorite(string $word): JsonResponse
    {
        try {
            $wordData = Word::where('word', '=', $word)->firstOrFail();
            $user = $wordData->users()->where('user_id', '=', auth()->user()->id)->first();
            if (!!$user) {
                $wordData->users()->detach(auth()->user()->id);
            }
            return response()->noContent();
        } catch (Exception $error) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
