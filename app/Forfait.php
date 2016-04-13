<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *     required={"nom"},
 *     @SWG\Xml(name="Forfait"),
 *     @SWG\Property(property="id_forfait", format="int64", type="integer", default=42),
 *     @SWG\Property(property="nom", format="string", type="string", default="premium"),
 *     @SWG\Property(property="resum", format="string", type="string", default="premium access"),
 *     @SWG\Property(property="prix", format="int64", type="integer", default=15),
 *     @SWG\Property(property="duree_jours", format="int64", type="integer", default=30),
 * )
 */
class Forfait extends Model
{
    public $primaryKey = "id_forfait";
    public $timestamps = false;
}
