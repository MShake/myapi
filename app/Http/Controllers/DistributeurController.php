<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;

class DistributeurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Get(
     *     path="/distributeur",
     *     summary="Display a listing of distributors.",
     *     tags={"distributeur"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $distributeurs = Distributeur::all();
        return $distributeurs;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Get(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Find distributor by ID",
     *     description="Returns a single distributor",
     *     operationId="getDistributorById",
     *     tags={"distributeur"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of distributor to return",
     *         in="path",
     *         name="id_distributeur",
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
     *         description="Distributor not found"
     *     )
     * )
     */
    public function show($id)
    {
        $distributeur = Distributeur::find($id);

        if (empty($distributeur)) {
            return response()->json(
                ['error' => 'this distributor does not exist'],
                404);
        }


        return $distributeur;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Delete(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Delete a distributor",
     *     description="Delete a distributor through an ID",
     *     operationId="deleteDistributeur",
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Distributeur ID to delete",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Distributor deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid distributor value"
     *     )
     *
     * )
     */

    public function destroy($id)
    {
        $distributeur = Distributeur::find($id);

        if (empty($distributeur)) {
            return response()->json(
                ['error' => 'this distributor does not exist'],
                404);
        }

        $distributeur->delete();
    }
}
