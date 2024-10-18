<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Definition",
 *     type="object",
 *     title="Definition",
 *     description="Definition model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the definition"
 *     ),
 *     @OA\Property(
 *         property="definition",
 *         type="string",
 *         description="Text of the definition"
 *     ),
 *     @OA\Property(
 *         property="example",
 *         type="string",
 *         description="Example usage of the word in the definition"
 *     ),
 *     @OA\Property(
 *         property="meaning_id",
 *         type="integer",
 *         description="ID of the related meaning"
 *     ),
 *     @OA\Property(
 *         property="meaning",
 *         ref="#/components/schemas/Meaning",
 *         description="The related meaning"
 *     ),
 *     @OA\Property(
 *         property="synonyms",
 *         type="array",
 *         description="List of synonyms for the definition",
 *         @OA\Items(ref="#/components/schemas/DefinitionSynonym")
 *     ),
 *     @OA\Property(
 *         property="antonyms",
 *         type="array",
 *         description="List of antonyms for the definition",
 *         @OA\Items(ref="#/components/schemas/DefinitionAntonym")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the definition"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update timestamp of the definition"
 *     )
 * )
 */
class Definition extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['definition', 'example', 'meaning_id'];

    public function meaning(): BelongsTo
    {
        return $this->belongsTo(Meaning::class);
    }

    function synonyms(): HasMany
    {
        return $this->hasMany(DefinitionSynonym::class);
    }

    function antonyms(): HasMany
    {
        return $this->hasMany(DefinitionAntonym::class);
    }
}
