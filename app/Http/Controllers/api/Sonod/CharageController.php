<?php

namespace App\Http\Controllers\api\Sonod;

use App\Http\Controllers\Controller;

use App\Models\Charage;
use Illuminate\Http\Request;

class CharageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->all();
        $district = $request->district;
        $thana = $request->thana;
        $CharageCount = Charage::where(['district'=>$district,'thana'=>$thana])->count();
        if($CharageCount>0){
           return  Charage::where(['district'=>$district,'thana'=>$thana])->first();
        }

        return 0;
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
        $id = $request->id;
        $data = $request->all();
        if($id){
            $charage = Charage::find($id);

            return $charage->update($data);
        }
        return Charage::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Charage  $charage
     * @return \Illuminate\Http\Response
     */
    public function show(Charage $charage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Charage  $charage
     * @return \Illuminate\Http\Response
     */
    public function edit(Charage $charage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Charage  $charage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Charage $charage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Charage  $charage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charage $charage)
    {
        //
    }
}
