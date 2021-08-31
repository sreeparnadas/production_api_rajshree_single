<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NumberCombination;
use Illuminate\Http\Request;
use App\Models\NextGameDraw;
use App\Models\DrawMaster;
use App\Http\Controllers\PlayMasterController;
use App\Http\Controllers\NumberCombinationController;
use App\Models\GameType;
use Carbon\Carbon;

class CentralController extends Controller
{
    public function createResult(){

        $today= Carbon::today()->format('Y-m-d');
        $nextGameDrawObj = NextGameDraw::first();
        $nextDrawId = $nextGameDrawObj->next_draw_id;
        $lastDrawId = $nextGameDrawObj->last_draw_id;
        $playMasterControllerObj = new PlayMasterController();
        $totalSale = $playMasterControllerObj->get_total_balance();
        // echo $totalSale;
        $single = GameType::find(1);
        // $totalPrice = floor((($totalSale*(($single->payout))/100))/$single->winning_price);

        $payout = ($totalSale*($single->payout))/100;
        $winningValue = floor($payout/$single->winning_price);
        // $winningValue = floor(0.9154);
        echo $winningValue;
        exit;

        DrawMaster::query()->update(['active' => 0]);
        if(!empty($nextGameDrawObj)){
            DrawMaster::findOrFail($nextDrawId)->update(['active' => 1]);
        }


        $resultMasterController = new ResultMasterController();
        $jsonData = $resultMasterController->save_auto_result($lastDrawId);

        $resultCreatedObj = json_decode($jsonData->content(),true);

//        $actionId = 'score_update';
//        $actionData = array('team1_score' => 46);
//        event(new ActionEvent($actionId, $actionData));

        if( !empty($resultCreatedObj) && $resultCreatedObj['success']==1){

            $totalDraw = DrawMaster::count();
            if($nextDrawId==$totalDraw){
                $nextDrawId = 1;
            }
            else {
                $nextDrawId = $nextDrawId + 1;
            }

            if($lastDrawId==$totalDraw){
                $lastDrawId = 1;
            }
            else{
                $lastDrawId = $lastDrawId + 1;
            }

            $nextGameDrawObj->next_draw_id = $nextDrawId;
            $nextGameDrawObj->last_draw_id = $lastDrawId;
            $nextGameDrawObj->save();

            return response()->json(['success'=>1, 'message' => 'Result added'], 200);
        }else{
            return response()->json(['success'=>0, 'message' => 'Result not added'], 401);
        }

    }

}
