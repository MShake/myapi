<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_genre", "nom"},
 *     @SWG\Xml(name="Genre"),
 *     @SWG\Property(property="id_genre", format="int64", type="integer", default=5),
 *     @SWG\Property(property="nom", format="string", type="string", default="Fantastique"),
 * )
 */

class Genre extends Model
{
    public $primaryKey = "id_genre";
    public $timestamps = false;

    public function films(){
        return $this->hasMany('App\Film', 'id_genre');
    }
}
