<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;

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
     *  )
     */
    public function index()
    {
        $genre = Genre::all();
        return $genre;
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
     *         description="Genre deleted"
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
                ['error' => 'this distributor does not exist'],
                404);
        }

        $genre->delete();
    }
}
