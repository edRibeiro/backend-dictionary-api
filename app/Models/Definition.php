<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
