<?php

namespace App\Http\Controllers;
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


Route::get('/search/repositories', [SearchRepository::class, 'search']);
Route::get('/search/commits', [SearchCommit::class, 'search']);
Route::get('/search/code', [SearchCode::class, 'search']);
Route::get('/search/issues', [SearchIssue::class, 'search']);
Route::get('/search/users', [SearchUser::class, 'search']);
Route::get('/search/topics', [SearchTopic::class, 'search']);
Route::get('/search/labels', [SearchLabel::class, 'search']);
