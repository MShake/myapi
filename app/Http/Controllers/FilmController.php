<?php

namespace App\Http\Controllers;

use App\Distributeur;
use App\Film;
use App\Genre;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FilmController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/film",
     *     summary="Display a listing of films.",
     *     tags={"film"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
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
     * @SWG\Get(
     *     path="/film/genre/{id_genre}",
     *     summary="Display a listing of films by ID Genre",
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="ID of genre to get films",
     *         in="path",
     *         name="id_genre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="Successful operation but there isn't film with this genre",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Genre not found"
     *     )
     *  )
     */
    public function getByIdGenre($id)
    {
        $genre = Genre::find($id);

        if (empty($genre)) {
            return response()->json(
                ['error' => 'this genre does not exist'],
                404);
        }

        $films = Film::where('id_genre', $id)->get();

        if ($films->isEmpty()) {
            return response()->json("No content", 204);
        }

        return $films;
    }

    /**
     * @SWG\Get(
     *     path="/film/distributeur/{id_distributeur}",
     *     summary="Display a listing of films by ID Distributeur",
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="ID of distributeur to get films",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="Successful operation but there isn't film with this distributeur",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Distributeur not found"
     *     )
     *  )
     */
    public function getByIdDistributeur($id)
    {
        $distributeur = Distributeur::find($id);

        if (empty($distributeur)) {
            return response()->json(
                ['error' => 'this distributeur does not exist'],
                404);
        }

        $films = Film::where('id_distributeur', $id)->get();

        if ($films->isEmpty()) {
            return response()->json("No content", 204);
        }

        return $films;
    }

    /**
     * @SWG\Post(
     *     path="/film",
     *     summary="Create a film",
     *     description="Use this method to create a film.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="createFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *      @SWG\Parameter(
     *         description="Genre du Film (id)",
     *         in="formData",
     *         name="id_genre",
     *         type="integer",
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Distributeur du film (id)",
     *         in="formData",
     *         name="id_distributeur",
     *         type="integer",
     *         maximum="255"
     *     ),
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
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début affiche",
     *         in="formData",
     *         name="date_debut_affiche",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin affiche",
     *         in="formData",
     *         name="date_fin_affiche",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Durée en minutes",
     *         in="formData",
     *         name="duree_minutes",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Année de production",
     *         in="formData",
     *         name="annee_production",
     *         type="integer",
     *         maximum="4"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Film created"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
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
            'id_genre' => 'exists:genres,id_genre',
            'id_distributeur' => 'exists:distributeurs,id_distributeur',
            'titre' => 'required|unique:films|max:255',
            'resum' => 'max:255',
            'date_debut_affiche' => 'date|before:' . $request->date_fin_affiche,
            'date_fin_affiche' => 'date|after:' . $request->date_debut_affiche,
            'duree_minutes' => 'numeric',
            'annee_production' => 'digits:4'


        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }
        
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
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
     * @SWG\Get(
     *     path="/film/{id_film}",
     *     summary="Find film by ID",
     *     description="Returns a single film",
     *     operationId="getFilmById",
     *     tags={"film"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of film to return",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Film not found"
     *     )
     * )
     */
    public function show($id)
    {
        $film = Film::find($id);

        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        return $film;
    }

    /**
     * @SWG\Put(
     *     path="/film/{id_film}",
     *     summary="Update a film",
     *     description="Use this method to update a film",
     *     operationId="updateFilm",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="ID of film to update",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the film",
     *         in="formData",
     *         name="titre",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Resume of the film",
     *         in="formData",
     *         name="resum",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début affiche",
     *         in="formData",
     *         name="date_debut_affiche",
     *         required=false,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin affiche",
     *         in="formData",
     *         name="date_fin_affiche",
     *         required=false,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Durée en minutes",
     *         in="formData",
     *         name="duree_minutes",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Année de production",
     *         in="formData",
     *         name="annee_production",
     *         required=false,
     *         type="integer",
     *         maximum="4"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Film updated"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Film not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs incorrect"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $film = Film::find($id);

        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'unique:films|max:255',
            'resum' => 'max:255',
            'date_debut_affiche' => 'date|before:' . $request->date_fin_affiche,
            'date_fin_affiche' => 'date|after:' . $request->date_debut_affiche,
            'duree_minutes' => 'numeric',
            'annee_production' => 'digits:4'

        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $film->titre = $request->titre != null ? $request->titre : $film->titre;
        $film->resum = $request->resum != null ? $request->resum : $film->resum;
        $film->date_debut_affiche = $request->date_debut_affiche != null ? $request->date_debut_affiche : $film->date_debut_affiche;
        $film->date_fin_affiche = $request->date_fin_affiche != null ? $request->date_fin_affiche : $film->date_fin_affiche;
        $film->duree_minutes = $request->duree_minutes != null ? $request->duree_minutes : $film->duree_minutes;
        $film->annee_production = $request->annee_production != null ? $request->annee_production : $film->annee_production;

        $film->save();

        return response()->json(
            $film,
            200
        );
    }


    /**
     * @SWG\Delete(
     *     path="/film/{id_film}",
     *     summary="Delete a film",
     *     description="Delete a film through an ID",
     *     operationId="deleteFilm",
     *     tags={"film"},
     *     @SWG\Parameter(
     *         description="Film ID to delete",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Film deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid film value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $film = Film::find($id);

        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        $film->delete();
    }
}
