<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="MeaningAntonym",
 *     type="object",
 *     title="MeaningAntonym",
 *     description="Antonym for the meaning",
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
 *         property="meaning_id",
 *         type="integer",
 *         description="ID of the related meaning"
 *     ),
 *     @OA\Property(
 *         property="meaning",
 *         ref="#/components/schemas/Meaning",
 *         description="The related meaning"
 *     )
 * )
 */
class MeaningAntonym extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word', 'meaning_id'];

    public function meaning(): BelongsTo
    {
        return $this->belongsTo(Meaning::class);
    }
}
