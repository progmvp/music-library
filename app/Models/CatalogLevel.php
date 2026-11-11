<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogLevel extends Model
{
    protected $fillable = [
        'section_id',
        'level_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(CatalogSection::class, 'section_id');
    }

    public function tracks()
    {
        return $this->belongsToMany(
            Track::class,
            'track_levels',
            'level_id',
            'track_id'
        );
    }
}