<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OidcBackchannelSession extends Model
{
    protected $table = 'oidc_backchannel_sessions';

    protected $fillable = [
        'sub',
        'sid',
        'laravel_session_id',
    ];
}
