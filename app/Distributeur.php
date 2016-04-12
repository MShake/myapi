<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *     required={"id_distributeur", "nom", "telephone"},
 *     @SWG\Xml(name="Distributeur"),
 *     @SWG\Property(property="id_distributeur", format="int64", type="integer", default=11),
 *     @SWG\Property(property="nom", format="string", type="string", default="advanced"),
 *     @SWG\Property(property="telephone", format="string", type="string", default="0123456789"),
 *     @SWG\Property(property="adresse", format="string", type="string", default="242 rue du faubourg st antoine"),
 *     @SWG\Property(property="cpostal", format="string", type="string", default="75012"),
 *     @SWG\Property(property="ville", format="string", type="string", default="Paris"),
 *     @SWG\Property(property="pays", format="string", type="string", default="France"),
 * )
 */

class Distributeur extends Model
{
    public $primaryKey = "id_distributeur";
    public $timestamps = false;
}
