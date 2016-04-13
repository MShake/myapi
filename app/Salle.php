<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_salle", "nom_salle"},
 *     @SWG\Xml(name="Salle"),
 *     @SWG\Property(property="id_salle", format="int64", type="integer", default=62),
 *     @SWG\Property(property="numero_salle", format="int64", type="integer", default=1),
 *     @SWG\Property(property="nom_salle", format="string", type="string", default="Kirk Hammett"),
 *     @SWG\Property(property="etage_salle", format="int64", type="integer", default=0),
 *     @SWG\Property(property="places", format="int64", type="integer", default=130),
 * )
 */

class Salle extends Model
{
    public $primaryKey = "id_salle";
    public $timestamps = false;
}
