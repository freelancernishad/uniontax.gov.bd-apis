<?php

namespace App\Http\Controllers\api\Payment;

use App\Http\Controllers\Controller;

use App\Models\Sonod;
use App\Models\Payment;
use App\Models\Holdingtax;
use App\Models\Uniouninfo;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Models\HoldingBokeya;
use App\Models\TanderInvoice;
use App\Models\Tender;
use App\Models\TenderFormBuy;
use App\Models\TenderList;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
// use Meneses\LaravelMpdf\Facades\LaravelMpdf;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class PaymentController extends Controller
{



    public function ipn(Request $request)
    {
        $data = $request->all();
        Log::info(json_encode($data));
        $sonod = Sonod::find($data['cust_info']['cust_id']);
        $trnx_id = $data['trnx_info']['mer_trnx_id'];
        $payment = payment::where('trxid', $trnx_id)->first();

        $Insertdata = [];
        if ($data['msg_code'] == '1020') {
            $Insertdata = [
                'status' => 'Paid',
                'method' => $data['pi_det_info']['pi_name'],
            ];



            if($payment->sonod_type=='holdingtax'){
                $hosdingBokeya = HoldingBokeya::find($payment->sonodId);
                // $hosdingtax= Holdingtax::find($hosdingBokeya->holdingTax_id);
                $hosdingBokeya->update(['status'=>'Paid','payYear'=>date('Y'),'payOB'=>COB(1)]);
            }elseif($payment->sonod_type=='Tenders_form'){



                $TenderFormBuy = Tender::find($payment->sonodId);
                $TenderFormBuy->update(['payment_status'=>'Paid']);


                $tenderList = TenderList::find($TenderFormBuy->tender_id);
                $unioun_name = $tenderList->union_name;
                $deccription = "Your Tender Successfuly submited";
                SmsNocSmsSend($deccription, $TenderFormBuy->mobile,$unioun_name);



            }elseif($payment->sonod_type=='tender-deposit'){



                $TenderFormBuy = TanderInvoice::find($payment->sonodId);
                $TenderFormBuy->update(['status'=>'Paid']);


                // $tenderList = TenderList::find($TenderFormBuy->tender_id);
                // $unioun_name = $tenderList->union_name;

                $deccription = "Your Tender Successfuly submited";
                // SmsNocSmsSend($deccription, $TenderFormBuy->mobile,$unioun_name);



            }else{
                $sonod->update(['khat' => 'সনদ ফি','stutus' => 'Pending', 'payment_status' => 'Paid']);
            }



        } else {
            $sonod->update(['khat' => 'সনদ ফি','stutus' => 'failed', 'payment_status' => 'Failed']);
            $Insertdata = ['status' => 'Failed',];
        }
        $Insertdata['ipnResponse'] = json_encode($data);
        // return $Insertdata;
        return $payment->update($Insertdata);
    }


    public function ReCallIpn(Request $request)
    {

        $trnx_id = $request->trnx_id;
        $myserver = Payment::where(['trxId'=>$trnx_id])->first();
        $date = $myserver->date;
        $union = $myserver->union;
        $unioninfo = Uniouninfo::where('short_name_e',$union)->first();
        $AKPAY_MER_REG_ID = $unioninfo->AKPAY_MER_REG_ID;

        // $trans_date = date("Y-m-d", strtotime($request->trans_date));
        $trans_date = date("Y-m-d", strtotime($date));

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://pg.ekpay.gov.bd/ekpaypg/v1/get-status',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{

        "username":"'.$AKPAY_MER_REG_ID.'",
         "trnx_id":"'.$trnx_id.'",
         "trans_date":"'.$trans_date.'"

        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response1 = curl_exec($curl);

        curl_close($curl);
         $data =  json_decode($response1);





        // $data = $request->all();

        Log::info(json_encode($data));




        // $trnx_id = $data->trnx_info->mer_trnx_id;
        $payment = payment::where('trxid', $trnx_id)->first();






        $Insertdata = [];
        if ($data->msg_code == '1020') {
            $Insertdata = [
                'status' => 'Paid',
                'method' => $data->pi_det_info->pi_name,
            ];
            if($payment->sonod_type=='holdingtax'){
                $hosdingBokeya = HoldingBokeya::find($payment->sonodId);
                // $hosdingtax= Holdingtax::find($hosdingBokeya->holdingTax_id);
                $hosdingBokeya->update(['status'=>'Paid','payYear'=>date('Y')]);
            }else{
                $sonod = Sonod::find($data->cust_info->cust_id);
                // return  $sonod;
                $sonod->update(['khat' => 'সনদ ফি','stutus' => 'Pending', 'payment_status' => 'Paid']);
            }
        }elseif($data->msg_code == '404'){
            $sonod = Sonod::find($payment->sonodId);
            $sonod->update(['khat' => 'সনদ ফি','stutus' => '404', 'payment_status' => 'Failed']);
            $Insertdata = ['status' => '404',];
        } else {
            $sonod = Sonod::find($data->cust_info->cust_id);
            $sonod->update(['khat' => 'সনদ ফি','stutus' => 'failed', 'payment_status' => 'Failed']);
            $Insertdata = ['status' => 'Failed',];
        }
        $Insertdata['ipnResponse'] = json_encode($data);
        // return $Insertdata;
        return $payment->update($Insertdata);
    }





    public function AkpayPaymentCheck(Request $request)
    {

        $trnx_id = $request->trnx_id;
        $myserver = Payment::where(['trxId'=>$trnx_id])->first();
        $date = $myserver->date;
        $union = $myserver->union;
        $unioninfo = Uniouninfo::where('short_name_e',$union)->first();
        $AKPAY_MER_REG_ID = $unioninfo->AKPAY_MER_REG_ID;

        // $trans_date = date("Y-m-d", strtotime($request->trans_date));
        $trans_date = date("Y-m-d", strtotime($date));

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://pg.ekpay.gov.bd/ekpaypg/v1/get-status',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{

         "username":"'.$AKPAY_MER_REG_ID.'",
         "trnx_id":"'.$trnx_id.'",
         "trans_date":"'.$trans_date.'"

        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response1 = curl_exec($curl);

        curl_close($curl);





      return   $data =  [
        'myserver'=>$myserver,
        'akpay'=> json_decode($response1),
      ];


    }








    public function export(Request $request)
    {


        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "500000000000000000");
        ini_set('memory_limit', '12008M');







        //  $request->all();
        $union = $request->union;
        $sonod_type = $request->sonod_type;
        $from = $request->from;
        $to = $request->to;
        $payment_type = $request->payment_type;

        if($payment_type=='menual'){
            $filter=['payment_type'=>null];
        }elseif($payment_type=='online'){
            $filter=['payment_type'=>'online'];
        }elseif($payment_type=='all'){
            $filter=[];
        }


        if($union=='all'){
            $unionfilter=[];
        }else{
            $unionfilter=['union'=>$union];
        }





        if($sonod_type && $from && $to){




            if($sonod_type=='all'){
            // return Payment::where(['status'=>'Paid'])->whereBetween('date', [$from, $to])->orderBy('id','desc')->get();
            $row = Payment::with(['sonod','tax'])->where(['status'=>'Paid'])->where($unionfilter)->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','asc')->get();
            }else{
                 $row = Payment::with(['sonod','tax'])->where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($unionfilter)->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','asc')->get();
            }

            // return $row;
        $uniouninfo = Uniouninfo::where(['short_name_e' => $union])->first();
        // return view('Export',compact('row','uniouninfo','sonod_type','from','to'));
        $pdf = LaravelMpdf::loadView('Export',compact('row','uniouninfo','sonod_type','from','to','union'));
        return $pdf->stream("hlsdfhlo.pdf");

        }




        if($sonod_type=='all'){
            $row = Payment::with(['sonod','tax'])->where(['status'=>'Paid'])->where($unionfilter)->where($filter)->orderBy('id','asc')->get();
        }
        $row = Payment::with(['sonod','tax'])->where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($unionfilter)->where($filter)->orderBy('id','asc')->get();
        // return Excel::download($export, 'report.xlsx');

        $uniouninfo = Uniouninfo::where(['short_name_e' => $union])->first();
        $pdf = LaravelMpdf::loadView('Export',compact('row','uniouninfo','sonod_type','from','to','union'));
        return $pdf->stream("hlsdfhlo.pdf");



    }



    public function Search(Request $request)
    {
        // return $request->all();
        $payment_type = $request->payment_type;
        $sonod_type = $request->sonod_type;

        $from = $request->from;
        $to = $request->to;

        $union = $request->union;

        if($payment_type=='menual'){
            $filter=['payment_type'=>null];
        }elseif($payment_type=='online'){
            $filter=['payment_type'=>'online'];
        }elseif($payment_type=='all'){
            $filter=[];
        }




        if($union){

            if($union=='all'){
                $unionfilter=[];
            }else{
                $unionfilter=['union'=>$union];
            }


            if($from && $to){
                if($sonod_type=='all'){

                    return Payment::where(['status'=>'Paid'])->where($unionfilter)->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($unionfilter)->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','desc')->get();

            }elseif($from){
                if($sonod_type=='all'){
                return Payment::where(['status'=>'Paid'])->where($unionfilter)->where($filter)->where('date',$from)->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($unionfilter)->where($filter)->where('date',$from)->orderBy('id','desc')->get();

            }else{
                if($sonod_type=='all'){
                return Payment::where(['status'=>'Paid'])->where($unionfilter)->where($filter)->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($unionfilter)->where($filter)->orderBy('id','desc')->get();

            }



        }else{

            if($from && $to){
                if($sonod_type=='all'){
                return Payment::where(['status'=>'Paid'])->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($filter)->whereBetween('date', [$from, $to])->orderBy('id','desc')->get();

            }elseif($from){
                if($sonod_type=='all'){
                return Payment::where(['status'=>'Paid'])->where($filter)->where('date',$from)->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($filter)->where('date',$from)->orderBy('id','desc')->get();

            }else{
                if($sonod_type=='all'){
                return Payment::where(['status'=>'Paid'])->where($filter)->orderBy('id','desc')->get();
                }
                return Payment::where(['sonod_type'=>$sonod_type,'status'=>'Paid'])->where($filter)->orderBy('id','desc')->get();

            }
        }

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
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }




    public function SearchByAll(Request $request)
    {
        $type = $request->type;
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;
        $searchData = [];
        if($type=='district'){
            $searchData['district'] = $search;
        }else{
            $searchData['thana'] = $search;
        }





        $unioninfo = Uniouninfo::where($searchData)->get();



        $result = [];
        foreach ($unioninfo as $value) {
            $paymentCount = Payment::where(['union'=>$value->short_name_e,'status'=>'Paid','payment_type'=>'online'])->whereBetween('date', [$from, $to])->count();
            $totalAmount = Payment::where(['union'=>$value->short_name_e,'status'=>'Paid','payment_type'=>'online'])->whereBetween('date', [$from, $to])->sum('amount');

            $resultPush = [
                'unionFull_name'=>$value->full_name,
                'unionShort_name_e'=>$value->short_name_e,
                'short_name_b'=>$value->short_name_b,
                'district'=>$value->district,
                'thana'=>$value->thana,
                'AKPAY_MER_REG_ID'=>$value->AKPAY_MER_REG_ID,
                'totalAmount'=>$totalAmount,
                'paymentCount'=>$paymentCount,
            ];
            array_push($result,$resultPush);
        }

        return $result;


    }



    public function success(Request $request)
    {
        $transId = $request->transId;

        $payment = Payment::where('trxId', $transId)->first();

        if (!$payment) {
            abort(404); // Handle if payment transaction ID not found
        }

        if ($payment->sonod_type == 'holdingtax') {
            $redirect = "/holdingPay/success?transId=$transId";
        } elseif ($payment->sonod_type == 'Tenders_form') {
            $redirect = "/tenderformpay/success?transId=$transId";
        } elseif ($payment->sonod_type == 'tender-deposit') {
            $redirect = "/tenderdeposit/success?transId=$transId";
        } else {
            $sonod = Sonod::find($payment->sonodId);
            if ($sonod && $sonod->pBy == 'Pre Pay') {
                $redirect = "/applications/final/form?sToken=$sonod->uniqeKey";
            } else {
                $redirect = "/payment/success/confirm?transId=$transId";
            }
        }

        return view('payment.success_redirect', ['redirect' => $redirect]);
    }



}
