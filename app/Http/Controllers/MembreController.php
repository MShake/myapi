<?php

namespace App\Http\Controllers;

use App\Membre;
use Illuminate\Http\Request;

use App\Http\Requests;

class MembreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/membre",
     *     summary="Display a listing of membres.",
     *     tags={"membre"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Membre")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="No members"
     *     ),
     *  )
     */
    public function index()
    {
        $membres = Membre::all();
        if ($membres->isEmpty()) {
            return response()->json(
                ['error' => 'No members'],
                204);
        }
        return $membres;
    }

    /**
     * @SWG\Post(
     *     path="/membre",
     *     summary="Create a member",
     *     description="Use this method to create a member.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="createMembre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"membre"},
     *      @SWG\Parameter(
     *         description="ID personne",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="abonnement du member (id)",
     *         in="formData",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Date d'inscription",
     *         in="formData",
     *         name="date_inscription",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Début de l'abonnement",
     *         in="formData",
     *         name="debut_abonnement",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Member created"
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
            'id_personne' => 'required|exists:personnes,id_personne',
            'id_abonnement' => 'required|exists:abonnements,id_abonnement',
            'date_inscription' => 'required|date_format:Y-m-d H:i:s',
            'debut_abonnement' => 'required|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $membre = new Seance;
        $membre->id_personne = $request->id_personne;
        $membre->id_abonnement = $request->id_abonnement;
        $membre->date_inscription = $request->date_inscription;
        $membre->debut_abonnement = $request->debut_abonnement;
        $membre->save();

        return response()->json(
            $membre,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/membre/{id_membre}",
     *     summary="Find member by ID",
     *     description="Returns a single member",
     *     operationId="getMembreById",
     *     tags={"membre"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of member to return",
     *         in="path",
     *         name="id_membre",
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
     *         description="Member not found"
     *     )
     * )
     */
    public function show($id)
    {
        $membre = Membre::find($id);

        if (empty($membre)) {
            return response()->json(
                ['error' => 'this member does not exist'],
                404);
        }

        return $membre;
    }

    /**
     * @SWG\Put(
     *     path="/membre/{id_membre}",
     *     summary="Update a member",
     *     description="Use this method to update a member.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="updateMembre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="ID of member to update",
     *         in="path",
     *         name="id_membre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="ID personne",
     *         in="formData",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="abonnement du member (id)",
     *         in="formData",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Date d'inscription",
     *         in="formData",
     *         name="date_inscription",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Début de l'abonnement",
     *         in="formData",
     *         name="debut_abonnement",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Member created"
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
    public function update(Request $request, $id)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

        $membre = Membre::find($id);

        if (empty($membre)) {
            return response()->json(
                ['error' => 'this membre does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'id_personne' => 'exists:personnes,id_personne',
            'id_abonnement' => 'exists:abonnements,id_abonnement',
            'date_inscription' => 'date_format:Y-m-d H:i:s',
            'debut_abonnement' => 'date_format:Y-m-d H:i:s'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $membre->id_personne = $request->id_personne != null ? $request->id_personne : $membre->id_personne;
        $membre->id_abonnement = $request->id_abonnement != null ? $request->id_abonnement : $membre->id_abonnement;
        $membre->date_inscription = $request->date_inscription != null ? $request->date_inscription : $membre->date_inscription;
        $membre->debut_abonnement = $request->debut_abonnement != null ? $request->debut_abonnement : $membre->debut_abonnement;
        $membre->save();

        return response()->json(
            $membre,
            200
        );
    }

    /**
     * @SWG\Delete(
     *     path="/membre/{id_membre}",
     *     summary="Delete a member",
     *     description="Delete a member through an ID",
     *     operationId="deleteMembre",
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="Member ID to delete",
     *         in="path",
     *         name="id_membre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="member deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid member value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $membre = Membre::find($id);

        if (empty($membre)) {
            return response()->json(
                ['error' => 'this member does not exist'],
                404);
        }

        $membre->delete();
    }
}
