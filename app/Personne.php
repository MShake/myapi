<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_personne", "nom", "prenom", "date_naissance", "email", "cpostal", "ville", "pays"},
 *     @SWG\Xml(name="Personne"),
 *     @SWG\Property(property="id_personne", format="int64", type="integer", default=42),
 *     @SWG\Property(property="nom", format="string", type="string", default="Panini"),
 *     @SWG\Property(property="prenom", format="string", type="string", default="Flourian"),
 *     @SWG\Property(property="date_naissance", format="date", type="string", default="2016-06-15"),
 *     @SWG\Property(property="email", format="string", type="string", default="panini@esgi.fr"),
 *     @SWG\Property(property="adresse", format="string", type="string", default="242 rue du Faubourg Saint Antoine"),
 *     @SWG\Property(property="cpostal", format="int", type="integer", default=75012),
 *     @SWG\Property(property="ville", format="string", type="string", default="Paris"),
 *     @SWG\Property(property="pays", format="string", type="string", default="France")
 * )
 */

class Personne extends Model
{
    public $primaryKey = "id_personne";
    public $timestamps = false;

    public function personnesOuvreur(){
        return $this->hasMany('App\Seance', 'id_personne_ouvreur');
    }

    public function personnesTechnicien(){
        return $this->hasMany('App\Seance', 'id_personne_technicien');
    }

    public function personnesMenage(){
        return $this->hasMany('App\Seance', 'id_personne_menage');
    }
}
