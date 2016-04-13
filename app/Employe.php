<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
