<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PlaylistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/auth/status', [AuthController::class, 'status']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::put('/auth/change-personal-data', [AuthController::class, 'changeUserData']);

Route::post('/user/subscribe', [UserController::class, 'subscribe']);
Route::get('user/followers', [UserController::class, 'getUserFollowers']);
Route::get('user/subscriptions', [UserController::class, 'getUserSubscriptions']);
Route::get('user/family', [UserController::class, 'getMailsForFamilySubscriptions']);
Route::post('user/family_add', [UserController::class, 'addNewUserIntoFamilySubscription']);

Route::post('playlists', [PlaylistController::class, 'store']);
Route::post('playlists/toggle-movie', [PlaylistController::class, 'toggleMovie']);
Route::get('playlists/get-movies', [PlaylistController::class, 'getPlaylistMovies']);
Route::get('playlists/get-user-playlists', [PlaylistController::class, 'getUserPlaylists']);
Route::put('playlists', [PlaylistController::class, 'update']);
Route::delete('playlists', [PlaylistController::class, 'delete']);
Route::get('playlists', [PlaylistController::class, 'getUserPlaylists']);
Route::get('playlists/featured', [PlaylistController::class, 'getListOfFeaturedPlaylists']);
Route::get('playlists/item', [PlaylistController::class, 'getPlaylistDetail']);
Route::post('playlists/subscribe', [PlaylistController::class, 'togglePlaylistSubscription']);
Route::get('playlists/check_mine', [PlaylistController::class, 'checkIfPlaylistIsMine']);
Route::get('playlists/get_personalized_playlists', [PlaylistController::class, 'getPersonalizedPlaylists']);
Route::get('playlists/get_added_playlists', [PlaylistController::class, 'getMyPlaylists']);

Route::get('/movie/featured', [MovieController::class, 'getFeaturedMovies']);
Route::get('/movie/popular', [MovieController::class, 'getPopularMovies']);
Route::get('/movie/last-released', [MovieController::class, 'getLastReleasedMovies']);
Route::get('/movie/info', [MovieController::class, 'getMovieInfo']);
Route::post('/movie/favorites', [MovieController::class, 'addOrRemoveToFavoriteMovie']);
Route::get('/movie/favorites', [MovieController::class, 'getFavoriteMovies']);
Route::get('/movie/search', [MovieController::class, 'search']);
Route::get('/movie/similar', [MovieController::class, 'similar']);
Route::get('/movie/genres', [MovieController::class, 'getGenres']);
Route::get('/movie/by_genre', [MovieController::class, 'getMoviesByGenre']);

Route::post('/admin/movie/create', [\App\Http\Controllers\Api\AdminController::class, 'createMovie']);
Route::get('/admin/movie/list', [\App\Http\Controllers\Api\AdminController::class, 'getListOfMovies']);
Route::get('/admin/movie', [\App\Http\Controllers\Api\AdminController::class, 'getMovie']);
Route::put('/admin/movie/update', [\App\Http\Controllers\Api\AdminController::class, 'updateMovie']);
Route::delete('/admin/movie/delete', [\App\Http\Controllers\Api\AdminController::class, 'deleteMovie']);
Route::get('/admin/movie/all', [\App\Http\Controllers\Api\AdminController::class, 'getAllMovies']);


Route::post('/admin/genre/create', [\App\Http\Controllers\Api\AdminController::class, 'createGenre']);
Route::get('/admin/genre/list', [\App\Http\Controllers\Api\AdminController::class, 'getListOfGenres']);
Route::get('/admin/genre', [\App\Http\Controllers\Api\AdminController::class, 'getGenre']);
Route::put('/admin/genre/update', [\App\Http\Controllers\Api\AdminController::class, 'updateGenre']);
Route::delete('/admin/genre/delete', [\App\Http\Controllers\Api\AdminController::class, 'deleteGenre']);
Route::post('/admin/genre/toggle', [\App\Http\Controllers\Api\AdminController::class, 'attachOrToggleMovie']);
