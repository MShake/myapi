<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_film", "titre", "resum", "date_debut_affiche", "date_fin_affiche", "duree_minutes", "annee_production"},
 *     @SWG\Xml(name="Film"),
 *     @SWG\Property(property="id_film", format="int64", type="integer"),
 *     @SWG\Property(property="titre", format="string", type="string"),
 *     @SWG\Property(property="resum", format="string", type="string"),
 *     @SWG\Property(property="date_debut_affiche", format="date", type="string"),
 *     @SWG\Property(property="date_fin_affiche", format="date", type="string"),
 *     @SWG\Property(property="duree_minutes", format="int", type="integer"),
 *     @SWG\Property(property="annee_production", format="int", type="integer")
 * )
 */

class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;
}
