<?php

namespace App\Http\Controllers;

use App\Abonnement;
use App\Forfait;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     *     description="Use this method to get stats.<br /><b>This can only be done if you're admin.</b>",
     *     tags={"stats"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *  )
     */
    public function getStats(){

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

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
                $stats["stats"]["forfaits"][$abo->forfait->nom]["pourcentage_ca"] = $stats["stats"]["forfaits"][$abo->forfait->nom]["total_revenu"] * 100 / $total_ca;
            }
        }


        return $stats;
    }

    /**
     * @SWG\Get(
     *     path="/stats/{debut}",
     *     summary="Display stats for abonnements which begin after specified date debut.",
     *     description="Use this method to get stats after a date.<br /><b>This can only be done if you're admin.</b>",
     *     tags={"stats"},
     *     @SWG\Parameter(
     *         description="Begin date to check stats",
     *         in="path",
     *         name="debut",
     *         required=true,
     *         type="string",
     *         format="date"
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Forbidden access. You need to be admin"
     *     ),
     *  )
     */
    public function getStatsWithDate($date_debut_abonnement){

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user->isAdmin != 1) {
            return response()->json(
                ['error' => 'Forbidden'],
                403);
        }

        $stats = array();
        $stats["stats"]["forfaits"] = array();
        $total_ca = 0;

        $abonnements = Abonnement::where("debut", ">", $date_debut_abonnement)->get();
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
                $stats["stats"]["forfaits"][$abo->forfait->nom]["pourcentage_ca"] = $stats["stats"]["forfaits"][$abo->forfait->nom]["total_revenu"] * 100 / $total_ca;
            }
        }


        return $stats;
    }
}
