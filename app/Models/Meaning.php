<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Meaning",
 *     type="object",
 *     title="Meaning",
 *     required={"part_of_speech", "word_id"},
 *     @OA\Property(property="id", type="integer", description="ID of the meaning"),
 *     @OA\Property(property="part_of_speech", type="string", description="Part of speech"),
 *     @OA\Property(property="word_id", type="integer", description="ID of the word related to this meaning"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion timestamp", nullable=true),
 *     @OA\Property(
 *         property="word",
 *         ref="#/components/schemas/Word"
 *     ),
 *     @OA\Property(
 *         property="definitions",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Definition")
 *     ),
 *     @OA\Property(
 *         property="synonyms",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/MeaningSynonym")
 *     ),
 *     @OA\Property(
 *         property="antonyms",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/MeaningAntonym")
 *     ),
 * )
 */
class Meaning extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['part_of_speech', 'word_id'];

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }

    function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    function synonyms(): HasMany
    {
        return $this->hasMany(MeaningSynonym::class);
    }

    function antonyms(): HasMany
    {
        return $this->hasMany(MeaningAntonym::class);
    }
}
