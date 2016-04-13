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

Route::group(['middleware' => 'jwt.auth'], function(){
    /*
     * entité film
     */
    Route::resource('film', 'FilmController');
    Route::get('film/genre/{id_genre}', "FilmController@getByIdGenre");
    Route::get('film/distributeur/{id_distributeur}', "FilmController@getByIdDistributeur");
});

/*
 * Entité Genre
 */
Route::resource('genre', 'GenreController');

/*
 * Entité Distributeur
 */
Route::resource('distributeur', 'DistributeurController');

/*
 * Entité Personne
 */
Route::resource('personne', 'PersonneController');

/*
 * Entité Seance
 */
Route::resource('seance', 'SeanceController');
Route::get('seance/film/{id_film}', "SeanceController@getByIdFilm");
Route::get('seance/salle/{id_salle}', "SeanceController@getByIdSalle");

/*
 * Entité Salle
 */
Route::resource('salle', 'SalleController');

/*
 * Entité Employe
 */
Route::resource('employe', 'EmployeController');


/*
 * JWT Auth
 */
Route::post('authenticate', [
    'as' => 'authenticate', 'uses' => 'JWTController@authenticate'
]);
Route::post('hashPassword', [
    'as' => 'hashPassword', 'uses' => 'JWTController@hashPassword'
]);
