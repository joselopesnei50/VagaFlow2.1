<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message_id',
        'company_name',
        'contact_info',
        'status',
        'delivery_status',
        'ai_message',
        'strategy_note',
        'match_score',
        'sent_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
