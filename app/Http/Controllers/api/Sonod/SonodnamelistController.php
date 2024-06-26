<?php

namespace App\Http\Controllers\api\Sonod;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sonod;
use App\Models\SonodFee;
use Illuminate\Http\Request;
use App\Models\Sonodnamelist;
use Illuminate\Support\Facades\Auth;

class SonodnamelistController extends Controller
{
    public function updatesonodname(Request $request)
    {
        $id = $request->id;
        $data = $request->except(['icon']);
        $iconCount =  count(explode(';',$request->icon));
        if($iconCount>1){
            // return $request->icon;
             $data['icon'] =  fileupload($request->icon,"assets/icon/");
        }

        if ($id) {
            $sonodNameList = Sonodnamelist::find($id);
            return $sonodNameList->update($data);
        } else {
            return Sonodnamelist::create($data);
        }
    }


    public function updatesonodnameFee(Request $request)
    {
        $sonodlist =  $request->sonodfee;
        $unionname =  $request->unionname;
        foreach ($sonodlist as $key=>$value) {
            // return $key;
           $sonodName = Sonodnamelist::where('service_id',$key)->first();

           $filter = [
                'unioun'=>$unionname,
                'service_id'=>$key,
           ];
           $sonodFeeCount = SonodFee::where($filter)->count();
           if($sonodFeeCount>0){
            $sonodFee = SonodFee::where($filter)->first();
            $sonodFee->update(['fees'=>$value]);

           }else{
            SonodFee::create(['unioun'=>$unionname,'service_id'=>$key,'fees'=>$value]);
           }

        }
    }

    public function feeList(Request $request)
    {
        $unioun = $request->unioun;
        $sondFee = SonodFee::where(['unioun'=>$unioun])->get();
        $sonodfee = [];
        foreach ($sondFee as $value) {
            $sonodfee[$value->service_id] = $value->fees;
        }
        return $sonodfee;
    }




    public function getsonodname(Request $request, $id)
    {
        $data =  Sonodnamelist::find($id);
        $data['icon'] =  asset($data->icon);
        return $data;
    }
    public function deletesonodname(Request $request, $id)
    {
        $sonodnamelist =  Sonodnamelist::find($id);
        $sonodnamelist->delete();
        return 'Sonod Name deleted!';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fees = $request->fees;
        $unioun = $request->unioun;

        $data = $request->data;
        $admin = $request->admin;
        if ($admin) {
            return Sonodnamelist::all();
            // return Sonodnamelist::with('sonods')->get();
        }
        if ($data) {
            if($fees){

                $sonodname =  Sonodnamelist::where('enname', $data)->first();
                 $sonodFee =  SonodFee::where(['service_id'=> $sonodname->service_id,'unioun'=>$unioun])->first();
                $data = [
                    'sonodname'=>$sonodname,
                    'sonodFee'=>$sonodFee,

                ];
                return $data;
            }

            return Sonodnamelist::where('enname', $data)->first();
        }
        return Sonodnamelist::all();
    }
    public function sonodCount(Request $request)
    {
        $userid = $request->userid;
        $Upazila = '';
        if($userid){
            $user = User::find($userid);
           $Upazila =  $user->thana;
        }


        $union = $request->union;
        $position = $request->postion;
        // return $request->all();
        $sonodCount = [];
        $sonodnamelist = Sonodnamelist::all();
        foreach ($sonodnamelist as $value) {
            $penddingStatus = 'Pending';
            $Secretary_approvedstatus = 'Secretary_approved';
            $approvedstatus = 'approved';
            if ($position == 'Secretary') {
                $penddingStatus = 'Pending';
                $Secretary_approvedstatus = 'Secretary_approved';
                $approvedstatus = 'approved';
            } else
            if ($position == 'Chairman') {
                $penddingStatus = 'Secretary_approved';
                $Secretary_approvedstatus = 'Secretary_approved';
                $approvedstatus = 'approved';
            }
            // return $penddingStatus;
            if ($union == '') {

                if($userid){

                     $sonodCount['Pending'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $penddingStatus, 'applicant_present_Upazila' => $Upazila])->count();
                    $sonodCount['Secretary_approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $Secretary_approvedstatus, 'applicant_present_Upazila' => $Upazila])->count();
                    if ($Secretary_approvedstatus == 'Secretary_approved') {
                        $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $approvedstatus, 'payment_status' => 'Unpaid', 'applicant_present_Upazila' => $Upazila])->count();
                    } else {
                        $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $approvedstatus, 'applicant_present_Upazila' => $Upazila])->count();
                    }

                }else{
                    $sonodCount['Pending'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $penddingStatus])->count();
                    $sonodCount['Secretary_approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $Secretary_approvedstatus])->count();
                    if ($Secretary_approvedstatus == 'Secretary_approved') {
                        $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $approvedstatus, 'payment_status' => 'Unpaid'])->count();
                    } else {
                        $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['sonod_name' => $value->bnname, 'stutus' => $approvedstatus])->count();
                    }
                }




            } else {
                $sonodCount['Pending'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['unioun_name' => $union, 'sonod_name' => $value->bnname, 'stutus' => $penddingStatus])->count();
                $sonodCount['Secretary_approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['unioun_name' => $union, 'sonod_name' => $value->bnname, 'stutus' => $Secretary_approvedstatus])->count();
                if ($Secretary_approvedstatus == 'Secretary_approved') {
                    $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['unioun_name' => $union, 'sonod_name' => $value->bnname, 'stutus' => $approvedstatus, 'payment_status' => 'Unpaid'])->count();
                } else {
                    $sonodCount['approved'][str_replace(" ", "_", $value->enname)] =  Sonod::where(['unioun_name' => $union, 'sonod_name' => $value->bnname, 'stutus' => $approvedstatus])->count();
                }
            }
            // print_r($value->bnname);
        }
        // return $Secretary_approvedstatus;
        return $sonodCount;
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
     * @param  \App\Models\Sonodnamelist  $sonodnamelist
     * @return \Illuminate\Http\Response
     */
    public function show(Sonodnamelist $sonodnamelist)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sonodnamelist  $sonodnamelist
     * @return \Illuminate\Http\Response
     */
    public function edit(Sonodnamelist $sonodnamelist)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sonodnamelist  $sonodnamelist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sonodnamelist $sonodnamelist)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sonodnamelist  $sonodnamelist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sonodnamelist $sonodnamelist)
    {
        //
    }
}
