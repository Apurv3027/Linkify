<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $fillable = [
        'link_id',
        'ip_address',
        'country',
        'referrer',
        'device',
        'browser',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
