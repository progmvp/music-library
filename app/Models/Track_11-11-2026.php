<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'article',
        'track_name',

        'section_id',
        'folder_id',

        'duration',

        'price_mp3',
        'price_flac',
        'price_bundle',

        'preview_code',

        'waveform_json',

        'year',

        'views',
        'downloads',

        'is_active',
    ];

    protected $casts = [
        'waveform_json' => 'array',
        'is_active'     => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(CatalogSection::class);
    }

    public function folder()
    {
        return $this->belongsTo(CatalogFolder::class);
    }

    public function levels()
    {
        return $this->belongsToMany(
            CatalogLevel::class,
            'track_levels',
            'track_id',
            'level_id'
        );
    }

    public function musicStyles()
    {
        return $this->belongsToMany(
            CatalogMusicStyle::class,
            'track_music_styles',
            'track_id',
            'music_style_id'
        );
    }

    public function getPreviewUrlAttribute(): string
    {
        return 'https://podberimuzyku.ru/' . $this->preview_code;
    }
}
