<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_film", "titre"},
 *     @SWG\Xml(name="Film"),
 *     @SWG\Property(property="id_film", format="int64", type="integer", default=42),
 *     @SWG\Property(property="id_genre", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_distributeur", format="int64", type="integer", default=1),
 *     @SWG\Property(property="titre", format="string", type="string", default="Deadpool"),
 *     @SWG\Property(property="resum", format="string", type="string", default="Deadpool, est l'anti-héros le plus atypique de l'univers Marvel. A l'origine, il s'appelle Wade Wilson : un ancien militaire des Forces Spéciales devenu mercenaire. Après avoir subi une expérimentation hors norme qui va accélérer ses pouvoirs de guérison, il va devenir Deadpool. Armé de ses nouvelles capacités et d'un humour noir survolté, Deadpool va traquer l'homme qui a bien failli anéantir sa vie."),
 *     @SWG\Property(property="date_debut_affiche", format="date", type="string", default="2016-02-10"),
 *     @SWG\Property(property="date_fin_affiche", format="date", type="string", default="2016-04-30"),
 *     @SWG\Property(property="duree_minutes", format="int", type="integer", default=108),
 *     @SWG\Property(property="annee_production", format="int", type="integer", default=2015)
 * )
 */

class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;
}
