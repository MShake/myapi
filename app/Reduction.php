<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_reduction", "nom", "date_debut", "date_fin", "pourcentage_reduction"},
 *     @SWG\Xml(name="Reduction"),
 *     @SWG\Property(property="id_reduction", format="int64", type="integer", default=3),
 *     @SWG\Property(property="nom", format="string", type="string", default="Réduction étudiant ESGI"),
 *     @SWG\Property(property="date_debut", format="date", type="string", default="2016-04-11 00:00:00"),
 *     @SWG\Property(property="date_fin", format="date", type="string", default="2016-06-15 23:59:59"),
 *     @SWG\Property(property="pourcentage_reduction", format="integer",default="40")
 * )
 */

class Reduction extends Model
{
    public $primaryKey = "id_reduction";
    public $timestamps = false;
}
