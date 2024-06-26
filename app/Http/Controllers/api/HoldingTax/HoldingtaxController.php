<?php

namespace App\Http\Controllers\api\HoldingTax;

use App\Http\Controllers\Controller;

use App\Models\Payment;
use App\Models\HoldingTax;
use App\Models\TaxInvoice;
use App\Models\Uniouninfo;
use Illuminate\Http\Request;
use App\Models\HoldingBokeya;
use Illuminate\Support\Facades\DB;
use Rakibhstu\Banglanumber\NumberToBangla;
// use Meneses\LaravelMpdf\Facades\LaravelMpdf;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class HoldingtaxController extends Controller
{



    public function updateHoldingTax($union)
    {
        $holdingtaxs = HoldingTax::with(['holdingBokeyas' => function ($query) {
                $query->where(['year' => '2023-2024', 'price' => 0]);
            }])
            ->where('union', $union)
            ->orderBy('id', 'desc')
            ->get();

        $filteredHoldingTaxs = $holdingtaxs->filter(function ($holdingTax) {
            return !$holdingTax->holdingBokeyas->isEmpty();
        });

        $html = "
            <table border='1' width='100%'>
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Holding No</th>
                        <th>Name</th>
                        <th>Word no</th>
                        <th>NId</th>
                        <th>Mobile</th>
                        <th>griher_barsikh_mullo</th>
                        <th>Return Holding</th>
                    </tr>
                </thead>
                <tbody>
        ";

        $i = 1;
        foreach ($filteredHoldingTaxs as $value) {
            $griher_barsikh_mullo = $value->griher_barsikh_mullo ? int_bn_to_en($value->griher_barsikh_mullo) : 0;
            $jomir_vara = $value->jomir_vara ? int_bn_to_en($value->jomir_vara) : 0;
            $barsikh_vara = $value->barsikh_vara ? int_bn_to_en($value->barsikh_vara) : 0;

            $currentYearAmount = holdingTaxAmount($value->category, $griher_barsikh_mullo, $jomir_vara, $barsikh_vara);

            foreach ($value->holdingBokeyas as $hB) {
                $bokeyaUpdate = HoldingBokeya::find($hB->id);
                $bokeyaUpdate->update(['price' => $currentYearAmount]);
            }

            $html .= "
                <tr>
                    <td>$i</td>
                    <td>$value->holding_no</td>
                    <td>$value->gramer_name</td>
                    <td>$value->word_no</td>
                    <td>$value->nid_no</td>
                    <td>$value->mobile_no</td>
                    <td>$value->griher_barsikh_mullo</td>
                    <td>$currentYearAmount</td>
                </tr>
            ";
            $i++;
        }

        $html .= "
                </tbody>
            </table>
        ";

        return $html;
    }



    public function holdingAllPenddingInvoice(Request $request)
    {
        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "5000000000000000050000000000000000");
        ini_set('memory_limit', '12008M');

         $unioun =  $request->unioun;
         $holdingtax = HoldingTax::where('unioun',$unioun)->get();

         $fileName = 'Invoice-'.date('Y-m-d H:m:s').'.pdf';
         $data['fileName'] = $fileName;
         $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L','default_font' => 'bangla',]);

        foreach ($holdingtax as $value) {
             $holdingBokeya = HoldingBokeya::where(['holdingTax_id'=>$value->id,'status'=>'Unpaid'])->first();
            // foreach ($HoldingBokeya as $HB) {

                // $holdingBokeya = HoldingBokeya::find($HB->id);
                // print_r($holdingBokeya);


                $payYear = $holdingBokeya->payYear;
                $holdingTax_id = $holdingBokeya->holdingTax_id;
                $holdingTax = HoldingTax::find($holdingTax_id);
                $union = $holdingTax->unioun;
                $unions = Uniouninfo::where(['short_name_e'=>$union])->first();




               if($holdingBokeya->status=='Unpaid'){


                    $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->get();
                   $holdingBokeyasAmount = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->sum('price');
                    $TaxInvoicecount =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->count();

                   if($TaxInvoicecount>0){

                    $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->first();
                    $invoice=[
                     'totalAmount'=>$holdingBokeyasAmount,
                     ];
                    $TaxInvoice->update($invoice);
                    }else{

                        $invoice=[
                            'invoiceId'=>time().$holdingBokeya->id,
                            'holdingTax_id'=>$holdingTax_id,
                            'PayYear'=>date('Y'),
                            'totalAmount'=>$holdingBokeyasAmount,
                            'status'=>'Unpaid',
                        ];
                        TaxInvoice::create($invoice);
                    }

                  $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->first();



                }else{

                    $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->get();
                    $holdingBokeyasAmount = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->sum('price');
                    $TaxInvoicecount =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear])->count();


                    if($TaxInvoicecount>0){
                        $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear])->first();
                        $invoice=[
                         'totalAmount'=>$holdingBokeyasAmount,
                         'status'=>'Paid',
                         ];
                        $TaxInvoice->update($invoice);
                 }else{

                     $invoice=[
                         'invoiceId'=>time().$holdingBokeya->id,
                         'holdingTax_id'=>$holdingTax_id,
                         'PayYear'=>$payYear,
                         'totalAmount'=>$holdingBokeyasAmount,
                         'status'=>'Paid',
                     ];
                     TaxInvoice::create($invoice);
                 }

                 $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear,'status'=>'Paid'])->first();


                }













                $holdingTax = HoldingTax::find($holdingTax_id);
                $union = $holdingTax->unioun;
                $unions = Uniouninfo::where(['short_name_e'=>$union])->first();

                if($holdingBokeya->status=='Unpaid'){
                    $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->get();
                    $currentamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid','year'=>'2022-2023'])->sum('price');
                    $previousamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->where('year','!=','2022-2023')->sum('price');
                }else{


                    $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->get();
                    $currentamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear,'year'=>'2022-2023'])->sum('price');
                    $previousamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->where('year','!=','2022-2023')->sum('price');
                }


        // return $previousamount;


                // die();
            //  return   $amounts =(int)number_format($TaxInvoice->totalAmount,2);
                 $amounts =(float)$TaxInvoice->totalAmount;

                $numto = new NumberToBangla();
                $amount = $numto->bnMoney($amounts);

                if($holdingBokeya->status=='Unpaid'){

                    // return $TaxInvoice->invoiceId.' --- '.time().$HB->id;

                    // if($TaxInvoice->invoiceId==time().$HB->id){

                    // }else{

                        $mpdf->WriteHTML( $this->invoice($holdingTax,$unions,$amount,$holdingBokeyas,'right',$TaxInvoice,$currentamount,$previousamount));
                        $mpdf->AddPage();

                    // }

                }




            // }
        }
        $mpdf->Output($fileName,'I');


    }



    public function holdingPaymentInvoice($id)
    {


          $holdingBokeya = HoldingBokeya::find($id);

          $COB = COB(1);
          if($holdingBokeya->payOB){
              $COB = $holdingBokeya->payOB;
          }

        $payYear = $holdingBokeya->payYear;
        $holdingTax_id = $holdingBokeya->holdingTax_id;
        $holdingTax = HoldingTax::find($holdingTax_id);
        $union = $holdingTax->unioun;
        $unions = Uniouninfo::where(['short_name_e'=>$union])->first();




       if($holdingBokeya->status=='Unpaid'){


            $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->get();
           $holdingBokeyasAmount = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->sum('price');
            $TaxInvoicecount =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->count();

           if($TaxInvoicecount>0){

            $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->first();
            $invoice=[
             'totalAmount'=>$holdingBokeyasAmount,
             'orthoBchor'=>$COB,
             ];
            $TaxInvoice->update($invoice);
            }else{

                $invoice=[
                    'invoiceId'=>time().$id,
                    'holdingTax_id'=>$holdingTax_id,
                    'PayYear'=>date('Y'),
                    'orthoBchor'=>$COB,
                    'totalAmount'=>$holdingBokeyasAmount,
                    'status'=>'Unpaid',
                ];
                TaxInvoice::create($invoice);
            }

          $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->first();



        }else{

            $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->get();
            $holdingBokeyasAmount = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->sum('price');
            $TaxInvoicecount =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear])->count();


            if($TaxInvoicecount>0){
                $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear])->first();
                $invoice=[
                 'totalAmount'=>$holdingBokeyasAmount,
                 'orthoBchor'=>$COB,
                 'status'=>'Paid',
                 ];
                $TaxInvoice->update($invoice);
            }else{
                $invoice=[
                    'invoiceId'=>time().$id,
                    'holdingTax_id'=>$holdingTax_id,
                    'PayYear'=>$payYear,
                    'orthoBchor'=>$COB,
                    'totalAmount'=>$holdingBokeyasAmount,
                    'status'=>'Paid',
                ];
                TaxInvoice::create($invoice);
            }

         $TaxInvoice =  TaxInvoice::where(['holdingTax_id'=>$holdingTax_id,'PayYear'=>$payYear,'status'=>'Paid'])->first();


        }













        $holdingTax = HoldingTax::find($holdingTax_id);
        $union = $holdingTax->unioun;
        $unions = Uniouninfo::where(['short_name_e'=>$union])->first();




        if($holdingBokeya->status=='Unpaid'){
            $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->get();
            $currentamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid','year'=>COB(1)])->sum('price');
            $previousamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'status'=>'Unpaid'])->where('year','!=',COB(1))->sum('price');
        }else{


            $holdingBokeyas = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->get();
            $currentamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear,'year'=>COB(1)])->sum('price');
            $previousamount  = HoldingBokeya::where(['holdingTax_id'=>$holdingTax_id,'payYear'=>$payYear])->where('year','!=',COB(1))->sum('price');
        }


