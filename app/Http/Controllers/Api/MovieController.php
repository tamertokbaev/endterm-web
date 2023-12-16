<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    public function getFeaturedMovies()
    {
        return response()->json([
            'message' => 'success',
            'movies' => Movie::whereNotNull('banner_url')->take(12)->get()
        ]);
    }

    public function getPopularMovies()
    {
        return response()->json([
            'message' => 'success',
            'movies' => Movie::orderBy('rating', 'desc')->take(36)->get()
        ]);
    }

    public function getLastReleasedMovies()
    {
        return response()->json([
           'message' => 'success',
           'movies' => Movie::orderBy('updated_at', 'desc')->take(36)->get()
        ]);
    }

    public function getMovieInfo(Request $request)
    {
        $movie_id = $request->movie_id;
        return response()->json([
            'message' => 'success',
            'movie' => Movie::with('genres')->where('id', $movie_id)->first()
        ]);
    }

    public function addOrRemoveToFavoriteMovie(Request $request)
    {
        $user = $request->user();
        $movieId = $request->movie_id;

        $user
            ->getFavoriteMovies()
            ->toggle([$movieId]);

        $favorites = $user->getFavoriteMovies()->get();
        return response()->json([
            'message' => 'success',
            'favorites' => $favorites
        ]);
    }

    public function getFavoriteMovies(Request $request)
    {
        $user = $request->user();

        $favorites = $user->getFavoriteMovies()->get();

        return response()->json([
            'message' => 'success',
            'favorites' => $favorites
        ]);
    }

    public function search(Request $request)
    {
        $slug = $request->slug;
        $results =
            Movie::where('title', 'like', '%'.$slug.'%')
                ->orWhere('description', 'like', '%'.$slug.'%')
                ->take(15)
                ->get();

        return response()->json([
            'message' => 'success',
            'results' => $results
        ]);
    }

    public function similar(Request $request)
    {
        $movies = DB::table('movies')
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        return response()->json([
           'message' => 'success',
           'movies' => $movies
        ]);
    }

    public function getGenres()
    {
        return response()->json([
           'message' => 'success',
           'genres' => Genre::all()
        ]);
    }

    public function getMoviesByGenre(Request $request)
    {
        $genreId = $request->genreId;


        return response()->json([
           'message' => 'success',
           'movies' => Genre::find($genreId)->movies()->take(15)->get()
        ]);
    }
}
