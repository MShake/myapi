<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


/**
 * @SWG\Definition(
 *     required={"id_employe", "titre"},
 *     @SWG\Xml(name="Employe"),
 *     @SWG\Property(property="id_employe", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_personne", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_fonction", format="int64", type="integer", default=1),
 * )
 */

class Employe extends Model
{
    public $primaryKey = "id_employe";
    public $timestamps = false;

    public function fonction(){
        return $this->belongsTo('App\Fonction', 'id_fonction');
    }

    public function personne(){
        return $this->belongsTo('App\Personne', 'id_personne');
    }
    
    
}
