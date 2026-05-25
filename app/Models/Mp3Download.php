<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mp3Download extends Model
{
    /**
     * The table associated with this model.
     */
    protected $table = 'mp3_downloads';

    /**
     * Disable the updated_at column — we only track created_at.
     */
    public const UPDATED_AT = null;

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'url',
        'title',
        'ip_address',
        'user_agent',
        'referer',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
