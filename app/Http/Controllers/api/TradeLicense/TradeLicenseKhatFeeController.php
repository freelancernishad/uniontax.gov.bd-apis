<?php

namespace App\Http\Controllers\api\TradeLicense;

use App\Http\Controllers\Controller;

use App\Models\TradeLicenseKhatFee;
use Illuminate\Http\Request;

class TradeLicenseKhatFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $khat_id_1 = $request->khat_id_1;
        $khat_id_2 = $request->khat_id_2;
        $dataget = $request->dataget;
        if($dataget=='single'){
            return TradeLicenseKhatFee::where(['khat_id_1'=>$khat_id_1])->first();
        }else{
            return TradeLicenseKhatFee::where(['khat_id_1'=>$khat_id_1,'khat_id_2'=>$khat_id_2])->first();
        }

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
     * @param  \App\Models\TradeLicenseKhatFee  $tradeLicenseKhatFee
     * @return \Illuminate\Http\Response
     */
    public function show(TradeLicenseKhatFee $tradeLicenseKhatFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TradeLicenseKhatFee  $tradeLicenseKhatFee
     * @return \Illuminate\Http\Response
     */
    public function edit(TradeLicenseKhatFee $tradeLicenseKhatFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TradeLicenseKhatFee  $tradeLicenseKhatFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TradeLicenseKhatFee $tradeLicenseKhatFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TradeLicenseKhatFee  $tradeLicenseKhatFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(TradeLicenseKhatFee $tradeLicenseKhatFee)
    {
        //
    }
}
