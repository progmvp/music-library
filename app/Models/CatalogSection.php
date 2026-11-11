<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogSection extends Model
{
    protected $table = 'catalog_sections';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function folders()
    {
        return $this->hasMany(
            CatalogFolder::class,
            'section_id'
        );
    }

    public function levels()
    {
        return $this->hasMany(
            CatalogLevel::class,
            'section_id'
        );
    }

    public function tracks()
    {
        return $this->hasMany(
            Track::class,
            'section_id'
        );
    }
}