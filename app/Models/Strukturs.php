<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strukturs extends Model
{
    use HasFactory;

    protected $table = 'strukturs';

    protected $fillable = [
        'struktur',
        'name',
        'email'
    ];
}
