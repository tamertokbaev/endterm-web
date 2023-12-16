<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class PlaylistController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $image = $request->preview_image;
        $fileName = $image->hashName();
        $path = 'http://localhost:8000/storage/playlists/'.$fileName;
        $image->store('playlists', 'public');
        $playlist = Playlist::create([
            'playlist_name' => $request->playlist_name,
            'description' => $request->description,
            'user_id' => $user->id,
            'preview_url' => $path
        ]);

        return response()->json([
            'message' => 'success',
            'playlist' => $playlist
        ]);
    }

    public function toggleMovie(Request $request)
    {
        $movie_id = $request->movie_id;
        $playlist_id = $request->playlist_id;

        $playlist = Playlist::find($playlist_id)
            ->getRelatedMovies()
            ->toggle([$movie_id]);

        return response()->json([
            'message' => 'success',
            'playlist' => $playlist
        ]);
    }

    public function getPlaylistMovies(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $user = $request->user();

        if ($user->subscription == 0) {

        } else {

        }

        $movies = Playlist::find($playlist_id)
            ->getRelatedMovies()
            ->get();

        return response()->json([
            'message' => 'success',
            'movies' => $movies
        ]);
    }

    public function getSubscribedAuthors(Request $request)
    {

        return response()->json([
            'message' => 'success',

        ]);
    }

    public function update(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $user = $request->user();
        $playlist = Playlist::where('id', $playlist_id)
            ->update([
                    'playlist_name' => $request->playlist_name,
                    'description' => $request->description,
                    'user_id' => $user->id
                ]
            );

        return response()->json([
            'message' => 'success',
            'playlist' => $playlist
        ]);
    }

    public function delete(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $playlist = Playlist::find('id', $playlist_id);
        $playlist->delete();

        return response()->json([
           'message' => 'success',
           'playlist_id' => $playlist_id
        ]);
    }

    public function getUserPlaylists(Request $request)
    {
        $user = $request->user();
        $playlists = $user->getPlaylists()->get();

        return response()->json([
            'message' => 'success',
            'playlists' => $playlists
        ]);
    }

    public function checkIfPlaylistIsMine(Request $request)
    {
        $user = $request->user();
        $playlist_id = $request->playlist_id;

        $playlist = Playlist::find($playlist_id);

        return response()->json([
           'message' => 'success',
            'result' => $playlist->user_id == $user->id
        ]);
    }

    public function getListOfFeaturedPlaylists()
    {

        return response()->json([
            'message' => 'success',
            'playlists' => Playlist::with('getRelatedMovies')
                ->orderByDesc('subscribers')
                ->get()
        ]);
    }

    public function getPersonalizedPlaylists()
    {
        $popularIds = DB::table('playlist_movies')
            ->select('playlist_id')
            ->from('playlist_movies')
            ->groupBy('playlist_id')
            ->orderByRaw('count(*) desc')
            ->take(15)
            ->get();
        $popularMergedIds = [];
        foreach ($popularIds as $id) {
            $popularMergedIds[] = $id->playlist_id;
        }
        $playlists = Playlist::whereIn('id', $popularMergedIds)->get();
        return response()->json([
           'message' => 'success',
            'result' => $playlists
        ]);
    }

    public function getMyPlaylists(Request $request)
    {
        $user = $request->user();

        return response()->json([
           'message' => 'success',
            'playlists' => $user->getPlaylists()->get()
        ]);
    }

    public function togglePlaylistSubscription(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $user = $request->user();
        Playlist::find($playlist_id)
            ->subscribers()->toggle([$user->id]);;

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getPlaylistDetail(Request $request)
    {
        $playlist_id = $request->playlist_id;
        $playlist = Playlist::where('id', $playlist_id)
            ->with('getRelatedMovies')
            ->with('author')
            ->with('subscribers')
            ->first();

        $count = DB::table('playlist_users')
            ->selectRaw('count(user_id) as subscribersCount')
            ->where('playlist_id', $playlist_id)
            ->first()
            ->subscribersCount;

        return response()->json([
            'message' => 'success',
            'playlist' => $playlist,
            'subscribers' => $count
        ]);
    }
}
