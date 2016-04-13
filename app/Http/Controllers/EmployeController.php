<?php

namespace App\Http\Controllers;

use App\Employe;
use Illuminate\Http\Request;

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
     *  )
     */
    public function index()
    {
        $employes = Employe::all();
        return $employes;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_personne' => 'exists:personnes,id_personne',
            'id_fonction' => 'exists:fonctions,id_fonction',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employe = Employe::find($id);
        return $employe;
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
    public function destroy($id)
    {
        //
    }
}
