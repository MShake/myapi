<?php

namespace App\Http\Controllers;

use App\HistoriqueMembre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class HistoriqueMembreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/historique",
     *     summary="Display a listing of historiques.",
     *     tags={"historique"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/HistoriqueMembre")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="No historiques"
     *     ),
     *  )
     */
    public function index()
    {
        $historiques = HistoriqueMembre::all();
        if ($historiques->isEmpty()) {
            return response()->json(
                ['error' => 'No historiques'],
                204);
        }
        return $historiques;
    }

    /**
     * @SWG\Post(
     *     path="/historique",
     *     summary="Create a historique",
     *     description="Use this method to create an historic",
     *     operationId="createHistorique",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"historique"},
     *      @SWG\Parameter(
     *         description="id du membre (id)",
     *         in="formData",
     *         name="id_membre",
     *         type="integer",
     *         required=true,
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Numero de la seance (id)",
     *         in="formData",
     *         name="id_seance",
     *         type="integer",
     *         required=true,
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date",
     *         in="formData",
     *         name="date",
     *         type="string",
     *         required=true,
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Historique created"
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
            'id_membre' => 'required|exists:membres,id_membre',
            'id_seance' => 'required|exists:seances,id',
            'date' => 'required|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $historique = new HistoriqueMembre;
        $historique->id_personne = $request->id_personne;
        $historique->id_fonction = $request->id_fonction;
        $historique->date = $request->date;

        $historique->save();

        return response()->json(
            $historique,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/historique/{id_historique}",
     *     summary="Find historique by ID",
     *     description="Returns a single historique",
     *     operationId="getHistoriqueById",
     *     tags={"historique"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of historique to return",
     *         in="path",
     *         name="id_historique",
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
     *         description="Historique not found"
     *     )
     * )
     */
    public function show($id)
    {
        $historiques = HistoriqueMembre::find($id);

        if(empty($historiques)){
            return response()->json(
                ['error' => 'this historique does not exist'],
                404);
        }
        return $historiques;
    }

    /**
     * @SWG\Delete(
     *     path="/historique/{id_historique}",
     *     summary="Delete an historic infos",
     *     description="Delete a historic infos through an ID",
     *     operationId="deleteHistorique",
     *     tags={"historique"},
     *     @SWG\Parameter(
     *         description="Historique ID to delete",
     *         in="path",
     *         name="id_historique",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Historique deleted"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid historique value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $historique = HistoriqueMembre::find($id);

        if (empty($historique)) {
            return response()->json(
                ['error' => 'this historique does not exist'],
                404);
        }

        $historique->delete();
        
        return response()->json(
            "Historique successfully deleted",
            200);
    }
}
