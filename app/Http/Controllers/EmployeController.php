<?php

namespace App\Http\Controllers;

use App\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

class EmployeController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/employe",
     *     summary="Display a listing of employes.",
     *     tags={"employe"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Employe")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $employes = Employe::all();
        return $employes;
    }

    /**
     * @SWG\Post(
     *     path="/employe",
     *     summary="Create a employe",
     *     description="Use this method to create a employe",
     *     operationId="createEmploye",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *      @SWG\Parameter(
     *         description="id de la personne (id)",
     *         in="formData",
     *         name="id_personne",
     *         type="integer",
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Fonction d'une personne (id)",
     *         in="formData",
     *         name="id_fonction",
     *         type="integer",
     *         maximum="255"
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
            'id_personne' => 'exists:personnes,id_personne',
            'id_fonction' => 'exists:fonctions,id_fonction',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $employe = new Employe;
        $employe->id_personne = $request->id_personne;
        $employe->id_fonction = $request->id_fonction;
        $employe->save();

        return response()->json(
            $employe,
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
        $employe = Employe::find($id);
        return $employe;
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
        $employe = Employe::find($id);

        if (empty($employe)) {
            return response()->json(
                ['error' => 'this employe does not exist'],
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
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
