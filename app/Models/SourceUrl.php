<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="SourceUrl",
 *     type="object",
 *     title="Source URL",
 *     required={"url", "word_id"},
 *     @OA\Property(property="id", type="integer", description="ID of the source URL"),
 *     @OA\Property(property="url", type="string", description="URL source"),
 *     @OA\Property(property="word_id", type="integer", description="ID of the word related to this source"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion timestamp", nullable=true),
 *     @OA\Property(
 *         property="word",
 *         ref="#/components/schemas/Word"
 *     )
 * )
 */
class SourceUrl extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'word_id'];

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
