<?php

namespace App\Http\Controllers;

use App\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Personne;

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
     *         description="Employe created"
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
     * @SWG\Get(
     *     path="/employe/{id_employe}",
     *     summary="Find employe by ID",
     *     description="Returns a single employe",
     *     operationId="getEmployeById",
     *     tags={"employe"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of employe to return",
     *         in="path",
     *         name="id_employe",
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
     *         description="Employe not found"
     *     )
     * )
     */
    public function show($id)
    {
        $employe = Employe::with('Personne')->find($id);
        return $employe;
    }


    /**
     * @SWG\Put(
     *     path="/employe/{id_employe}",
     *     summary="Update a employe",
     *     description="Use this method to update a employe",
     *     operationId="updateEmploye",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="ID of employe to return",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
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
     *         response=200,
     *         description=" Employe updated"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
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

            'id_personne' => 'exists:personnes,id_personne',
            'id_fonction' => 'exists:fonctions,id_fonction',

        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $employe->id_personne = $request->id_personne != null ? $request->id_personne : $employe->id_personne;
        $employe->id_fonction = $request->id_fonction != null ? $request->id_fonction : $employe->id_fonction;
        $employe->save();

        return response()->json(
            $employe,
            200);

    }

    /**
     * @SWG\Delete(
     *     path="/employe/{id_employe}",
     *     summary="Delete a employe",
     *     description="Delete a employe through an ID",
     *     operationId="deleteEmploye",
     *     tags={"employe"},
     *     @SWG\Parameter(
     *         description="Employe ID to delete",
     *         in="path",
     *         name="id_employe",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Employe deleted"
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
        $employe = Employe::find($id);

        if (empty($employe)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404);
        }

        $employe->delete();
    }
}
