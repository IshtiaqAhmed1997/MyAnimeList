<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimeList extends Model
{
    protected $fillable = [
        'mal_id',
        'slug',
        'title',
        'title_english',
        'title_japanese',
        'synopsis',
        'source',
        'trailer',
        'airing',
        'aired_from',
        'aired_to',
        'episodes',
        'duration',
        'status',
        'image_url',
        'rating',
        'score',
        'rank',
        'genres',
        'studio',
    ];
}
