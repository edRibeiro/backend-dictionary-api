<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="DefinitionAntonym",
 *     type="object",
 *     title="DefinitionAntonym",
 *     description="Antonym for the definition",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the antonym"
 *     ),
 *     @OA\Property(
 *         property="word",
 *         type="string",
 *         description="Antonym word"
 *     ),
 *     @OA\Property(
 *         property="definition_id",
 *         type="integer",
 *         description="ID of the related definition"
 *     ),
 *     @OA\Property(
 *         property="definition",
 *         ref="#/components/schemas/Definition",
 *         description="The related definition"
 *     )
 * )
 */
class DefinitionAntonym extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word', 'definition_id'];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(Meaning::class);
    }
}
