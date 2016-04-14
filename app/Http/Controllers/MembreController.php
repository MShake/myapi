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
     *  )
     */
    public function index()
    {
        $membres = Membre::all();

        return $membres;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @SWG\Get(
     *     path="/membre/{id_membre}",
     *     summary="Find membre by ID",
     *     description="Returns a single membre",
     *     operationId="getMembreById",
     *     tags={"membre"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of membre to return",
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
     *         description="Membre not found"
     *     )
     * )
     */
    public function show($id)
    {
        $membre = Membre::find($id);

        if (empty($membre)) {
            return response()->json(
                ['error' => 'this membre does not exist'],
                404);
        }

        return $membre;
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
        //
    }

    /**
     * @SWG\Delete(
     *     path="/membre/{id_membre}",
     *     summary="Delete a membre",
     *     description="Delete a membre through an ID",
     *     operationId="deleteMembre",
     *     tags={"membre"},
     *     @SWG\Parameter(
     *         description="Membre ID to delete",
     *         in="path",
     *         name="id_membre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Membre deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid membre value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $membre = Membre::find($id);

        if (empty($membre)) {
            return response()->json(
                ['error' => 'this membre does not exist'],
                404);
        }

        $membre->delete();
    }
}
