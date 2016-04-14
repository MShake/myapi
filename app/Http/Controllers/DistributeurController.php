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
     *     @SWG\Response(
     *         response=204,
     *         description="No distributor"
     *     ),
     *  )
     */
    public function index()
    {
        $distributeurs = Distributeur::all();
        if ($distributeurs->isEmpty()) {
            return response()->json(
                ['error' => 'No distributors'],
                204);
        }
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
     *         description="Name of the distributor",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *      @SWG\Parameter(
     *         description="Phone of the distributor",
     *         in="formData",
     *         name="telephone",
     *         required=true,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Address of the distributor",
     *         in="formData",
     *         name="adresse",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="ZIP of the distributor",
     *         in="formData",
     *         name="cpostal",
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="City of the distributor",
     *         in="formData",
     *         name="ville",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Country of the distributor",
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
            'telephone' => 'numeric|required',
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

    /**
     * @SWG\Put(
     *     path="/distributeur/{id_distributeur}",
     *     summary="Update a distributor",
     *     description="Use this method to update a distributor",
     *     operationId="updateDistributor",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="ID of distributor to update",
     *         in="path",
     *         name="id_distributeur",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the distributor",
     *         in="formData",
     *         name="nom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Phone of the ditributor",
     *         in="formData",
     *         name="telephone",
     *         required=false,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Adress of the distributor",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="Postal code of the distributor",
     *         in="formData",
     *         name="cpostal",
     *         required=false,
     *         type="integer",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="City of the distributor",
     *         in="formData",
     *         name="ville",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Country of the distributor",
     *         in="formData",
     *         name="pays",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Distributor updated"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Distributor not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing field or incorrect syntax. Please check errors messages"
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $distributeur = Distributeur::find($id);

        if (empty($distributeur)) {
            return response()->json(
                ['error' => 'this distributor does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'unique:distributeurs|max:255',
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


        $distributeur->nom = $request->nom != null ? $request->nom : $distributeur->nom;
        $distributeur->telephone = $request->telephone != null ? $request->telephone : $distributeur->telephone;
        $distributeur->adresse = $request->adresse != null ? $request->adresse : $distributeur->adresse;
        $distributeur->cpostal = $request->cpostal != null ? $request->cpostal : $distributeur->cpostal;
        $distributeur->ville = $request->ville != null ? $request->ville : $distributeur->ville;
        $distributeur->pays = $request->pays != null ? $request->pays : $distributeur->pays;

        $distributeur->save();

        return response()->json(
            $distributeur,
            201
        );
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
        
        return response()->json(
            "Distributor successfully deleted",
            200);
    }
}
