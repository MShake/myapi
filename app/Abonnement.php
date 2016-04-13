<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @SWG\Definition(
 *     required={"nom"},
 *     @SWG\Xml(name="Abonnement"),
 *     @SWG\Property(property="id_abonnement", format="int64", type="integer", default=42),
 *     @SWG\Property(property="id_forfait", format="int64", type="integer", default=1),
 *     @SWG\Property(property="debut", format="date", type="string", default="2016-02-17"),
 * )
 */
class Abonnement extends Model
{
    public $primaryKey = "id_abonnement";
    public $timestamps = false;

    public function forfait(){
        return $this->belongsTo('App\Forfait', 'id_forfait');
    }
}
