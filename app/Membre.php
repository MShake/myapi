<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_personne", "id_abonnement", "date_inscription", "debut_abonnement"},
 *     @SWG\Xml(name="Membre"),
 *     @SWG\Property(property="id_membre", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_personne", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_abonnement", format="int64", type="integer", default=1),
 *     @SWG\Property(property="date_inscription", format="datetime", type="string", default="2016-02-10"),
 *     @SWG\Property(property="debut_abonnement", format="datetime", type="string", default="2016-04-30"),
 * )
 */

class Membre extends Model
{
    public $primaryKey = "id_membre";
    public $timestamps = false;

    public function personne(){
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function abonnement(){
        return $this->belongsTo('App\Abonnement', 'id_abonnement');
    }
}
