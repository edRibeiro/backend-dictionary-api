<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     required={"id", "name", "email"},
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         example="9d43789a-1553-470c-b0ee-b33e000139bb",
 *         description="User's unique identifier"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="User 1",
 *         description="User's name"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="example@email.com",
 *         description="User's email address"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-17T02:11:57.000000Z",
 *         description="Timestamp when the user was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-17T02:11:57.000000Z",
 *         description="Timestamp when the user was last updated"
 *     ),
 *     @OA\Property(
 *         property="history",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Word")
 *     ),
 *     @OA\Property(
 *         property="favorites",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Word")
 *     ),
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function history(): BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'user_word', 'user_id', 'word_id')
            ->withTimestamps()->withPivot('created_at');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'favorites', 'user_id', 'word_id')
            ->withTimestamps()->withPivot('created_at');
    }
}
