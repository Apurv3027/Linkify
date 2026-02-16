<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'clicks',
        'file_path',
        'type',
        'downloads',
    ];

    protected $casts = [
        'clicks' => 'integer',
    ];

    /**
     * Link belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Link has many Clicks
     */
    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
