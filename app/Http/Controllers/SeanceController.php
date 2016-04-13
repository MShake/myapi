<?php

namespace App\Http\Controllers;

use App\Film;
use App\Salle;
use App\Seance;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class SeanceController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/seance",
     *     summary="Display a listing of seances.",
     *     tags={"seance"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $seances = Seance::all();

        return $seances;
    }

    /**
     * @SWG\Get(
     *     path="/seance/film/{id_film}",
     *     summary="Display a listing of seances by ID Film",
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="ID of film to get seances",
     *         in="path",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="Successful operation but there isn't seance with this film",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Film not found"
     *     )
     *  )
     */
    public function getByIdFilm($id)
    {
        $film = Film::find($id);

        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        $seances = Seance::where('id_film', $id)->get();

        if ($seances->isEmpty()) {
            return response()->json("No content", 204);
        }

        return $seances;
    }

    /**
     * @SWG\Get(
     *     path="/seance/salle/{id_salle}",
     *     summary="Display a listing of seances by ID Salle",
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="ID of salle to get seances",
     *         in="path",
     *         name="id_salle",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="Successful operation but there isn't seance with this salle",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Salle not found"
     *     )
     *  )
     */
    public function getByIdSalle($id)
    {
        $salle = Salle::find($id);

        if (empty($salle)) {
            return response()->json(
                ['error' => 'this salle does not exist'],
                404);
        }

        $seances = Seance::where('id_salle', $id)->get();

        if ($seances->isEmpty()) {
            return response()->json("No content", 204);
        }

        return $seances;
    }

    /**
     * @SWG\Post(
     *     path="/seance",
     *     summary="Create a seance",
     *     description="Use this method to create a seance",
     *     operationId="createSeance",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"seance"},
     *      @SWG\Parameter(
     *         description="Film de la seance (id)",
     *         in="formData",
     *         name="id_film",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="Salle de la seance (id)",
     *         in="formData",
     *         name="id_salle",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Ouvreur de la seance (id)",
     *         in="formData",
     *         name="id_personne_ouvreur",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Technicien de la seance (id)",
     *         in="formData",
     *         name="id_personne_technicien",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Menage de la seance (id)",
     *         in="formData",
     *         name="id_personne_menage",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début seance",
     *         in="formData",
     *         name="debut_seance",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin seance",
     *         in="formData",
     *         name="fin_seance",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Seance created"
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
            'id_film' => 'required|exists:films,id_film',
            'id_salle' => 'required|exists:salles,id_salle',
            'id_personne_ouvreur' => 'required|exists:personnes,id_personne',
            'id_personne_technicien' => 'required|exists:personnes,id_personne',
            'id_personne_menage' => 'required|exists:personnes,id_personne',
            'debut_seance' => 'required|date|before:' . $request->fin_seance,
            'fin_seance' => 'required|date|after:' . $request->debut_seance
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $seance = new Seance;
        $seance->id_film = $request->id_film;
        $seance->id_salle = $request->id_salle;
        $seance->id_personne_ouvreur = $request->id_personne_ouvreur;
        $seance->id_personne_technicien = $request->id_personne_technicien;
        $seance->id_personne_menage = $request->id_personne_menage;
        $seance->debut_seance = $request->debut_seance;
        $seance->fin_seance = $request->fin_seance;
        $seance->save();

        return response()->json(
            $seance,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/seance/{id}",
     *     summary="Find seance by ID",
     *     description="Returns a single seance",
     *     operationId="getSeanceById",
     *     tags={"seance"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of seance to return",
     *         in="path",
     *         name="id",
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
     *         description="Seance not found"
     *     )
     * )
     */
    public function show($id)
    {
        $seance = Seance::find($id);

        if (empty($seance)) {
            return response()->json(
                ['error' => 'this seance does not exist'],
                404);
        }

        return $seance;
    }

    /**
     * @SWG\Put(
     *     path="/seance/{id}",
     *     summary="Update a seance",
     *     description="Use this method to update a seance",
     *     operationId="updateSeance",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="ID of seance to update",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="Film de la seance (id)",
     *         in="formData",
     *         name="id_film",
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="Salle de la seance (id)",
     *         in="formData",
     *         name="id_salle",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Ouvreur de la seance (id)",
     *         in="formData",
     *         name="id_personne_ouvreur",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Technicien de la seance (id)",
     *         in="formData",
     *         name="id_personne_technicien",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Personne Menage de la seance (id)",
     *         in="formData",
     *         name="id_personne_menage",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Date début seance",
     *         in="formData",
     *         name="debut_seance",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Date fin seance",
     *         in="formData",
     *         name="fin_seance",
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Seance updated"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Seance not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $seance = Seance::find($id);

        if (empty($seance)) {
            return response()->json(
                ['error' => 'this seance does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'id_film' => 'exists:films,id_film',
            'id_salle' => 'exists:salles,id_salle',
            'id_personne_ouvreur' => 'exists:personnes,id_personne',
            'id_personne_technicien' => 'exists:personnes,id_personne',
            'id_personne_menage' => 'exists:personnes,id_personne',
            'debut_seance' => 'date|before:' . $request->fin_seance,
            'fin_seance' => 'date|after:' . $request->debut_seance
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $seance->id_film = $request->id_film != null ? $request->id_film : $seance->id_film;
        $seance->id_salle = $request->id_salle != null ? $request->id_salle : $seance->id_salle;
        $seance->id_personne_ouvreur = $request->id_personne_ouvreur != null ? $request->id_personne_ouvreur : $seance->id_personne_ouvreur;
        $seance->id_personne_technicien = $request->id_personne_technicien != null ? $request->id_personne_technicien : $seance->id_personne_technicien;
        $seance->id_personne_menage = $request->id_personne_menage != null ? $request->id_personne_menage : $seance->id_personne_menage;
        $seance->debut_seance = $request->debut_seance != null ? $request->debut_seance : $seance->debut_seance;
        $seance->fin_seance = $request->fin_seance != null ? $request->fin_seance : $seance->fin_seance;
        $seance->save();

        return response()->json(
            $seance,
            200
        );
    }

    /**
     * @SWG\Delete(
     *     path="/seance/{id}",
     *     summary="Delete a seance",
     *     description="Delete a seance through an ID",
     *     operationId="deleteSeance",
     *     tags={"seance"},
     *     @SWG\Parameter(
     *         description="Seance ID to delete",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Seance deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid seance value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $seance = Seance::find($id);

        if (empty($seance)) {
            return response()->json(
                ['error' => 'this seance does not exist'],
                404);
        }

        $seance->delete();
    }
}
