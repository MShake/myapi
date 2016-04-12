<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(required={"id_film"}, @SWG\Xml(name="Film"))
 */


class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;
}
