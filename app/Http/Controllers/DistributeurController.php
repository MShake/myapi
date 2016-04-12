<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

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

    /**
     * @SWG\Post(
     *     path="/distributeur",
     *     summary="Create a distributor",
     *     description="Use this method to create a distributor",
     *     operationId="createDitributor",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *      @SWG\Parameter(
     *         description="Nom du distributeur",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *      @SWG\Parameter(
     *         description="Téléphone du distributeur",
     *         in="formData",
     *         name="telephone",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Adresse",
     *         in="formData",
     *         name="adresse",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Code postal",
     *         in="formData",
     *         name="cpostal",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Ville",
     *         in="formData",
     *         name="ville",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Pays",
     *         in="formData",
     *         name="pays",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Distributor Created"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing field or incorrect syntax. Check the error message"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:distributeurs|max:255',
            'telephone' => 'numeric',
            'adresse' => 'max:255',
            'cpostal' => 'numeric',
            'ville' => 'max:255',
            'pays' => 'max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $distributeur = new Distributeur();
        $distributeur->nom = $request->nom;
        $distributeur->telephone = $request->telephone;
        $distributeur->adresse = $request->adresse;
        $distributeur->cpostal = $request->cpostal;
        $distributeur->ville = $request->ville;
        $distributeur->pays = $request->pays;
        $distributeur->save();

        return response()->json(
            $distributeur,
            201);
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
