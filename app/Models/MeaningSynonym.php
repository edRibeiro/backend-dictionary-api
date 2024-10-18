<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="MeaningSynonym",
 *     type="object",
 *     title="MeaningSynonym",
 *     description="Synonym for the meaning",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the synonym"
 *     ),
 *     @OA\Property(
 *         property="word",
 *         type="string",
 *         description="Synonym word"
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
class MeaningSynonym extends Model
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
