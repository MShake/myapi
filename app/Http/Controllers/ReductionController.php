<?php

namespace App\Http\Controllers;

use App\Reduction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReductionController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/reduction",
     *     summary="Display a listing of vouchers.",
     *     tags={"reduction"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Reduction")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $reductions = Reduction::all();
        return $reductions;
    }

    /**
     * @SWG\Post(
     *     path="/reduction",
     *     summary="Create a voucher",
     *     description="Use this method to create a voucher.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="createReduction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"reduction"},
     *      @SWG\Parameter(
     *         description="Name of the voucher",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         required=true,
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Date of the beginning of the voucher",
     *         in="formData",
     *         name="date_debut",
     *         type="string",
     *         format="datetime",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="Date of the end of the voucher",
     *         in="formData",
     *         name="date_fin",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Percentage of the voucher",
     *         in="formData",
     *         name="pourcentage_reduction",
     *         type="integer",
     *         required=true,
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Voucher created"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing fields or incorrect syntax"
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
            'nom' => 'required|max:255',
            'date_debut' => 'required|date_format:Y-m-d H:i:s|before:'.$request->date_fin,
            'date_fin' => 'required|date_format:Y-m-d H:i:s|after:'.$request->date_debut,
            'pourcentage_reduction' => 'required|numeric|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $reduction = new Reduction;
        $reduction->nom = $request->nom;
        $reduction->date_debut = $request->date_debut;
        $reduction->date_fin = $request->date_fin;
        $reduction->pourcentage_reduction = $request->pourcentage_reduction;
        $reduction->save();
        return response()->json(
            $reduction,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/reduction/{id_reduction}",
     *     summary="Find voucher by ID",
     *     description="Returns a single voucher",
     *     operationId="getReductionById",
     *     tags={"reduction"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of voucher to return",
     *         in="path",
     *         name="id_reduction",
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
     *         description="Voucher not found"
     *     )
     * )
     */
    public function show($id)
    {
        $reduction = Reduction::find($id);

        if (empty($reduction)) {
            return response()->json(
                ['error' => 'this voucher does not exist'],
                404);
        }

        return $reduction;
    }

    /**
     * @SWG\Put(
     *     path="/reduction/{id_reduction}",
     *     summary="Update a voucher",
     *     description="Use this method to update a voucher.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="updateReduction",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"reduction"},
     *     @SWG\Parameter(
     *         description="ID of voucher to update",
     *         in="path",
     *         name="id_reduction",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *      @SWG\Parameter(
     *         description="Name of the voucher",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         maximum="255"
     *     ),
     *      @SWG\Parameter(
     *         description="Date of the beginning of the voucher",
     *         in="formData",
     *         name="date_debut",
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Date of the end of the voucher",
     *         in="formData",
     *         name="date_fin",
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Percentage of the voucher",
     *         in="formData",
     *         name="pourcentage_reduction",
     *         type="integer",
     *         maximum="11"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Voucher updated"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Voucher not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing fields or incorrect syntax"
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

        $reduction = Reduction::find($id);

        if (empty($reduction)) {
            return response()->json(
                ['error' => 'this voucher does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'max:255',
            'date_debut' => 'date_format:Y-m-d H:i:s|before:'.$request->date_fin,
            'date_fin' => 'date_format:Y-m-d H:i:s|after:'.$request->date_debut,
            'pourcentage_reduction' => 'numeric|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $reduction->nom = $request->nom != null ? $request->nom : $reduction->nom;
        $reduction->date_debut = $request->date_debut != null ? $request->date_debut : $reduction->date_debut;
        $reduction->date_fin = $request->date_fin != null ? $request->date_fin : $reduction->date_fin;
        $reduction->pourcentage_reduction = $request->pourcentage_reduction != null ? $request->pourcentage_reduction : $reduction->pourcentage_reduction;

        $reduction->save();

        return response()->json(
            $reduction,
            200
        );
    }

    /**
     * @SWG\Delete(
     *     path="/reduction/{id_reduction}",
     *     summary="Delete a voucher",
     *     description="Delete a voucher through an ID.<br /><b>This can only be done if you're admin.</b>",
     *     operationId="deleteReduction",
     *     tags={"reduction"},
     *     @SWG\Parameter(
     *         description="Voucher ID to delete",
     *         in="path",
     *         name="id_reduction",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Voucher deleted"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid voucher value"
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

        $reduction = Reduction::find($id);

        if (empty($reduction)) {
            return response()->json(
                ['error' => 'this voucher does not exist'],
                404);
        }

        $reduction->delete();
    }
}
