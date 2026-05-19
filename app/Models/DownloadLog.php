<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    /**
     * The table associated with this model.
     */
    protected $table = 'download_logs';

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
        'status',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'status'     => DownloadStatus::class,
        'created_at' => 'datetime',
    ];
}
