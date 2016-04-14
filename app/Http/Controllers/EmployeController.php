<?php

namespace App\Http\Controllers;

use App\Employe;
use App\Seance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     *     @SWG\Response(
     *         response=204,
     *         description="No employees"
     *     ),
     *  )
     */
    public function index()
    {
        $employes = Employe::all();
        if ($employes->isEmpty()) {
            return response()->json(
                ['error' => 'No employees'],
                204);
        }
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
     *         required=true,
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Fonction d'une personne (id)",
     *         in="formData",
     *         name="id_fonction",
     *         type="integer",
     *         required=true,
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
            'id_personne' => 'exists:personnes,id_personne|required|numeric',
            'id_fonction' => 'exists:fonctions,id_fonction|required|numeric',
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
     * @SWG\Get(
     *     path="/planningEmploye/{id_employe}",
     *     summary="Find planning by ID employe",
     *     description="Returns info of an employe and list of the seances",
     *     operationId="getPlanningByIdPersonne",
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
    public function getPlanningByIdPersonne($id){
        $employe[0] = Employe::with('Personne','Fonction')->find($id);


        if (empty($employe)) {
            return response()->json(
                ['error' => 'this employe does not exist'],
                404);
        }

        $seances = Seance::orwhere('id_personne_menage', $id)
                            ->orWhere('id_personne_ouvreur', $id)
                            ->orWhere('id_personne_technicien', $id)->get();

        $employe[1] = $seances;
        return $employe;

    }

    /**
     * @SWG\Get(
     *     path="/planningEmploye/{year}/{month}/{day}",
     *     summary="Find planning by date",
     *     description="Returns planning by date",
     *     operationId="getPlanningByDate",
     *     tags={"employe"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="Year of the seance",
     *         in="path",
     *         name="year",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Month of the seance",
     *         in="path",
     *         name="month",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Day of the seance",
     *         in="path",
     *         name="day",
     *         required=true,
     *         type="integer"
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

    public function getPlanningByDate($year,$month,$day){

        $seances = Seance::where(DB::raw('YEAR(debut_seance)'),'=',$year)
                            ->where(DB::raw('MONTH(debut_seance)'),'=',$month)
                            ->where(DB::raw('DAY(debut_seance)'),'=',$day)->get();
        foreach($seances as $key => $seance){
            $seance->personneOuvreur = Personne::find($seance->id_personne_ouvreur);
            $seance->personneTechnicien = Personne::find($seance->id_personne_technicien);
            $seance->personneMenage = Personne::find($seance->id_personne_menage);
        }

        return $seances;

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

            'id_personne' => 'exists:personnes,id_personne|numeric',
            'id_fonction' => 'exists:fonctions,id_fonction|numeric',

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
     *         description="Invalid employee value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $employe = Employe::find($id);

        if (empty($employe)) {
            return response()->json(
                ['error' => 'this employee does not exist'],
                404);
        }

        $employe->delete();
    }
}
