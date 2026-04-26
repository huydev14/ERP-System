<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthConfig extends Model
{
    protected $table = 'oauth_configs';

    protected $fillable = [
        'provider',
        'client_id',
        'client_secret',
        'redirect_uri',
        'scopes',
        'additional_config',
        'is_active',
    ];

    protected $casts = [
        'client_secret' => 'encrypted',
        'scopes' => 'array',
        'additional_config' => 'array',
        'is_active' => 'boolean',
    ];
}
