<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals';

    protected $fillable = [
        'committee',
        'name',
    ];
}
