<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function getRelatedMovies()
    {
        return $this->belongsToMany(Movie::class, 'playlist_movies', 'playlist_id', 'movie_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'playlist_users', 'playlist_id', 'user_id');
    }
}
