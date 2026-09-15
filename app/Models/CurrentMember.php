<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentMember extends Model
{
    protected $table = 'current_members';

    protected $fillable = [
        'committee',
        'name',
        'email',
        'job',
        'flag_elected',
        'flag_active',
        'flag_staff',
        'flag_suspended',
        'group',
    ];
}
