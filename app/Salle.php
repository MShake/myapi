<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function seances(){
        return $this->hasMany('App\Seance', 'id_salle');
    }

    public function isDisponible($date){
        //\DB::enableQueryLog();

        $salle = DB::table('salles')
            ->join('seances', 'salles.id_salle', '=', 'seances.id_salle')
            ->select('seances.*')
            ->where('salles.id_salle', "=", $this->id_salle)
            ->where(function($query) use ($date){
                $query->where('seances.fin_seance', '>', $date)
                    ->where('seances.debut_seance', '<', $date);
            })
            ->get();

        //print_r(\DB::getQueryLog());

        if(empty($salle)){
            return true;
        }

        return false;
    }

}
