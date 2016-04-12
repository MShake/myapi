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
     *     produces={"application/xml", "application/json"},
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
