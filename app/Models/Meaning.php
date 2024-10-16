<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
