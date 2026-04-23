<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_role',
        'whatsapp_number',
        'cv_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