// return $previousamount;


        // die();
    //  return   $amounts =(int)number_format($TaxInvoice->totalAmount,2);
    //    return  $amounts =(float)$TaxInvoice->totalAmount.'.00';

         $amounts = number_format((float)$TaxInvoice->totalAmount, 2, '.', ''); ;

        $numto = new NumberToBangla();
        $amount = $numto->bnMoney($amounts) . ' মাত্র';


        $qrurl = url("/holding/tax/invoice/$id");

// return $this->invoice($holdingTax,$unions,$amount,$holdingBokeyas,'right',$TaxInvoice,$currentamount,$previousamount);
        $fileName = 'Invoice-'.date('Y-m-d H:m:s');
        $data['fileName'] = $fileName;
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L','default_font' => 'bangla',]);
        $mpdf->WriteHTML( $this->invoice($holdingTax,$unions,$amount,$holdingBokeyas,'right',$TaxInvoice,$currentamount,$previousamount,$id));
        $mpdf->Output($fileName,'I');

    }



    public function holdingCertificate_of_honor(Request $request,$id)
    {
           $holdingBokeya = HoldingBokeya::find($id);
           $holdingTax = HoldingTax::find($holdingBokeya->holdingTax_id);
              $uniouninfo = Uniouninfo::where('short_name_e',$holdingTax->unioun)->first();



          $fileName = 'Invoice-'.date('Y-m-d H:m:s');
        $pdf = LaravelMpdf::loadView('pdf.certificate_of_honor',compact('uniouninfo','holdingTax','holdingBokeya'));
        return $pdf->stream("$fileName-$holdingBokeya->holdingTax_id.pdf");

    }







    public function invoice($customers,$unions,$amount,$bokeyas,$float,$TaxInvoice,$currentamount=0,$previousamount=0,$id=0){

        $full_name = $unions->full_name;
        $short_name_b = $unions->short_name_b;
        $thana = $unions->thana;
        $district = $unions->district;
        $c_signture = $unions->c_signture;


        $holding_no = $customers->holding_no;
        $maliker_name = $customers->maliker_name;
        $father_or_samir_name = $customers->father_or_samir_name;
        $gramer_name = $customers->gramer_name;
        $word_no = $customers->word_no;
        $mobile_no = $customers->mobile_no;
        $category = $customers->category;
        $busnessName = $customers->busnessName;

        $invoiceId = $TaxInvoice->invoiceId;
        $status = $TaxInvoice->status;
        $orthoBchor = $TaxInvoice->orthoBchor;
        $created_at = date("d/m/Y", strtotime($TaxInvoice->created_at));
        $subtotal = number_format($TaxInvoice->totalAmount,2);



        $qrurl = url("/holding/tax/invoice/$id");
        $qrcode = "<img src='https://chart.apis.google.com/chart?cht=qr&chl=$qrurl&chs=100'/>";



    //     $footerSection = "


    //     <table width='100%'>
    //     <tr>
    //         <td>
    //         ইউপি সচিব/আদায়কারীর স্বাক্ষর
    //             <br/>
    //             তারিখ: ".int_en_to_bn($created_at)."
    //         </td>
    //         <td>
    //         $qrcode
    //         </td>
    //         <td style='text-align:right' width='70px'>
    //         <img width='130px' src='".base64($c_signture)."'/>
    //         ইউপি চেয়ারম্যানের স্বাক্ষর
    //         </td>
    //     </tr>
    // </table>
    //     ";

        $footerSection = "


        <table width='100%'>
        <tr>
            <td>
            ইউপি সচিব/আদায়কারীর স্বাক্ষর
                <br/>
                তারিখ: ".int_en_to_bn($created_at)."
            </td>
            <td>
            $qrcode
            </td>
            <td style='text-align:right' width='130px'>
            ইউপি চেয়ারম্যানের স্বাক্ষর
            </td>
        </tr>
    </table>

    <p style='background-color:#7e7e7e;color: white;padding:5px 10px;text-align:center'>এই রশিদ টি ইলেকট্রনিক ভাবে তৈরি হয়েছে,  কোন স্বাক্ষর প্রয়োজন নেই।</p>



        ";


        // <div style='text-align:right'>(ডিলারের কপি)</div>
        $html = "

        <style>
        @page {
            margin: 10px;
           }

        .memoborder{
            width: 48%;
        }

        .memo {
        //    width: 500px;
        //    margin:0 auto;
        //    padding:20px;
            background: white;

        }



        .memoHead {
            text-align: center;
            color:black
        }
        .companiname {
            margin:0;
        }
        p {

            color:black;
            margin:0;

        }div {

            color:black;
            margin:0;

        }
        p.defalttext.address {
            background:black;
            width: 269px;
            margin: 0 auto;
            color: white;
            border-radius: 50px;
            padding: 2px 0px;
        }
        p.defalttext {
            font-weight: 600;
            font-size: 16px;
            margin: 0;
            /* transform: scaleX(1.2); */

        }


        .thead .tr {
            color: black;
        }
        .thead .tr .th {
            color: black;
        }

        .tr {
            border: 1px solid black;
        }

        .th {
            border: 1px solid black;
            border-right: 1px solid white;
        }

        .td {
            border: 1px solid black;
        }
        .table,  .td {
          border: 1px solid black;
          border-collapse: collapse;
          text-align: center;
          color:black;
        }.th, {
          border: 1px solid white;
          border-collapse: collapse;
        }

        .td {
            height: 18.5px;

        }
        .slNo{
            float: right;
            width: 300px;
        }



.tdlist{
    height: 200px;
    vertical-align: top;
}

        </style>


    <div id='body'>

    <div class='memoborder' style='float:left' >
    <div class='memobg memobg1'>
            <div class='memo'>
            <div class='memoHead'>



            <p class='defalttext'>ইউপি ফরম-১০</p>
            <h2 style='font-weight: 500;' class='companiname'>$full_name</h2>
            <p class='defalttext'>উপজেলা: $thana, জেলা: $district  </p>
            <h2 class='companiname' style='  color: #410fcc;'>ট্যাক্স, রেট ও বিবিধ প্রাপ্তি আদায় রশিদ </h2>";

            if($status=='Paid'){
                $html .="            <h2 class='companiname' style='width: 160px;
                margin: 0 auto;
                background: green;
                color: white;
                border-radius: 50px;
                font-size: 16px;
                padding: 6px 0px;'>পরিশোধিত </h2>";
            }else{

                $html .="           <h2 class='companiname' style='width: 160px;
                margin: 0 auto;
                background: red;
                color: white;
                border-radius: 50px;
                font-size: 16px;
                padding: 6px 0px;'>অপরিশোধিত </h2>";
            }



            $html .="




        </div>

        <table style='width:100%'>
        <tr>
            <td colspan='2'>অর্থ বছর- ".int_en_to_bn($orthoBchor)."</td>
            <td style='text-align:right'>রশিদ নং- ".int_en_to_bn($invoiceId)."</td>
        <tr>

        <tr>
            <td colspan='3'>এসেসমেন্ট/হোল্ডিং নং- ".int_en_to_bn($holding_no)."</td>

        <tr>

        <tr>
            <td >নাম: $maliker_name </td>
            <td colspan='2'>পিতা/স্বামীর নাম- $father_or_samir_name</td>

        <tr>
        <tr>
            <td >ঠিকানা: গ্রাম- $gramer_name,</td>
            <td >ওয়ার্ড- ".int_en_to_bn($word_no)."</td>
            <td >ডাকঘর- $short_name_b</td>

        <tr>
        <tr>
            <td >উপজেলা: $thana </td>
            <td >জেলা: $district</td>
            <td >মোবাইল: ".int_en_to_bn($mobile_no)."</td>

        <tr>
        </table>
    <p></p>




                <div class='memobody' style='position: relative;'>

                    <div class='productDetails'>
                        <table class='table' style='border:1px solid #444B8F;width:100%' cellspacing='0'>
                            <thead class='thead'>
                                <tr class='tr'>
                                    <td class='th defaltfont' colspan='5' width='10%'>আদায়ের বিবরণ</td>
                                </tr>

                                <tr class='tr'>
                                    <td class='td defaltfont' width='5%'>ক্র. নং</td>
                                    <td class='td defaltfont' width='25%'>খাত</td>
                                    <td class='td defaltfont' width='15%'>বিগত বছরের বকেয়া (যদি থাকে) টাকা</td>
                                    <td class='td defaltfont' width='15%'>চলতি অর্থ বছরে টাকার পরিমাণ</td>
                                    <td class='td defaltfont' width='15%'>মোট টাকার পরিমাণ</td>



                                </tr>
                            </thead>
                            <tbody class='tbody'>";



                                    // $totalpay = $orders->pay;
                                    // $totaldue = $orders->due;
                                    $index = 1;

                                // $orderDetails = json_decode($orders->Invoices);


                                  $html .="  <tr class='tr items'>
                                        <td class='td tdlist defaltfont'>".int_en_to_bn($index)."</td>";


                            if ($category=='প্রতিষ্ঠান') {

                                $html .="<td class='td tdlist defaltfont'>প্রতিষ্ঠানের বাৎসরিক মূল্যের উপর কর <br/> ($busnessName)</td>";
                            }else{
                                $html .="<td class='td tdlist defaltfont'>বসত বাড়ীর বাৎসরিক মূল্যের উপর কর</td>";

                            }



                                         $html .="<td class='td tdlist defaltfont'>".int_en_to_bn(number_format($previousamount,2))."</td>
                                        <td class='td tdlist defaltfont'>".int_en_to_bn(number_format($currentamount,2))."</td>
                                        <td class='td tdlist defaltfont'>".int_en_to_bn($subtotal)."</td>

                                    </tr>";

                                        $index++;









                                $html .=" </tbody>
                            <tfoot class='tfoot'>";





                            $html .="
                            <tr class='tr'>
                            <td colspan='4' class='defalttext td defaltfont'style='text-align:right;    padding: 0 13px;'><p> মোট </p></td>
                            <td class='td defaltfont'>".int_en_to_bn($subtotal)."</td>
                    </tr>


                            ";








                                $html .=" </tfoot>
                        </table>
                        <p style='margin-top:15px;padding:0 15px;' class='defaltfont'>কথায় : $amount</p>

                    </div>
                </div>
                <div class='memofooter' style='margin-top:25px'>

                    $footerSection

                </div>
            </div>
        </div>
        </div>



    <div class='memoborder' style='float:right' >
    <div class='memobg memobg1'>
            <div class='memo'>
            <div class='memoHead'>



            <p class='defalttext'>ইউপি ফরম-১০</p>
            <h2 style='font-weight: 500;' class='companiname'>$full_name</h2>
            <p class='defalttext'>উপজেলা: $thana, জেলা: $district  </p>
            <h2 class='companiname'  style='  color: #410fcc;'>ট্যাক্স, রেট ও বিবিধ প্রাপ্তি আদায় রশিদ </h2>";

            if($status=='Paid'){
                $html .="            <h2 class='companiname' style='width: 160px;
                margin: 0 auto;
                background: green;
                color: white;
                border-radius: 50px;
                font-size: 16px;
                padding: 6px 0px;'>পরিশোধিত </h2>";
            }else{

                $html .="           <h2 class='companiname' style='width: 160px;
                margin: 0 auto;
                background: red;
                color: white;
                border-radius: 50px;
                font-size: 16px;
                padding: 6px 0px;'>অপরিশোধিত </h2>";
            }



            $html .="




        </div>

        <table style='width:100%'>
        <tr>
            <td colspan='2'>অর্থ বছর- ".int_en_to_bn($orthoBchor)."</td>
            <td style='text-align:right'>রশিদ নং- ".int_en_to_bn($invoiceId)."</td>
        <tr>

        <tr>
            <td colspan='3'>এসেসমেন্ট/হোল্ডিং নং- ".int_en_to_bn($holding_no)."</td>

        <tr>

        <tr>
            <td >নাম: $maliker_name </td>
            <td colspan='2'>পিতা/স্বামীর নাম- $father_or_samir_name</td>

        <tr>
        <tr>
            <td >ঠিকানা: গ্রাম- $gramer_name,</td>
            <td >ওয়ার্ড- ".int_en_to_bn($word_no)."</td>
            <td >ডাকঘর- $short_name_b</td>

        <tr>
        <tr>
            <td >উপজেলা: $thana </td>
            <td >জেলা: $district</td>
            <td >মোবাইল: ".int_en_to_bn($mobile_no)."</td>

        <tr>
        </table>
    <p></p>




                <div class='memobody' style='position: relative;'>

                    <div class='productDetails'>
                        <table class='table' style='border:1px solid #444B8F;width:100%' cellspacing='0'>
                            <thead class='thead'>
                                <tr class='tr'>
                                    <td class='th defaltfont' colspan='5' width='10%'>আদায়ের বিবরণ</td>
                                </tr>

                                <tr class='tr'>
                                    <td class='td defaltfont' width='5%'>ক্র. নং</td>
                                    <td class='td defaltfont' width='25%'>খাত</td>
                                    <td class='td defaltfont' width='15%'>বিগত বছরের বকেয়া (যদি থাকে) টাকা</td>
                                    <td class='td defaltfont' width='15%'>চলতি অর্থ বছরে টাকার পরিমাণ</td>
                                    <td class='td defaltfont' width='15%'>মোট টাকার পরিমাণ</td>



                                </tr>
                            </thead>
                            <tbody class='tbody'>";



                                    // $totalpay = $orders->pay;
                                    // $totaldue = $orders->due;
                                    $index = 1;

                                // $orderDetails = json_decode($orders->Invoices);


                                $html .="  <tr class='tr items'>
                                <td class='td tdlist defaltfont'>".int_en_to_bn($index)."</td>";


                                if ($category=='প্রতিষ্ঠান') {
                                    $html .="<td class='td tdlist defaltfont'>প্রতিষ্ঠানের বাৎসরিক মূল্যের উপর কর  <br/> ($busnessName)</td>";
                                }else{
                                    $html .="<td class='td tdlist defaltfont'>বসত বাড়ীর বাৎসরিক মূল্যের উপর কর</td>";
                                }



                                 $html .="<td class='td tdlist defaltfont'>".int_en_to_bn(number_format($previousamount,2))."</td>
                                <td class='td tdlist defaltfont'>".int_en_to_bn(number_format($currentamount,2))."</td>
                                <td class='td tdlist defaltfont'>".int_en_to_bn($subtotal)."</td>

                            </tr>";

                                        $index++;









                                $html .=" </tbody>
                            <tfoot class='tfoot'>";





                            $html .="
                            <tr class='tr'>
                            <td colspan='4' class='defalttext td defaltfont'style='text-align:right;    padding: 0 13px;'><p> মোট </p></td>
                            <td class='td defaltfont'>".int_en_to_bn($subtotal)."</td>
                    </tr>


                            ";








                                $html .=" </tfoot>
                        </table>
                        <p style='margin-top:15px;padding:0 15px;' class='defaltfont'>কথায় : $amount</p>

                    </div>
                </div>
                <div class='memofooter' style='margin-top:25px'>

                    $footerSection

                </div>
            </div>
        </div>
        </div>




        </div>



        ";


        return $html;
    }





    public function invoice1($customers,$unions,$amount,$bokeyas,$float,$TaxInvoice,$currentamount=0,$previousamount=0){

        $full_name = $unions->full_name;
        $thana = $unions->thana;
        $district = $unions->district;


        $maliker_name = $customers->maliker_name;
        $gramer_name = $customers->gramer_name;

        $invoiceId = $TaxInvoice->invoiceId;
        $status = $TaxInvoice->status;
        $created_at = date("d/m/Y", strtotime($TaxInvoice->created_at));
        $subtotal = number_format($TaxInvoice->totalAmount,2);

        // <div style='text-align:right'>(ডিলারের কপি)</div>
        $html = "

        <style>
        @page {
            margin: 10px;
           }

        .memoborder{
            width: 48%;
        }

        .memobg {

            padding: 10px;
            position: relative;
            background: linear-gradient(42deg, rgba(163, 92, 33, 1) 0%, rgba(147, 61, 83, 1) 11%, rgba(67, 120, 108, 1) 24%, rgba(28, 140, 194, 1) 33%, rgba(88, 132, 157, 1) 42%, rgba(135, 119, 143, 1) 51%, rgba(162, 87, 145, 1) 61%, rgba(180, 126, 20, 1) 71%, rgba(190, 155, 49, 1) 80%, rgba(195, 113, 90, 1) 89%, rgba(111, 137, 52, 1) 98%);
            /* padding: 3px; */

        }
        .memo {
        //    width: 500px;
        //    margin:0 auto;
        //    padding:20px;
            background: white;

        }



        .memoHead {
            text-align: center;
            color:#444B8F
        }
        h1.companiname {
            transform: scaleX(2);
            margin:0;
        }
        p {

            color:#444B8F;
            margin:0;

        }div {

            color:#444B8F;
            margin:0;

        }
        p.defalttext.address {
            background:#444B8F;
            width: 269px;
            margin: 0 auto;
            color: white;
            border-radius: 50px;
            padding: 2px 0px;
        }
        p.defalttext {
            font-weight: 600;
            font-size: 16px;
            margin: 0;
            /* transform: scaleX(1.2); */

        }


        thead tr {
            background: #444B8F;
        }
        thead tr th {
            color: white;
        }

        tr {
            border: 1px solid #444B8F;
        }

        th {
            border: 1px solid #444B8F;
            border-right: 1px solid white;
        }

        td {
            border: 1px solid #444B8F;
        }
        table,  td {
          border: 1px solid #444B8F;
          border-collapse: collapse;
          text-align: center;
          color:#444B8F;
        }th, {
          border: 1px solid white;
          border-collapse: collapse;
        }

        td {
            height: 18.5px;

        }
        .slNo{
            float: right;
            width: 300px;
        }
        </style>


    <div id='body'>

    <div class='memoborder' style='float:left' >
    <div class='memobg memobg1'>
            <div class='memo'>
            <div class='memoHead'>

            <img width='50px' style='margin-top:10px' src='".base64('assets/img/bangladesh-govt.png')."' />

            <p class='defalttext'>গণপ্রজাতন্ত্রী বাংলাদেশ</p>
            <h1 class='companiname'>$full_name $status</h1>
            <p class='defalttext'>উপজেলা: $thana, জেলা: $district  ।</p>
            <p class='defalttext address'>হোল্ডিং ট্যাক্স </p>


            <div style='display:flex; margin-top:20px'>

            </div>

        </div>

                <div class='memobody' style='position: relative;'>
                    <table width='100%' style='border: 1px solid #2F77A5;margin-bottom:20px' cellspacing='0'>
                        <tr>
                            <td style='background:#2F77A5;padding:10px 5px;color:white;padding:5px 5px;width:15%;float:left;border-bottom:1px solid #2F77A5;text-align:left'
                                class='defaltfont'>নাম</td>
                            <td style='border-bottom:1px solid #2F77A5;padding-left:6px;color:#2F77A5;text-align:left'
                                class='defaltfont'> $maliker_name </td>
                            <td width='10%'
                                style='background:#2F77A5;padding:10px 5px;color:white;padding:5px 5px;width:15%;float:left;border-bottom:1px solid #2F77A5;text-align:left'
                                class='defaltfont'>ক্রমিক নং</td>
                            <td width='20%' style='border-bottom:1px solid #2F77A5;padding-left:6px;color:#2F77A5;text-align:left'
                                class='defaltfont'>$invoiceId</td>
                        </tr>
                        <tr>
                            <td style='background:#935E6C;padding:10px 5px;color:white;padding:5px 5px;width:15%;float:left;text-align:left'
                                class='defaltfont'>ঠিকানা</td>
                            <td class='defaltfont' style='padding-left:6px;color:#2F77A5;text-align:left'> $gramer_name</td>
                            <td style='background:#935E6C;padding:10px 5px;color:white;padding:5px 5px;width:15%;float:left;text-align:left'
                                class='defaltfont'>তারিখ</td>
                            <td class='defaltfont' style='padding-left:6px;color:#2F77A5;text-align:left'>".int_en_to_bn($created_at)."
                            </td>
                        </tr>
                    </table>
                    <div class='productDetails'>
                        <table class='table' style='border:1px solid #444B8F;width:100%' cellspacing='0'>
                            <thead class='thead'>
                                <tr class='tr'>
                                    <th class='th defaltfont' width='10%'>ক্রমিক নং</th>
                                    <th class='th defaltfont' width='45%'>অর্থ বছর</th>

                                    <th class='th defaltfont' width='15%'>টাকা</th>
                                </tr>
                            </thead>
                            <tbody class='tbody'>";



                                    // $totalpay = $orders->pay;
                                    // $totaldue = $orders->due;
                                    $index = 1;

                                // $orderDetails = json_decode($orders->Invoices);

                                foreach($bokeyas as $bokeya){
                                  $html .="  <tr class='tr'>
                                        <td class='td defaltfont'>".int_en_to_bn($index)."</td>
                                        <td class='td defaltfont'>".int_en_to_bn($bokeya->year)."</td>
                                        <td class='td defaltfont'>".int_en_to_bn($bokeya->price)."</td>
                                    </tr>";

                                        $index++;
                                        // $subtotal += $product->pay;

                                }


                                    $totalrow = 16-$index;
                                    for ($i=0; $i <$totalrow ; $i++) {
                                        $html .=" <tr class='tr'>
                                        <td class='td defaltfont'>".int_en_to_bn($i+$index)."</td>
                                        <td class='td defaltfont'></td>
                                        <td class='td defaltfont'></td>
                                    </tr>";
                                    }



                                $html .=" </tbody>
                            <tfoot class='tfoot'>";





                            $html .="
                            <tr class='tr'>
                            <td colspan='2' class='defalttext td defaltfont'style='text-align:right;    padding: 0 13px;'><p> মোট </p></td>
                            <td class='td defaltfont'>".int_en_to_bn($subtotal)."</td>
                    </tr>


                            ";








                                $html .=" </tfoot>
                        </table>
                        <p style='margin-top:15px;padding:0 15px;' class='defaltfont'>কথায় : $amount</p>

                    </div>
                </div>
                <div class='memofooter' style='margin-top:25px'>
                    <p style='float:left;width:30%;padding:10px 15px' class='defaltfont'>ক্রেতার স্বাক্ষর</p>

                    <p style='float:right;width:30%;text-align:right;padding:10px 15px' class='defaltfont'>বিক্রেতার
                    স্বাক্ষর</p>
                </div>
            </div>
        </div>
        </div>




        </div>



        ";


        return $html;
    }

















    public function int_bn_to_en($number)
    {
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        return str_replace($bn_digits, $en_digits, $number);
    }


    public function index(Request $r)
    {
        $word = $r->word;
        $union = $r->union;
        if(!$word && !$union){

            return HoldingTax::orderBy('id','desc')->paginate(20);
        }
        if(!$word){

            return HoldingTax::where(['unioun' => $union])->orderBy('id','desc')->paginate(20);
        }
        return HoldingTax::where(['unioun' => $union, 'word_no' => $word])->orderBy('id','desc')->paginate(20);
    }


    public function bokeyaReport(Request $request)
    {


        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "50000000000000000");
        ini_set('memory_limit', '12008M');

        $word = $request->word;
        $union = $request->union;
        $status = 'Unpaid';
        $uniouninfo = Uniouninfo::where(['short_name_e' => $union])->first();
        if(!$word && !$union){
        //   return  $holdingtaxs = HoldingTax::with('holdingBokeyas')->orderBy('id','desc')->get();

    $holdingtaxs =  HoldingTax::with(['holdingBokeyas' => function ($query) use ($status) {$query->where('status', $status)->where('price','!=','0');}])->orderBy('id','desc')->get();
    $holdingtaxs =  $filteredHoldingTaxs = $holdingtaxs->filter(function ($holdingTax) {
       return !$holdingTax->holdingBokeyas->isEmpty();
   });

            $fileName = 'report-'.date('Y-m-d H:m:s');
            $pdf = LaravelMpdf::loadView('pdf.unpaidHolding',compact('uniouninfo','holdingtaxs','word'));
            return $pdf->stream("$fileName.pdf");

        }
        if(!$word){

            // $holdingtaxs =  HoldingTax::with('holdingBokeyas')->where(['unioun' => $union])->orderBy('id','desc')->get();

            $holdingtaxs =  HoldingTax::with(['holdingBokeyas' => function ($query) use ($status) {$query->where('status', $status)->where('price','!=','0');}])->where(['unioun' => $union])->orderBy('id','desc')->get();
            $holdingtaxs =  $filteredHoldingTaxs = $holdingtaxs->filter(function ($holdingTax) {
               return !$holdingTax->holdingBokeyas->isEmpty();
           });

            $fileName = 'Invoice-'.date('Y-m-d H:m:s');
            $pdf = LaravelMpdf::loadView('pdf.unpaidHolding',compact('uniouninfo','holdingtaxs','word'));
            return $pdf->stream("$fileName.pdf");

        }



    $holdingtaxs =  HoldingTax::with(['holdingBokeyas' => function ($query) use ($status) {$query->where('status', $status)->where('price','!=','0');}])->where(['unioun' => $union, 'word_no' => $word])->orderBy('id','desc')->get();
     $holdingtaxs =  $filteredHoldingTaxs = $holdingtaxs->filter(function ($holdingTax) {
        return !$holdingTax->holdingBokeyas->isEmpty();
    });



        $fileName = 'Invoice-'.date('Y-m-d H:m:s');
        $pdf = LaravelMpdf::loadView('pdf.unpaidHolding',compact('uniouninfo','holdingtaxs','word'));
        return $pdf->stream("$fileName.pdf");




    }



    public function show(Request $r,$id)
    {

        return HoldingTax::find($id);
    }
    public function store(Request $r)
    {





        if($r->id){
            $id = $r->id;
            $data = $r->except('bokeya','image');
            $Holdingtax =  HoldingTax::find($id);
            $Holdingtax->update($data);
            $bokeya = $r->bokeya;
            foreach ($bokeya as $value) {
            $HoldingBokeya =  HoldingBokeya::find($value['id']);
            $HoldingBokeya->update($value);
            }
            return $Holdingtax;
        }






        //  echo '<pre>';
        //  print_r($r->input());
        $griher_barsikh_mullo = int_bn_to_en($r->griher_barsikh_mullo);
        $jomir_vara = int_bn_to_en($r->jomir_vara);
        $barsikh_vara = int_bn_to_en($r->barsikh_vara);
        $category = $r->category;
        if ($category == 'মালিক নিজে বসবাসকারী') {
            //বার্ষিক মূল্যের 7.5%
            $barsikh_muller_percent = ($griher_barsikh_mullo * 7.5) / 100;
            //মোট মূল্য
            $total_mullo = $jomir_vara + $barsikh_muller_percent;
            //রক্ষণাবেক্ষণ খরচ
            $rokhona_bekhon_khoroch = $total_mullo / 6;
            // প্রাক্কলিত মূল্য
            $prakklito_mullo = $total_mullo - $rokhona_bekhon_khoroch;
            // রেয়াত
            $reyad = $prakklito_mullo / 4;
            // প্রদেয় করযোগ্য বার্ষিক মূল্য
            $prodey_korjoggo_barsikh_mullo = $prakklito_mullo - $reyad;
            // নির্ধারিত হোল্ডিং কর (৭%)
            $current_year_kor = ($prodey_korjoggo_barsikh_mullo * 7) / 100;
            if ($current_year_kor >= 500) {
                $current_year_kor = 500;
            } else {
                $current_year_kor = $current_year_kor;
            }
            //echo $current_year_kor;
            //আংশিক প্রদেয় করযোগ্য
            $angsikh_prodoy_korjoggo_barsikh_mullo = '';
            //রক্ষণাবেক্ষণ খরচ (6%)
            $rokhona_bekhon_khoroch_percent = '';
            //প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য
            $prodey_korjoggo_barsikh_varar_mullo = '';
            //মোট প্রদেয় করযোগ্য বার্ষিক মূল্য
            $total_prodey_korjoggo_barsikh_mullo = '';
        } else if ($category == 'প্রতিষ্ঠান') {
            //বার্ষিক মূল্যের 7.5%
            $barsikh_muller_percent = ($griher_barsikh_mullo * 7.5) / 100;
            //মোট মূল্য
            $total_mullo = $jomir_vara + $barsikh_muller_percent;
            //রক্ষণাবেক্ষণ খরচ
            $rokhona_bekhon_khoroch = $total_mullo / 6;
            // প্রাক্কলিত মূল্য
            $prakklito_mullo = $total_mullo - $rokhona_bekhon_khoroch;
            // রেয়াত
            $reyad = $prakklito_mullo / 4;
            // প্রদেয় করযোগ্য বার্ষিক মূল্য
            $prodey_korjoggo_barsikh_mullo = $prakklito_mullo - $reyad;
            // নির্ধারিত হোল্ডিং কর (৭%)
            $current_year_kor = ($prodey_korjoggo_barsikh_mullo * 7) / 100;
            //আংশিক প্রদেয় করযোগ্য
            $angsikh_prodoy_korjoggo_barsikh_mullo = '';
            //রক্ষণাবেক্ষণ খরচ (6%)
            $rokhona_bekhon_khoroch_percent = '';
            //প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য
            $prodey_korjoggo_barsikh_varar_mullo = '';
            //মোট প্রদেয় করযোগ্য বার্ষিক মূল্য
            $total_prodey_korjoggo_barsikh_mullo = '';
        } else if ($category == 'ভাড়া') {
            //ভারা
            //বার্ষিক ভাড়ার মূল্যের
            //রক্ষণাবেক্ষণ খরচ ৬%
            // প্রদেয় করযোগ্য বার্ষিক মূল্য = বার্ষিক ভাড়ার মূল্যের - রক্ষণাবেক্ষণ খরচ
            // নির্ধারিত হোল্ডিং কর (৭%) =  প্রদেয় করযোগ্য বার্ষিক মূল্য৭%
            //বার্ষিক মূল্যের 7.5%
            $barsikh_muller_percent = '';
            //মোট মূল্য
            $total_mullo = '';
            //রক্ষণাবেক্ষণ খরচ
            $rokhona_bekhon_khoroch = '';
            // প্রাক্কলিত মূল্য
            $prakklito_mullo = '';
            // রেয়াত
            $reyad = '';
            // প্রদেয় করযোগ্য বার্ষিক মূল্য
            $prodey_korjoggo_barsikh_mullo = '';
            //আংশিক প্রদেয় করযোগ্য
            $angsikh_prodoy_korjoggo_barsikh_mullo = '';
            //রক্ষণাবেক্ষণ খরচ (6%)
            $rokhona_bekhon_khoroch_percent = $barsikh_vara / 6;;
            //প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য
            $prodey_korjoggo_barsikh_varar_mullo = $barsikh_vara - $rokhona_bekhon_khoroch_percent;
            //মোট প্রদেয় করযোগ্য বার্ষিক মূল্য
            $total_prodey_korjoggo_barsikh_mullo = '';
            // নির্ধারিত হোল্ডিং কর (৭%)
            $current_year_kor = ($prodey_korjoggo_barsikh_varar_mullo * 7) / 100;
            if ($current_year_kor >= 500) {
                $current_year_kor = 500;
            } else {
                $current_year_kor = $current_year_kor;
            }
            //echo $current_year_kor;
        } else if ($category == 'আংশিক ভাড়া') {
            //বার্ষিক মূল্যের 7.5%
            $barsikh_muller_percent = ($griher_barsikh_mullo * 7.5) / 100;
            //মোট মূল্য
            $total_mullo = $jomir_vara + $barsikh_muller_percent;
            //রক্ষণাবেক্ষণ খরচ
            $rokhona_bekhon_khoroch = $total_mullo / 6;
            // প্রাক্কলিত মূল্য
            $prakklito_mullo = $total_mullo - $rokhona_bekhon_khoroch;
            // রেয়াত
            $reyad = $prakklito_mullo / 4;
            // প্রদেয় করযোগ্য বার্ষিক মূল্য
            $prodey_korjoggo_barsikh_mullo = '';
            //আংশিক প্রদেয় করযোগ্য
            $angsikh_prodoy_korjoggo_barsikh_mullo = $prakklito_mullo - $reyad;
            //রক্ষণাবেক্ষণ খরচ (6%)
            $rokhona_bekhon_khoroch_percent = $barsikh_vara / 6;
            //প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য
            $prodey_korjoggo_barsikh_varar_mullo = $barsikh_vara - $rokhona_bekhon_khoroch_percent;
            //মোট প্রদেয় করযোগ্য বার্ষিক মূল্য
            $total_prodey_korjoggo_barsikh_mullo = $angsikh_prodoy_korjoggo_barsikh_mullo + $prodey_korjoggo_barsikh_varar_mullo;
            // নির্ধারিত হোল্ডিং কর (৭%)
            $current_year_kor = ($total_prodey_korjoggo_barsikh_mullo * 7) / 100;
            if ($current_year_kor >= 500) {
                $current_year_kor = 500;
            } else {
                $current_year_kor = $current_year_kor;
            }
            //echo $current_year_kor;
        }

        //die();
        //ভারা
        //বার্ষিক ভাড়ার মূল্যের
        //রক্ষণাবেক্ষণ খরচ ৬%
        // প্রদেয় করযোগ্য বার্ষিক মূল্য = বার্ষিক ভাড়ার মূল্যের - রক্ষণাবেক্ষণ খরচ
        // নির্ধারিত হোল্ডিং কর (৭%) =  প্রদেয় করযোগ্য বার্ষিক মূল্য৭%
        //মালিক + ভাড়া
        //গৃহের বার্ষিক  মূল্য
        //বার্ষিক মূল্যের 7.5%
        //জমির ভাড়া
        //মোট মূল্য
        //রক্ষণাবেক্ষণ খরচ
        //প্রাক্কলিত মূল্য
        //রেয়াত
        //আংশিক প্রদেয় করযোগ্য = প্রাক্কলিত মূল্য -রেয়াত
        //বার্ষিক ভাড়ার = ইনপুট
        //রক্ষণাবেক্ষণ খরচ (6%)  = বার্ষিক ভাড়ার ৬%
        //প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য  = বার্ষিক ভাড়ার -রক্ষণাবেক্ষণ খরচ
        //মোট প্রদেয় করযোগ্য বার্ষিক মূল্য  = আংশিক প্রদেয় করযোগ্য + প্রদেয় করযোগ্য বার্ষিক ভাড়ার মূল্য
        // নির্ধারিত হোল্ডিং কর (৭%) = মোট প্রদেয় করযোগ্য বার্ষিক মূল্য৭%


$total_bokeya = 0;
        foreach ($r->bokeya as  $value) {
           $total_bokeya += $value['price'];

        }

        $bokeya = json_encode($r->bokeya);

        $total_bokeya = $current_year_kor + $total_bokeya;




         $data = $r->except('bokeya','image');
      $data['bokeya'] = $bokeya;
      $data['total_bokeya'] = $total_bokeya;
      $data['barsikh_muller_percent'] = $barsikh_muller_percent;
      $data['total_mullo'] = $total_mullo;
      $data['rokhona_bekhon_khoroch'] = $rokhona_bekhon_khoroch;
      $data['prakklito_mullo'] = $prakklito_mullo;
      $data['reyad'] = $reyad;
      $data['angsikh_prodoy_korjoggo_barsikh_mullo'] = $angsikh_prodoy_korjoggo_barsikh_mullo;
      $data['barsikh_vara'] = $barsikh_vara;
      $data['rokhona_bekhon_khoroch_percent'] = $rokhona_bekhon_khoroch_percent;
      $data['prodey_korjoggo_barsikh_mullo'] = $prodey_korjoggo_barsikh_mullo;
      $data['prodey_korjoggo_barsikh_varar_mullo'] = $prodey_korjoggo_barsikh_varar_mullo;
      $data['total_prodey_korjoggo_barsikh_mullo'] = $total_prodey_korjoggo_barsikh_mullo;
      $data['current_year_kor'] = $current_year_kor;

      $imageCount =  count(explode(';', $r->image));
      if ($imageCount > 1) {
          $data['image'] =  fileupload($r->image, "holding/image/", 250, 300);
      }


        $holding =  HoldingTax::create($data);
        $curentdata = [
            'holdingTax_id'=>$holding->id,
            'year'=>"2023-2024",
            'price'=>$current_year_kor,
            'status'=>'Unpaid'
        ];






       $hosdingBokeya =  HoldingBokeya::create($curentdata);

$total_bokeya = 0;
        foreach ($r->bokeya as  $value) {
           $total_bokeya += $value['price'];
            $payYear = '';
           if($value['status']=='Paid'){
            $payYear = explode('-',$value['year'])[0];



            $hosdingtax= HoldingTax::find($hosdingBokeya->holdingTax_id);
            $req_timestamp = date('Y-m-d H:i:s');
           $customerData = [
               'union' => $hosdingtax->unioun,
               'trxId' => time(),
               'sonodId' => $hosdingBokeya->id,
               'sonod_type' => 'holdingtax',
               'amount' => $hosdingBokeya->price,
               'mob' => "01909756552",
               'status' => "Paid",
               'date' => $payYear.date('-m-d'),
               'month' => date('F'),
               'year' => $payYear,
               'created_at' => $req_timestamp,
           ];
           Payment::create($customerData);

           }


            $bokeyadata = [
                'holdingTax_id'=>$holding->id,
                'year'=>$value['year'],
                'price'=>$value['price'],
                'payYear'=>$payYear,
                'status'=>$value['status']
            ];
            HoldingBokeya::create($bokeyadata);


        }
        return $holding;



    }

    public function holding_tax_pay_Online(Request $request,$id)
    {
        $holdingBokeya = HoldingBokeya::find($id);


       $holdingTax = HoldingTax::where(['id'=>$holdingBokeya->holdingTax_id])->first();
      $unioninfos = Uniouninfo::where(['short_name_e' => $holdingTax->unioun])->first();
      $u_code = $unioninfos->u_code;

    //   $holdingBokeyasAmount = HoldingBokeya::where(['holdingTax_id'=>$holdingBokeya->holdingTax_id,'status'=>'Unpaid'])->sum('price');

        $trnx_id = $u_code.'-'.time();
        $cust_info = [
            "cust_email" => "",
            "cust_id" => "$holdingBokeya->id",
            "cust_mail_addr" => "Address",
            "cust_mobo_no" => "01909756552",
            "cust_name" => "Customer Name"
        ];

        $trns_info = [
            "ord_det" => 'sonod',
            "ord_id" => "$holdingBokeya->id",
            "trnx_amt" => $holdingBokeya->price,
            "trnx_currency" => "BDT",
            "trnx_id" => "$trnx_id"
        ];
        $redirectutl = ekpayToken($trnx_id, $trns_info, $cust_info,'payment',$holdingTax->unioun);


        $req_timestamp = date('Y-m-d H:i:s');
        $customerData = [
            'union' => $holdingTax->unioun,
            'trxId' => $trnx_id,
            'sonodId' => $id,
            'sonod_type' => 'holdingtax',
            'amount' => $holdingBokeya->price,
            'mob' => "01909756552",
            'status' => "Pending",
            'paymentUrl' => $redirectutl,
            'method' => 'ekpay',
            'payment_type' => 'online',
            'date' => date('Y-m-d'),
            'created_at' => $req_timestamp,
        ];
        Payment::create($customerData);

        //  $redirectutl =  ekpayToken($trnx_id, $holdingBokeya->price, $cust_info,'holdingPay');


        echo "
        <script>
            window.location.href='$redirectutl'
        </script>

        ";

    }

        public function holdingPaymentSuccess(Request $request)
        {
            // return $request->all();
            $transId = $request->transId;
            $payment = Payment::where(['trxId'=>$transId])->first();
            // $payment->update(['status'=>'Paid']);
            $holdingBokeya = HoldingBokeya::find($payment->sonodId);

            // $holdingBokeya->update(['status'=>'Paid']);

            $redirectutl = url("/holding/tax/invoice/$holdingBokeya->id");
            echo "
            <script>
                window.location.href='$redirectutl'
            </script>

            ";

        }



    public function holding_tax_Delete($id, $word)
    {
        DB::table('holdingtaxs')->where('id', $id)->delete();
        return redirect('/admin/holding_tax/list/' . $word);
    }
    public function holding_tax_view(Request $r, $id, $word)
    {
        $data['word'] = $word;
        $data['name'] = 'হোল্ডিং ট্যাক্স';
        $data['result'] = DB::table('holdingtaxs')->where('id', $id)->get();
        return view('mainAdmin/holdingTax.view', $data);
    }
    public function holding_tax_pay(Request $r)
    {
         $hosdingBokeya = HoldingBokeya::find($r->id);
         $hosdingtax= HoldingTax::find($hosdingBokeya->holdingTax_id);
         $req_timestamp = date('Y-m-d H:i:s');
        $customerData = [
            'union' => $hosdingtax->unioun,
            'trxId' => time(),
            'sonodId' => $r->id,
            'sonod_type' => 'holdingtax',
            'amount' => $hosdingBokeya->price,
            'mob' => "01909756552",
            'status' => "Paid",
            'date' => date('Y-m-d'),
            'created_at' => $req_timestamp,
        ];
        Payment::create($customerData);
        return $hosdingBokeya->update(['status'=>'Paid','payYear'=>date('Y')]);



    }


    public function holdingSearch(Request $r)
    {



        $userdata = $r->userdata;
        if($r->union){
            $union = $r->union;



            return DB::table('holdingtaxes')
            ->where('unioun',$union)
            ->where(function ($q)  use ($userdata,$union) {

                $q->orWhere('holding_no', 'like', "%$userdata%")
                ->orWhere('maliker_name', 'like', "%$userdata%")
                ->orWhere('nid_no', 'like', "%$userdata%")
                ->orWhere('mobile_no', 'like', "%$userdata%");
            })
            ->paginate(20);
        }


        $data['userdata']=$userdata;

        return DB::table('holdingtaxes')

        ->orWhere('holding_no', 'like', "%$userdata%")
        ->orWhere('maliker_name', 'like', "%$userdata%")
        ->orWhere('nid_no', 'like', "%$userdata%")
        ->orWhere('mobile_no', 'like', "%$userdata%")

        ->paginate(20);




    }


}
