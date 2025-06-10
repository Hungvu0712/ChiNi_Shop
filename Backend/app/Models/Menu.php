<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'prant_id',
        'name',
        'slug',
        'url',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'prant_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'prant_id');
    }
}
