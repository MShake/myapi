<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{

    public $primaryKey = "id_fonction";


    public function employes(){
        return $this->hasMany('App\Employe', 'id_employe');
    }
}
