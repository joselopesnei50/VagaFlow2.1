<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'location',
        'service',
        'status',
        'error_message',
        'results_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
