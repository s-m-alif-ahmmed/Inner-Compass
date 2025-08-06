<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    use HasFactory;

    protected $fillable = [
        "page_title",
        "page_content",
        "page_slug",
        "status"
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
