<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/me",
     *     tags={"User"},
     *     summary="Get current authenticated user",
     *     description="Returns the authenticated user's information.",
     *     operationId="getCurrentUser",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    function me(): JsonResponse
    {
        return response()->json(User::find(auth()->user()->id));
    }

    /**
     * @OA\Get(
     *     path="/user/me/history",
     *     summary="Get the user's word history",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of words that the user has previously added",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="word",
     *                         type="string",
     *                         example="a"
     *                     ),
     *                     @OA\Property(
     *                         property="added",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-10-18T17:29:59"
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="path",
     *                 type="string",
     *                 example="http://localhost/api/user/me/history"
     *             ),
     *             @OA\Property(
     *                 property="per_page",
     *                 type="integer",
     *                 example=15
     *             ),
     *             @OA\Property(
     *                 property="next_cursor",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="next_page_url",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="prev_cursor",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="prev_page_url",
     *                 type="string",
     *                 nullable=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    function history(): JsonResponse
    {
        $history = Word::join('user_word', 'words.id', '=', 'user_word.word_id')
            ->where('user_word.user_id', '=', auth()->user()->id)
            ->select('words.word', 'user_word.created_at as added')
            ->cursorPaginate();
        return response()->json($history->toArray(), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/user/me/favorites",
     *     summary="Get the user's favorite words",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of words that the user has favorited",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="word",
     *                         type="string",
     *                         example="a"
     *                     ),
     *                     @OA\Property(
     *                         property="added",
     *                         type="string",
     *                         format="date-time",
     *                         example="2024-10-18T17:29:59"
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="path",
     *                 type="string",
     *                 example="http://localhost/api/user/me/favorites"
     *             ),
     *             @OA\Property(
     *                 property="per_page",
     *                 type="integer",
     *                 example=15
     *             ),
     *             @OA\Property(
     *                 property="next_cursor",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="next_page_url",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="prev_cursor",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="prev_page_url",
     *                 type="string",
     *                 nullable=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    function favorites(): JsonResponse
    {
        $history = Word::join('favorites', 'words.id', '=', 'favorites.word_id')
            ->where('favorites.user_id', '=', auth()->user()->id)
            ->select('words.word', 'favorites.created_at as added')
            ->cursorPaginate();
        return response()->json($history->toArray(), Response::HTTP_OK);
    }
}
