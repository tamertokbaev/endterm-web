<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function createMovie(Request $request)
    {
        $user = $request->user();
        if (isset($user)) {
            $movie = Movie::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_url' => $request->image_url,
                'banner_url' => $request->banner_url,
                'imdb_url' => $request->imdb_url,
                'release_date' => $request->release_date,
                'preview_url' => $request->preview_url,
                'is_premium' => $request->is_premium
            ]);

            return response()->json([
                'message' => 'success',
                'movie' => $movie
            ]);
        }
        return response()->json([
            'message' => 'error',
        ]);
    }

    public function getListOfMovies(Request $request)
    {
        $rows = $request->rows;
        $offset = $request->page * $rows;

        $result = Movie::orderBy('updated_at', 'desc');

        $count = $result->count();
        $items = $result
            ->take($rows)
//            ->skip($offset)
            ->get();

        return [
            'message' => 'success',
            'items' => $items,
            'total' => $count
        ];
    }

    public function getMovie(Request $request)
    {
        $movieId = $request->movie_id;
        return response()->json([
            'message' => 'success',
            'movie' => Movie::with('genres')->where('id', $movieId)->first()
        ]);
    }

    public function getAllMovies(Request $request)
    {
        return response()->json([
           'message' => 'success',
           'movies' => Movie::all()
        ]);
    }

    public function deleteMovie(Request $request)
    {
        $movie_id = $request->movie_id;
        $movie = Movie::find($movie_id);
        $movie->delete();

        return response()->json([
            'message' => 'success',
            'movie_id' => $movie_id
        ]);
    }

    public function updateMovie(Request $request)
    {
        $movie_id = $request->movie_id;
        $movie = Movie::where('id', $movie_id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'imdb_url' => $request->imdb_url,
            'image_url' => $request->image_url,
            'release_date' => $request->release_date,
            'banner_url' => $request->banner_url,
            'preview_url' => $request->preview_url,
            'is_premium' => $request->is_premium
        ]);


        return response()->json([
            'message' => 'success',
            'movie_id' => $movie_id,
            'movie' => $movie
        ]);
    }

    public function createGenre(Request $request)
    {
        $user = $request->user();
        if (isset($user)) {
            $genre = Genre::create([
                'genre_name' => $request->genre_name,
            ]);

            return response()->json([
                'message' => 'success',
                'genre' => $genre
            ]);
        }
        return response()->json([
            'message' => 'error',
        ]);
    }

    public function getListOfGenres(Request $request)
    {
        $rows = $request->rows;
        $offset = $request->page * $rows;

        $result = Genre::with('movies')->orderBy('updated_at', 'desc');

        $count = $result->count();
        $items = $result
            ->take($rows)
//            ->skip($offset)
            ->get();

        return [
            'message' => 'success',
            'items' => $items,
            'total' => $count
        ];
    }

    public function getGenre(Request $request)
    {
        $genreId = $request->genre_id;
        return response()->json([
            'message' => 'success',
            'genre' => Genre::find($genreId)
        ]);
    }

    public function updateGenre(Request $request)
    {
        $genre_id = $request->genre_id;
        $genre = Genre::where('id', $genre_id)->update([
            'genre_name' => $request->genre_name
        ]);


        return response()->json([
            'message' => 'success',
            'genre_id' => $genre_id,
            'genre' => $genre
        ]);
    }

    public function deleteGenre(Request $request)
    {
        $genre_id = $request->genre_id;
        $genre = Genre::find($genre_id);
        $genre->delete();

        return response()->json([
            'message' => 'success',
            'genre_id' => $genre_id
        ]);
    }

    public function attachOrToggleMovie(Request $request)
    {
        $movie_id = $request->movie_id;
        $genre_id = $request->genre_id;

        $movie = Movie::find($movie_id);
        $genre = Genre::find($genre_id);

        if (isset($movie)) {
            $movie->getGenresRelatedToMovie()->toggle([$genre_id]);
        }

        return response()->json([
           'message' => 'success',
           'movies' => $genre->getRelatedMovies()->get()
        ]);
    }
}
