<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $table = 'committees';

    protected $fillable = [
        'token',
        'name',
        'short_name',
        'default_weekday',
        'default_time',
        'default_address',
        'default_room',
        'wiki_internal_minutes',
        'default_latitude',
        'default_longitude',
        'minutes_in_wiki',
        'minutes_structure',
    ];
}
