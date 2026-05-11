<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StumvRole extends Model
{
    protected $table = 'stumv_roles';

    protected $fillable = [
        'committee',
        'group',
        'flag_elected',
        'flag_active',
        'flag_staff',
        'stumv_committee',
        'stumv_role',
    ];
}
