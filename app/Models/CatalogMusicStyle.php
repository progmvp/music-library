<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogMusicStyle extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tracks()
    {
        return $this->belongsToMany(
            Track::class,
            'track_music_styles',
            'music_style_id',
            'track_id'
        );
    }
}