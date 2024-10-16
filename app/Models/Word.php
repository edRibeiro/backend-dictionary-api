<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
