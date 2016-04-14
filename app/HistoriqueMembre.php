<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_historique", "id_membre", "id_seance", "date"},
 *     @SWG\Xml(name="Historique"),
 *     @SWG\Property(property="id_historique", format="int64", type="integer", default=1),
 *     @SWG\Property(property="id_membre", format="int64", type="integer", default=2),
 *     @SWG\Property(property="id_seance", format="int64", type="integer", default=3),
 *     @SWG\Property(property="date", format="date", type="string", default="2016-06-15"),
 * )
 */

class HistoriqueMembre extends Model
{
    public $primaryKey = "id_historique";
    public $table = "historique_membre";
    public $timestamps = false;

}
