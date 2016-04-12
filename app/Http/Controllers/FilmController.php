<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/film",
     *     summary="Display a listing of films.",
     *     tags={"film"},
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *     ),
     *  )
     */

    public function index()
    {
        $films = Film::all();
        return $films;
    }

    /**
     * @SWG\Post(
     *     path="/film",
     *     summary="Create a film",
     *     description="Use this method to create a film",
     *     operationId="createFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Name of the film",
     *         in="formData",
     *         name="titre",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the film",
     *         in="formData",
     *         name="resum",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début affiche",
     *         in="formData",
     *         name="date_debut_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin affiche",
     *         in="formData",
     *         name="date_fin_affiche",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Durée en minutes",
     *         in="formData",
     *         name="duree_minutes",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Année de production",
     *         in="formData",
     *         name="annee_production",
     *         required=true,
     *         type="integer",
     *         maximum="4"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Film created"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|unique:films|max:255',
            'resum' => 'required|max:255',
            'date_debut_affiche' => 'required|date|before:'.$request->date_fin_affiche,
            'date_fin_affiche' => 'required|date|after:'.$request->date_debut_affiche,
            'duree_minutes' => 'required|numeric',
            'annee_production' => 'required|digits:4'

        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $film = new Film;
        $film->titre = $request->titre;
        $film->resum = $request->resum;
        $film->date_debut_affiche = $request->date_debut_affiche;
        $film->date_fin_affiche = $request->date_fin_affiche;
        $film->duree_minutes = $request->duree_minutes;
        $film->annee_production = $request->annee_production;
        $film->save();

        return response()->json(
            $film,
            201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = Film::find($id);

        if(empty($film)){
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }


        return $film;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $film = Film::find($id);
        $film->titre = $request->titre;
        $film->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $film = Film::find($id);
        $film->delete();
    }
}
