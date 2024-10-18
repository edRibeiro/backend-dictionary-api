<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Word",
 *     type="object",
 *     title="Word",
 *     required={"word", "license", "license_url"},
 *     @OA\Property(property="id", type="integer", description="ID of the word"),
 *     @OA\Property(property="word", type="string", description="The word"),
 *     @OA\Property(property="license", type="string", description="License of the word"),
 *     @OA\Property(property="license_url", type="string", description="License URL of the word"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion timestamp", nullable=true),
 *     @OA\Property(
 *         property="phonetics",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Phonetic")
 *     ),
 *     @OA\Property(
 *         property="meanings",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Meaning")
 *     ),
 *     @OA\Property(
 *         property="sourceUrls",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SourceUrl")
 *     ),
 *     @OA\Property(
 *         property="viewer",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/User")
 *     ),
 *     @OA\Property(
 *         property="users",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/User")
 *     ),
 * )
 */
class Word extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word', 'license', 'license_url'];

    function phonetics(): HasMany
    {
        return $this->hasMany(Phonetic::class);
    }

    function meanings(): HasMany
    {
        return $this->hasMany(Meaning::class);
    }

    function sourceUrls(): HasMany
    {
        return $this->hasMany(SourceUrl::class);
    }

    public function viewer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_word')->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
