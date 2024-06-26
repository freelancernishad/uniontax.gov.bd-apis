<?php

namespace App\Http\Controllers\api\Sonod;

use App\Http\Controllers\Controller;

use App\Models\ActionLog;
use Illuminate\Http\Request;

class ActionLogController extends Controller
{

    public function rejectreason(Request $request,$id)
    {
      return ActionLog::where(['sonod_id'=>$id,'status'=>'cancel'])->orderBy('id','DESC')->first();
    }

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
     * @param  \App\Models\ActionLog  $actionLog
     * @return \Illuminate\Http\Response
     */
    public function show(ActionLog $actionLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ActionLog  $actionLog
     * @return \Illuminate\Http\Response
     */
    public function edit(ActionLog $actionLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActionLog  $actionLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActionLog $actionLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActionLog  $actionLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActionLog $actionLog)
    {
        //
    }
}
