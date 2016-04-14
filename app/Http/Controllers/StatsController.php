<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Forfait;
use Illuminate\Http\Request;

use App\Http\Requests;

class StatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @SWG\Get(
     *     path="/stats",
     *     summary="Display stats for all abonnements.",
     *     tags={"stats"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *     ),
     *  )
     */
    public function getStats(){
        $stats = array();
        $stats["stats"]["forfaits"] = array();
        $total_ca = 0;

        $abonnements = Abonnement::all();
        foreach($abonnements as $keyAbo => $abo){
            $abo->forfait();
            $total_ca += $abo->forfait->prix;
        }

        // Total CA
        $stats["stats"]["abonnements"]["total_ca"] = $total_ca;

        // Total ventes abonnements
        $stats["stats"]["abonnements"]["total_ventes"] = sizeof($abonnements);

        //Stats sur le total des ventes et des revenus
        foreach ($abonnements as $keyAbo => $abo){

            if($abo->id_forfait == $abo->forfait->id_forfait){
                if(!array_key_exists($abo->forfait->nom, $stats["stats"]["forfaits"])){
                    $stats["stats"]["forfaits"][$abo->forfait->nom]["total_ventes"] = 1;
                    $stats["stats"]["forfaits"][$abo->forfait->nom]["total_revenu"] = $abo->forfait->prix;
                }
                else{
                    $stats["stats"]["forfaits"][$abo->forfait->nom]["total_ventes"] += 1;
                    $stats["stats"]["forfaits"][$abo->forfait->nom]["total_revenu"] += $abo->forfait->prix;
                }
            }

        }

        //Pourcentage des ventes de forfaits
        foreach ($abonnements as $keyAbo => $abo){
            if($abo->id_forfait == $abo->forfait->id_forfait){
                    $stats["stats"]["forfaits"][$abo->forfait->nom]["pourcentage_ventes"] = $stats["stats"]["forfaits"][$abo->forfait->nom]["total_ventes"] * 100 / $stats["stats"]["abonnements"]["total_ventes"];
            }
        }

        //Pourcentage du CA par forfait
        foreach ($abonnements as $keyAbo => $abo){
            if($abo->id_forfait == $abo->forfait->id_forfait){
                $stats["stats"]["forfaits"][$abo->forfait->nom]["pourcentage_revenu"] = $stats["stats"]["forfaits"][$abo->forfait->nom]["total_revenu"] * 100 / $total_ca;
            }
        }


        return $stats;
    }
}
