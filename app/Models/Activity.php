<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['type', 'entity_type', 'entity_id', 'actor_id', 'summary'];

    protected $casts = [
        'summary' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}