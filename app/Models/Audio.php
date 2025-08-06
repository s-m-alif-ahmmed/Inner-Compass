<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Audio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'audio',
        'duration',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getThumbnailAttribute($value){
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url(Storage::url($value));
        }
        return $value;
    }

    public function getAudioAttribute($value){
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url(Storage::url($value));
        }
        return $value;
    }

    protected static function booted(): void
    {
        static::updating(function ($audio) {
            if ($audio->isDirty('thumbnail')) {
                $oldThumbnail = $audio->getOriginal('thumbnail');
                if ($oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
            }

            if ($audio->isDirty('audio')) {
                $oldAudio = $audio->getOriginal('audio');
                if ($oldAudio && Storage::disk('public')->exists($oldAudio)) {
                    Storage::disk('public')->delete($oldAudio);
                }
            }
        });

        static::deleting(function ($audio) {
            if ($audio->thumbnail && Storage::disk('public')->exists($audio->thumbnail)) {
                Storage::disk('public')->delete($audio->thumbnail);
            }

            if ($audio->audio && Storage::disk('public')->exists($audio->audio)) {
                Storage::disk('public')->delete($audio->audio);
            }
        });
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

}
