<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Phonetic",
 *     type="object",
 *     title="Phonetic",
 *     required={"text", "audio", "word_id"},
 *     @OA\Property(property="id", type="integer", description="ID of the phonetic"),
 *     @OA\Property(property="text", type="string", description="Phonetic transcription"),
 *     @OA\Property(property="audio", type="string", description="URL of the audio pronunciation"),
 *     @OA\Property(property="word_id", type="integer", description="ID of the word related to this phonetic"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion timestamp", nullable=true),
 *     @OA\Property(
 *         property="word",
 *         ref="#/components/schemas/Word"
 *     )
 * )
 */
class Phonetic extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['text', 'audio', 'source_url', 'license', 'word_id'];

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
