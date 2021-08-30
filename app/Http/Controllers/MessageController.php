<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMessage(Request $request)
    {
        $requestedData = Message::find(1);
        $requestedData = (object)$request->json()->all();
        $message = Message::find(1);
        $message->message = $requestedData->message;
        $message->save();
        

        return response()->json(['success'=>1,'data'=> $message], 200,[],JSON_NUMERIC_CHECK);
    }
    // public function saveMessage(Request $request)
    // {
    //     $requestedData = (object)$request->json()->all();
    //     $inputs = $requestedData['messages'];
    //     $exist_message = new Message();

    //     DB::beginTransaction();

    //     try{
    //         if($exist_message){
    //             $message_id = $exist_message->id;
    //             $new_message = Message::find($message_id);
    //         }else{
    //             $new_message = new Message();
    //         }

    //         $new_message->message = $requestedData->message;
    //         $new_message->save();
    //         DB::commit();
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         return response()->json(['success'=>0,'exception'=>$e->getMessage()], 500);
    //     }


    //     return response()->json(['success'=>1,'data'=> $new_message], 200,[],JSON_NUMERIC_CHECK);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
