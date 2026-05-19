<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivitySummary extends Model
{
    use HasFactory;

    // Explicitly define the table name as requested
    protected $table = 'user_activity_summary';

    // Disable standard created_at/updated_at timestamps to avoid created_at column errors
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'total_requests',
        'total_success',
        'total_failed',
        'last_seen_at',
        'updated_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'updated_at'   => 'datetime',
    ];
}
