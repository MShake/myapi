<?php

namespace App\Http\Controllers;

use App\Salle;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class SalleController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/salle",
     *     summary="Display a listing of salles.",
     *     tags={"salle"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Salle")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $salles = Salle::all();
        return $salles;
    }

    /**
     * @SWG\Post(
     *     path="/salle",
     *     summary="Create a salle",
     *     description="Use this method to create a salle",
     *     operationId="createSalle",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Numero de la salle",
     *         in="formData",
     *         name="numero_salle",
     *         required=true,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Nom de la salle",
     *         in="formData",
     *         name="nom_salle",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Etage de la salle",
     *         in="formData",
     *         name="etage_salle",
     *         required=true,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Nombre de places de la salle",
     *         in="formData",
     *         name="places",
     *         required=true,
     *         type="integer",
     *         format="string"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Salle created"
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
            'numero_salle' => 'required|unique:salles',
            'nom_salle' => 'required|unique:salles',
            'etage_salle' => 'required|max:255',
            'places' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $salle = new Salle;
        $salle->numero_salle = $request->numero_salle;
        $salle->nom_salle = $request->nom_salle;
        $salle->etage_salle = $request->etage_salle;
        $salle->places = $request->places;
        $salle->save();

        return response()->json(
            $salle,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/salle/{id_salle}",
     *     summary="Find salle by ID",
     *     description="Returns a single salle",
     *     operationId="getSalleById",
     *     tags={"salle"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of salle to return",
     *         in="path",
     *         name="id_salle",
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
     *         description="Salle not found"
     *     )
     * )
     */
    public function show($id)
    {
        $salle = Salle::find($id);

        if (empty($salle)) {
            return response()->json(
                ['error' => 'this salle does not exist'],
                404);
        }

        return $salle;
    }

    /**
     * @SWG\Put(
     *     path="/salle/{id_salle}",
     *     summary="Update a salle",
     *     description="Use this method to update a salle",
     *     operationId="updateSalle",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="ID of the salle to update",
     *         in="path",
     *         name="id_salle",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Numero de la salle",
     *         in="formData",
     *         name="numero_salle",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Nom de la salle",
     *         in="formData",
     *         name="nom_salle",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Etage de la salle",
     *         in="formData",
     *         name="etage_salle",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Nombre de places de la salle",
     *         in="formData",
     *         name="places",
     *         type="integer",
     *         format="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Salle updated"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Salle not Found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $salle = Salle::find($id);

        if (empty($salle)) {
            return response()->json(
                ['error' => 'this salle does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'numero_salle' => 'unique:salles',
            'nom_salle' => 'unique:salles',
            'etage_salle' => 'max:255',
            'places' => ''
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $salle->numero_salle = $request->numero_salle != null ? $request->numero_salle : $salle->numero_salle;
        $salle->nom_salle = $request->nom_salle != null ? $request->nom_salle : $salle->nom_salle;
        $salle->etage_salle = $request->etage_salle != null ? $request->etage_salle : $salle->etage_salle;
        $salle->places = $request->places != null ? $request->places : $salle->places;
        $salle->save();

        return response()->json(
            $salle,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/salle/{id_salle}",
     *     summary="Delete a salle",
     *     description="Delete a salle through an ID",
     *     operationId="deleteSalle",
     *     tags={"salle"},
     *     @SWG\Parameter(
     *         description="Salle ID to delete",
     *         in="path",
     *         name="id_salle",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Salle deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid salle value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $salle = Salle::find($id);

        if (empty($salle)) {
            return response()->json(
                ['error' => 'this salle does not exist'],
                404);
        }

        $salle->delete();
        
        return response()->json(
            "Salle successfully deleted",
            200);
    }
}
