<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    /**
     * The table associated with this model.
     */
    protected $table = 'faqs';

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'question',
        'answer',
        'order',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'order'      => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
