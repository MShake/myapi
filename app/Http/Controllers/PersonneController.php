<?php

namespace App\Http\Controllers;

use App\Personne;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class PersonneController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/personne",
     *     summary="Display a listing of personnes.",
     *     tags={"personne"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $personnes = Personne::all();
        return $personnes;
    }

    /**
     * @SWG\Post(
     *     path="/personne",
     *     summary="Create a personne",
     *     description="Use this method to create a personne",
     *     operationId="createPersonne",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"personne"},
     *      @SWG\Parameter(
     *         description="Lastname",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *      @SWG\Parameter(
     *         description="Firstname",
     *         in="formData",
     *         name="prenom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Birthdate",
     *         in="formData",
     *         name="date_naissance",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Parameter(
     *         description="E-mail",
     *         in="formData",
     *         name="email",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="Address",
     *         in="formData",
     *         name="adresse",
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="ZIP Code",
     *         in="formData",
     *         name="cpostal",
     *         type="integer",
     *         maximum="255",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="City",
     *         in="formData",
     *         name="ville",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="Country",
     *         in="formData",
     *         name="pays",
     *         type="string",
     *         maximum="255",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Personne Created"
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
            'nom' => 'required|max:255',
            'prenom' => 'required|max:255',
            'date_naissance' => 'required|date',
            'email' => 'required|max:255',
            'adresse' => 'max:255',
            'cpostal' => 'numeric|max:255|required',
            'ville' => 'max:255|required',
            'pays' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $personne = new Personne();
        $personne->nom = $request->nom;
        $personne->prenom = $request->prenom;
        $personne->date_naissance = $request->date_naissance;
        $personne->email = $request->email;
        $personne->adresse = $request->adresse;
        $personne->cpostal = $request->cpostal;
        $personne->ville = $request->ville;
        $personne->pays = $request->pays;
        $personne->save();

        return response()->json(
            $personne,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/personne/{id_personne}",
     *     summary="Find personne by ID",
     *     description="Returns a single personne",
     *     operationId="getPersonneById",
     *     tags={"personne"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of personne to return",
     *         in="path",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Personne not found"
     *     )
     * )
     */
    public function show($id)
    {
        $personne = Personne::find($id);

        if (empty($personne)) {
            return response()->json(
                ['error' => 'this personne does not exist'],
                404);
        }


        return $personne;
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
     *     path="/personne/{id_personne}",
     *     summary="Delete a personne",
     *     description="Delete a personne through an ID",
     *     operationId="deletePersonne",
     *     tags={"personne"},
     *     @SWG\Parameter(
     *         description="Personne ID to delete",
     *         in="path",
     *         name="id_personne",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Personne deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid personne value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $personne = Personne::find($id);

        if (empty($personne)) {
            return response()->json(
                ['error' => 'this personne does not exist'],
                404);
        }

        $personne->delete();
    }
}
