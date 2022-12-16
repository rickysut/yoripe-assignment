<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'picture',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
