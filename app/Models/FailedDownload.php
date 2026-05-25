<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedDownload extends Model
{
    /**
     * The table associated with this model.
     */
    protected $table = 'failed_downloads';

    /**
     * Disable the updated_at column — we only track created_at.
     */
    public const UPDATED_AT = null;

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'url',
        'ip_address',
        'reason',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
