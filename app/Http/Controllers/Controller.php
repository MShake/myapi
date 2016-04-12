<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;


/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="myapi.com",
 *     basePath="/",
 *     @SWG\Info(
 *          version="1.0.0",
 *          title="Ma première API",
 *          description="Une API permettant l'accès aux données de la base via une approche RESTful",
 *          termsOfService="",
 *          @SWG\Contact(
 *              name="Maxime Grimler",
 *              email="kalimerre@gmail.com"
 *          ),
 *     ),
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
}
