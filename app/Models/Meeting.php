<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'meetings';

    protected $fillable = [
        'committee',
        'name',
        'date',
        'time',
        'address',
        'latitude',
        'longitude',
        'room',
        'meeting_chairs',
        'minute_takers',
        'url_internal',
        'url_draft',
        'url_public',
        'minutes_approved_resolution',
        'uid',
        'minutes_structure',
        'ignore_minutes',
    ];
}
