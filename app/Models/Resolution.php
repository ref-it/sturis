<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    protected $table = 'resolutions';

    protected $fillable = [
        'committee',
        'meeting',
        'number',
        'type',
        'text',
        'yes',
        'no',
        'abstention',
    ];
}
