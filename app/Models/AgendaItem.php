<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaItem extends Model
{
    protected $table = 'agenda_items';

    protected $fillable = [
        'committee',
        'meeting',
        'structure_id',
        'parent',
        'order',
        'title',
        'text',
        'people',
        'expected_duration',
        'goals',
        'guest',
        'internal',
        'created_by'
    ];
}
