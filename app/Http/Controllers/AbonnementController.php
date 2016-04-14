<?php

namespace App\Http\Controllers;

use App\Abonnement;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AbonnementController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/abonnement",
     *     summary="Display a listing of abonnement.",
     *     tags={"abonnement"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Abonnement")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="No abonnement"
     *     ),
     *  )
     */
    public function index()
    {
        $abonnements = Abonnement::all();
        if ($abonnements->isEmpty()) {
            return response()->json(
                ['error' => 'No abonnement'],
                204);
        }
        return $abonnements;
    }

    /**
     * @SWG\Post(
     *     path="/abonnement",
     *     summary="Create an abonnement",
     *     description="Use this method to create an abonnement.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="createAbonnement",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Identifiant du forfait lié",
     *         in="formData",
     *         name="id_forfait",
     *         required=true,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date de début de l'abonnement",
     *         in="formData",
     *         name="debut",
     *         required=true,
     *         type="string",
     *         format="datetime",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Abonnement created"
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

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

        $validator = Validator::make($request->all(), [
            'id_forfait' => 'required|exists:forfaits,id_forfait',
            'debut' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $abonnement = new Abonnement();
        $abonnement->id_forfait = $request->id_forfait;
        $abonnement->debut = $request->debut;
        $abonnement->save();

        return response()->json(
            $abonnement,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Find abonnement by ID",
     *     description="Returns a single abonnement",
     *     operationId="getAbonnementById",
     *     tags={"abonnement"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of abonnement to return",
     *         in="path",
     *         name="id_abonnement",
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
        $abonnement = Abonnement::find($id);

        if (empty($abonnement)) {
            return response()->json(
                ['error' => 'this abonnement does not exist'],
                404);
        }

        return $abonnement;
    }

    /**
     * @SWG\Put(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Update a abonnement",
     *     description="Use this method to update a abonnement.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="updateAbonnement",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="ID of the abonnement to update",
     *         in="path",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Identifiant du forfait lié",
     *         in="formData",
     *         name="id_forfait",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Date de début de l'abonnement",
     *         in="formData",
     *         name="debut",
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Abonnement updated"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Abonnement not Found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

        $abonnement = Abonnement::find($id);

        if (empty($abonnement)) {
            return response()->json(
                ['error' => 'this abonnement does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'id_forfait' => 'exists:forfaits,id_forfait',
            'debut' => 'date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $abonnement->id_forfait = $request->id_forfait != null ? $request->id_forfait : $abonnement->id_forfait;
        $abonnement->debut = $request->debut != null ? $request->debut : $abonnement->debut;

        $abonnement->save();

        return response()->json(
            $abonnement,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Delete a abonnement",
     *     description="Delete an abonnement through an ID.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="deleteAbonnement",
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Abonnement ID to delete",
     *         in="path",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Abonnement deleted"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid abonnement value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

        $abonnement = Abonnement::find($id);

        if (empty($abonnement)) {
            return response()->json(
                ['error' => 'this abonnement does not exist'],
                404);
        }

        $abonnement->delete();
        
        return response()->json(
            "Abonneement successfully deleted",
            200);
    }
}
