<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name', 
        'partialFile', 
        'description', 
        'disableSection'
    ];

    protected $casts = [
        'disableSection' => 'boolean',
    ];
}
