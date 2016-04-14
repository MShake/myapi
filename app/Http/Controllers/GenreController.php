<?php

namespace App\Http\Controllers;

use App\Film;
use App\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Http\Requests;

class GenreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/genre",
     *     summary="Display a listing of genres.",
     *     tags={"genre"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Genre")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="No genres"
     *     ),
     *  )
     */
    public function index()
    {
        $genres = Genre::all();
        if ($genres->isEmpty()) {
            return response()->json(
                ['error' => 'No genres'],
                204);
        }
        return $genres;
    }

    /**
     * @SWG\Post(
     *     path="/genre",
     *     summary="Genre a film",
     *     description="Use this method to create a genre",
     *     operationId="createGenre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"genre"},
     *      @SWG\Parameter(
     *         description="Nom du genre",
     *         in="formData",
     *         name="nom",
     *         type="string",
     *         required=true,
     *         maximum="255"),
     *     @SWG\Response(
     *         response=201,
     *         description="Genre created"
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
            'nom' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $genre = new Genre;
        $genre->nom = $request->nom;
        $genre->save();

        return response()->json(
            $genre,
            201);
    }

    /**
     * @SWG\Get(
     *     path="/genre/{id_genre}",
     *     summary="Find genre by ID",
     *     description="Returns a single genre",
     *     operationId="getGenreById",
     *     tags={"genre"},
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         description="ID of genre to return",
     *         in="path",
     *         name="id_genre",
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
     *         description="Genre not found"
     *     )
     * )
     */
    public function show($id)
    {
        $genre = Genre::find($id);

        if (empty($genre)) {
            return response()->json(
                ['error' => 'this genre does not exist'],
                404);
        }
        
        return $genre;
    }

    /**
     * @SWG\Put(
     *     path="/genre/{id_genre}",
     *     summary="Update a genre",
     *     description="Use this method to update a genre",
     *     operationId="updateGenre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"genre"},
     *     @SWG\Parameter(
     *         description="ID of genre to update",
     *         in="path",
     *         name="id_genre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Parameter(
     *         description="Name of the genre",
     *         in="formData",
     *         name="nom",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Genre updated"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Genre not found"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing field or incorrect syntax. Please check errors messages"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (empty($genre)) {
            return response()->json(
                ['error' => 'this genre does not exist'],
                404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'unique:distributeurs|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }


        $genre->nom = $request->nom != null ? $request->nom : $genre->nom;

        $genre->save();

        return response()->json(
            $genre,
            201
        );
    }

    /**
     * @SWG\Delete(
     *     path="/genre/{id_genre}",
     *     summary="Delete a genre",
     *     description="Delete a genre through an ID",
     *     operationId="deleteGenre",
     *     tags={"genre"},
     *     @SWG\Parameter(
     *         description="Genre ID to delete",
     *         in="path",
     *         name="id_genre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Genre deleted and all genre set to null in table film with this id"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid genre value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        if (empty($genre)) {
            return response()->json(
                ['error' => 'this genre does not exist'],
                404);
        }

        $film = Film::where('id_genre',$id)
                    ->update(['id_genre' => null]);;

        $genre->delete();
    }
}
