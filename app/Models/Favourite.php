<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'audio_id',
    ];

    protected $hidden = [
      'created_at',
      'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audio()
    {
        return $this->belongsTo(Audio::class);
    }

}
