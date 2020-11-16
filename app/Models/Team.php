<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    private $fillable = [
        'name', 'description'
    ];

    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }
}
