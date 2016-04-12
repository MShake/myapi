<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
 * Entité Film
 */
Route::resource('film', 'FilmController');
Route::get('film/getByIdGenre/{id_genre}', "FilmController@getByIdGenre");
Route::get('film/getByIdDistributeur/{id_distributeur}', "FilmController@getByIdDistributeur");

/*
 * Entité Genre
 */
Route::resource('genre', 'GenreController');

/*
 * Entité Distributeur
 */
Route::resource('distributeur', 'DistributeurController');


