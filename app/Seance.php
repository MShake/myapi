<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_film", "id_salle", "id_personne_ouvreur", "id_personne_technicien", "id_personne_menage", "debut_seance", "fin_seance"},
 *     @SWG\Xml(name="Seance"),
 *     @SWG\Property(property="id", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_film", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_salle", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_personne_ouvreur", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_personne_technicien", format="int64", type="integer", default=2),
 *     @SWG\Property(property="id_personne_menage", format="int64", type="integer", default=3),
 *     @SWG\Property(property="debut_seance", format="datetime", type="string", default="2016-02-10"),
 *     @SWG\Property(property="fin_seance", format="datetime", type="string", default="2016-04-30"),
 * )
 */

class Seance extends Model
{
    public $primaryKey = "id";
    public $timestamps = false;
    
    public function film(){
        return $this->belongsTo('App\Film', 'id_film');
    }

    public function salle(){
        return $this->belongsTo('App\Salle', 'id_salle');
    }

    public function personneOuvreur(){
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function personneOTechnicien(){
        return $this->belongsTo('App\Personne', 'id_personne');
    }

    public function personneMenage(){
        return $this->belongsTo('App\Personne', 'id_personne');
    }
}
