<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $guarded = [];

    public static $allowed = ['facebook', 'twitter', 'google'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
