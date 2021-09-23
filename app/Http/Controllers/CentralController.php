<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NumberCombination;
use App\Models\PlayMaster;
use Illuminate\Http\Request;
use App\Models\NextGameDraw;
use App\Models\DrawMaster;
use App\Http\Controllers\PlayMasterController;
use App\Http\Controllers\NumberCombinationController;
use App\Models\GameType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CentralController extends Controller
{
    public function createResult(){

        $today= Carbon::today()->format('Y-m-d');
        $nextGameDrawObj = NextGameDraw::first();
        $nextDrawId = $nextGameDrawObj->next_draw_id;
        $lastDrawId = $nextGameDrawObj->last_draw_id;
        $playMasterControllerObj = new PlayMasterController();

        $playMasterObj = new TerminalReportController();
        $playMasterObj->updateCancellation();

        $totalSale = $playMasterControllerObj->get_total_sale($today,$lastDrawId);
        $single = GameType::find(1);

        $payout = ($totalSale*($single->payout))/100;
        $targetValue = floor($payout/$single->winning_price);

        // result less than equal to target value
        $result = DB::select(DB::raw("select single_numbers.id as single_number_id,single_numbers.single_number,sum(play_details.quantity) as total_quantity  from play_details
        inner join play_masters ON play_masters.id = play_details.play_master_id
        inner join single_numbers ON single_numbers.id = play_details.single_number_id
        where play_masters.draw_master_id = $lastDrawId  and date(play_details.created_at)= "."'".$today."'"."
        group by single_numbers.single_number,single_numbers.id
        having sum(play_details.quantity)<= $targetValue
        order by rand() limit 1"));

        // select empty item for result
        if(empty($result)){
            // empty value
            $result = DB::select(DB::raw("SELECT single_numbers.id as single_number_id FROM single_numbers WHERE id NOT IN(SELECT DISTINCT
        play_details.single_number_id FROM play_details
        INNER JOIN play_masters on play_details.play_master_id= play_masters.id
        WHERE  DATE(play_masters.created_at) = "."'".$today."'"." and play_masters.draw_master_id = $lastDrawId) ORDER by rand() LIMIT 1"));
        }

        // result greater than equal to target value

        if(empty($result)){
            $result = DB::select(DB::raw("select single_numbers.id as single_number_id,single_numbers.single_number,sum(play_details.quantity) as total_quantity  from play_details
            inner join play_masters ON play_masters.id = play_details.play_master_id
            inner join single_numbers ON single_numbers.id = play_details.single_number_id
            where play_masters.draw_master_id= $lastDrawId  and date(play_details.created_at)= "."'".$today."'"."
            group by single_numbers.single_number,single_numbers.id
            having sum(play_details.quantity)> $targetValue
            order by rand() limit 1"));
        }

        $single_number_result_id = $result[0]->single_number_id;

        DrawMaster::query()->update(['active' => 0]);
        if(!empty($nextGameDrawObj)){
            DrawMaster::findOrFail($nextDrawId)->update(['active' => 1]);
        }


        $resultMasterController = new ResultMasterController();
        $jsonData = $resultMasterController->save_auto_result($lastDrawId,$single_number_result_id);

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



    public function createResultByDate(){

        $today= '2021-09-02';
        $nextGameDrawObj = NextGameDraw::first();
        $nextDrawId = 7;
        $lastDrawId = 6;
        $playMasterControllerObj = new PlayMasterController();

        $totalSale = $playMasterControllerObj->get_total_sale($today,$lastDrawId);
        $single = GameType::find(1);

        $payout = ($totalSale*($single->payout))/100;
        $targetValue = floor($payout/$single->winning_price);
        echo $targetValue;

        // result less than equal to target value
        $result = DB::select(DB::raw("select single_numbers.id as single_number_id,single_numbers.single_number,sum(play_details.quantity) as total_quantity  from play_details
        inner join play_masters ON play_masters.id = play_details.play_master_id
        inner join single_numbers ON single_numbers.id = play_details.single_number_id
        where play_masters.draw_master_id = $lastDrawId  and date(play_details.created_at)= "."'".$today."'"."
        group by single_numbers.single_number,single_numbers.id
        having sum(play_details.quantity)<= $targetValue
        order by rand() limit 1"));

        echo 'Check1';
        print_r($result);
        if(empty($result)){
            // empty value
            $result = DB::select(DB::raw("SELECT single_numbers.id as single_number_id FROM single_numbers WHERE id NOT IN(SELECT DISTINCT
        play_details.single_number_id FROM play_details
        INNER JOIN play_masters on play_details.play_master_id= play_masters.id
        WHERE  DATE(play_masters.created_at) = "."'".$today."'"." and play_masters.draw_master_id = $lastDrawId) ORDER by rand() LIMIT 1"));
        }
        echo 'Check2';
        print_r($result);

        if(empty($result)){
            $result = DB::select(DB::raw("select single_numbers.id as single_number_id,single_numbers.single_number,sum(play_details.quantity) as total_quantity  from play_details
            inner join play_masters ON play_masters.id = play_details.play_master_id
            inner join single_numbers ON single_numbers.id = play_details.single_number_id
            where play_masters.draw_master_id= $lastDrawId  and date(play_details.created_at)= "."'".$today."'"."
            group by single_numbers.single_number,single_numbers.id
            having sum(play_details.quantity)>= $targetValue
            order by rand() limit 1"));
        }

        echo 'Check3';
        print_r($result);

        $single_number_result_id = $result[0]->single_number_id;


        $resultMasterController = new ResultMasterController();
        $jsonData = $resultMasterController->save_auto_result($lastDrawId,$single_number_result_id);

        $resultCreatedObj = json_decode($jsonData->content(),true);

//        $actionId = 'score_update';
//        $actionData = array('team1_score' => 46);
//        event(new ActionEvent($actionId, $actionData));

        if( !empty($resultCreatedObj) && $resultCreatedObj['success']==1){

            return response()->json(['success'=>1, 'message' => 'Result added'], 200);
        }else{
            return response()->json(['success'=>0, 'message' => 'Result not added'], 401);
        }

    }

}
