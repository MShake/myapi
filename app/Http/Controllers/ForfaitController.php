<?php

namespace App\Http\Controllers;

use App\Forfait;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ForfaitController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/forfait",
     *     summary="Display a listing of forfaits.",
     *     tags={"forfait"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Forfait")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $forfaits = Forfait::all();
        return $forfaits;
    }

    /**
     * @SWG\Post(
     *     path="/forfait",
     *     summary="Create a forfait",
     *     description="Use this method to create a forfait",
     *     operationId="createForfait",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"forfait"},
     *      @SWG\Parameter(
     *         description="Name of the forfait",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Description of the forfait",
     *         in="formData",
     *         name="resum",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Price of the forfait",
     *         in="formData",
     *         name="prix",
     *         required=true,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Day duration of the forfait",
     *         in="formData",
     *         name="duree_jours",
     *         type="integer",
     *         required=true,
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Forfait created"
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
            'nom' => 'required|unique:forfaits',
            'resum' => 'required',
            'prix' => 'required',
            'duree_jours' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $forfait = new Forfait();
        $forfait->nom = $request->nom;
        $forfait->resum = $request->resum;
        $forfait->prix = $request->prix;
        $forfait->duree_jours = $request->duree_jours;

        $forfait->save();

        return response()->json(
            $forfait,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/forfait/{id_forfait}",
     *     summary="Find forfait by ID",
     *     description="Returns a single forfait",
     *     operationId="getForfaitById",
     *     tags={"forfait"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of film to return",
     *         in="path",
     *         name="id_forfait",
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
     *         description="Forfait not found"
     *     )
     * )
     */
    public function show($id)
    {
        $forfait = Forfait::find($id);

        if (empty($forfait)) {
            return response()->json(
                ['error' => 'this forfait does not exist'],
                404);
        }

        return $forfait;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @SWG\Put(
     *     path="/forfait/{id_forfait}",
     *     summary="Update a forfait",
     *     description="Use this method to update a forfait",
     *     operationId="updateForfait",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"forfait"},
     *     @SWG\Parameter(
     *         description="ID of forfait to update",
     *         in="path",
     *         name="id_forfait",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="Name of the forfait",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Description of the forfait",
     *         in="formData",
     *         name="resum",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Price of the forfait",
     *         in="formData",
     *         name="prix",
     *         required=false,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Day duration of the forfait",
     *         in="formData",
     *         name="duree_jours",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Forfait updated"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $forfait = Forfait::find($id);

        if (empty($forfait)) {
            return response()->json(
                ['error' => 'this forfait does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'unique:forfaits',
            'resum' => '',
            'prix' => '',
            'duree_jours' => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $forfait->nom = $request->nom != null ? $request->nom : $forfait->nom;
        $forfait->resum = $request->resum != null ? $request->resum : $forfait->resum;
        $forfait->prix = $request->prix != null ? $request->prix : $forfait->prix;
        $forfait->duree_jours = $request->duree_jours != null ? $request->duree_jours : $forfait->duree_jours;

        $forfait->save();

        return response()->json(
            $forfait,
            201);
    }

    /**
     * @SWG\Delete(
     *     path="/forfait/{id_forfait}",
     *     summary="Delete a forfait",
     *     description="Delete a forfait through an ID",
     *     operationId="deleteForfait",
     *     tags={"forfait"},
     *     @SWG\Parameter(
     *         description="Forfait ID to delete",
     *         in="path",
     *         name="id_forfait",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Forfait deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid forfait value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $forfait = Forfait::find($id);

        if (empty($forfait)) {
            return response()->json(
                ['error' => 'this forfait does not exist'],
                404);
        }

        $forfait->delete();
    }
}
