<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    protected $table = 'motions';

    protected $fillable = [
        'agenda_item',
        'text',
        'created_by',
    ];
}
