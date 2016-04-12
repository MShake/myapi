<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(required={"id_film"}, @SWG\Xml(name="Film"))
 */


class Film extends Model
{
    public $primaryKey = "id_film";
    public $timestamps = false;

    /**
     * @SWG\Property()
     * @var int
     */
    public $id_film;

    /**
     * @SWG\Property()
     * @var string
     */
    public $titre;

    /**
     * @SWG\Property()
     * @var string
     */
    public $resum;

    /**
     * @SWG\Property()
     * @var date
     */
    public $date_debut_affiche;

    /**
     * @SWG\Property()
     * @var date
     */
    public $date_fin_affiche;

    /**
     * @SWG\Property()
     * @var int
     */
    public $duree_minutes;

    /**
     * @SWG\Property()
     * @var int
     */
    public $annee_production;
}
