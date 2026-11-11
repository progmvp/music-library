<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogFolder extends Model
{
    protected $table = 'catalog_folders';

    protected $fillable = [
        'section_id',
        'folder_code',
        'folder_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(
            CatalogSection::class,
            'section_id'
        );
    }

    public function tracks()
    {
        return $this->hasMany(
            Track::class,
            'folder_id'
        );
    }
}
