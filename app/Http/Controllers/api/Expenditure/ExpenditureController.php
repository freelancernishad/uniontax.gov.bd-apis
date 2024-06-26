<?php

namespace App\Http\Controllers\api\Expenditure;

use App\Http\Controllers\Controller;

use App\Models\Payment;
use App\Models\Uniouninfo;
use App\Models\Expenditure;
use Illuminate\Http\Request;
use Meneses\LaravelMpdf\Facades\LaravelMpdf;

class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $unioun_name = $request->unioun_name;
        $month_year = $request->month;
        $month =  date('F',strtotime($request->month));
        $year = date('Y',strtotime($request->month));
        $type = $request->type;

        if($type=='month'){

            return Expenditure::where(['unioun_name'=>$unioun_name,'month'=>$month,'year'=>$year])->get();

        }elseif($type=='year'){
           $year = $request->year;
            return Expenditure::where(['unioun_name'=>$unioun_name,'year'=>$year])->select(['month','year','unioun_name'])->distinct()->orderBy('month','desc')->get();
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
        $data =  $request->all();

        $data['month'] = date('F',strtotime($request->date));
        $data['year'] = date('Y',strtotime($request->date));



        $monthName = date('F',strtotime($request->date));
        $expenditure = Expenditure::where(['month'=>$monthName])->latest()->first();
        $Payment = Payment::where(['month'=>$monthName])->latest()->first();
        $buyBalance = $expenditure->balance-$request->price;

        $data['balance'] = $buyBalance;
        $Payment->update(['balance'=>$buyBalance]);


        return Expenditure::create($data);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expenditure  $expenditure
     * @return \Illuminate\Http\Response
     */
    public function show(Expenditure $expenditure)
    {
       return $expenditure;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expenditure  $expenditure
     * @return \Illuminate\Http\Response
     */
    public function edit(Expenditure $expenditure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expenditure  $expenditure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expenditure $expenditure)
    {
        $data =  $request->all();

        $data['month'] = date('F',strtotime($request->date));
        $data['year'] = date('Y',strtotime($request->date));

        $expenditure->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expenditure  $expenditure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expenditure $expenditure)
    {
        //
    }

    public function cashbook_download(Request $request)
    {

        $unioun_name = $request->unioun_name;
        $month = $request->month;
        $year = $request->year;

        $month_name = $month;
        $month_number = "";

        for($i=1;$i<=12;$i++){
            if(strtolower(date("F", mktime(0, 0, 0, $i, 1, 0))) == strtolower($month_name)){
                $month_number = $i;
                break;
            }
        }

        $month_numberName =   $month_number-1;
      if($month_numberName==0){
        $month_numberName = 12;
      }
        // return $month_number;
         $monthName = date('F', mktime(0, 0, 0,$month_numberName , 10));

        $sells = [];
        $sell = [];
        $buys = [];
        $buy = [];


$paymentBalance= 0;
$buyBalance= 0;

       if(Payment::where(['month'=>$monthName])->count()>0){
          $paymentBalance= Payment::where(['month'=>$monthName])->latest()->first()->balance;
       }
       if(Expenditure::where(['month'=>$month])->count()>0){
         $buyBalance = Expenditure::where(['month'=>$month])->latest()->first()->balance;
       }


        $paymentTotalAmount = Payment::where(['month'=>$month])->sum('amount');
        $buyTotalAmount = Expenditure::where(['month'=>$month])->sum('price');
        $days = cal_days_in_month(CAL_GREGORIAN,$month_number,$year);
        for ($i=1; $i <=$days ; $i++) {





            $date = $year.'-'.str_pad($month_number, 2, '0', STR_PAD_LEFT).'-'.str_pad($i, 2, '0', STR_PAD_LEFT);


            $paymentDesc = Payment::where(['date'=>$date])->get();
            $descriptionSellarray = [];
            $descriptionSell = '';
            foreach ($paymentDesc as $value) {
                array_push($descriptionSellarray,$value->sonod_type);
            }
            $paymentAmount = Payment::where(['date'=>$date])->sum('amount');
            $PayItems = count(array_unique($descriptionSellarray));
            $Pi = 0;
            foreach (array_unique($descriptionSellarray) as $des) {

                if($des=='holdingtax'){

                    $descriptionSell.='হোল্ডিং ট্যাক্স,';
                }else{

                    $descriptionSell.=$des.',';
                }

            }


            ////////////////////////////////////////

            $buyDesc = Expenditure::where(['date'=>$date])->get();

            $descriptionBuy = '';
            foreach ($buyDesc as $list) {

                $descriptionBuy.=$list->description.',';
                // print_r($list);
            }
            $buyAmount = Expenditure::where(['date'=>$date])->sum('price');




            array_push($sell,
                [
                    'date'=>$date,
                    'description'=>$descriptionSell,
                    'price'=>$paymentAmount,
                    'Bdate'=>$date,
                    'Bdescription'=>$descriptionBuy,
                    'Bprice'=>$buyAmount,
                ]
            );





        }




        $payment = Payment::where(['union'=>$unioun_name,'month'=>$month,'year'=>$year])->get();
        $expenditure = Expenditure::where(['unioun_name'=>$unioun_name,'month'=>$month,'year'=>$year])->get();
        $uniouninfo = Uniouninfo::where('short_name_e', $unioun_name)->first();



        $sells = json_decode(json_encode($sell));
        // $buys = json_decode(json_encode($buy));








        $filename = time().".pdf";
        // return $this->pdfHeader($id,$filename);
        // $pdf = LaravelMpdf::loadView('utsonod', compact('row', 'sonod', 'uniouninfo'));
        // return $pdf->stream("$EnsonodName-$row->sonod_Id.pdf");
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font' => 'bangla',
            'mode' => 'utf-8',
            'format' => 'A4',
            'setAutoTopMargin' => 'stretch',
            'setAutoBottomMargin' => 'stretch'
        ]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetHTMLHeader($this->pdfHeader($uniouninfo));
        $mpdf->SetHTMLFooter($this->pdfFooter($uniouninfo));
        // $mpdf->SetHTMLHeader('Document Title|Center Text|{PAGENO}');
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'BI';
        $mpdf->defaultfooterline = 0;
        $mpdf->showWatermarkImage = true;
        // $mpdf->WriteHTML('<watermarkimage src="'.$watermark.'" alpha="0.1" size="80,80" />');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($this->pdfBody($sells,$paymentTotalAmount,$buyTotalAmount,$paymentBalance,$buyBalance));
        $mpdf->useSubstitutions = false;
        $mpdf->simpleTables = true;
        $mpdf->Output($filename, 'I');


        // return view('cashBook', compact('sells', 'buys','uniouninfo','paymentTotalAmount','buyTotalAmount'));

        // $pdf = LaravelMpdf::loadView('cashBook', compact('sells','uniouninfo','paymentTotalAmount','buyTotalAmount'));
        // return $pdf->stream(time().".pdf");

    }



    public function pdfHeader($uniouninfo)
    {
     return  $html = "


        <table width='100%' style='border-collapse: collapse;' border='0'>
        <tr>
            <td style='text-align: center;' width='20%'>

            </td>
            <td style='text-align: center;' width='20%'>
                <img width='70px' src='". base64('backend/bd-logo.png') ."'>
            </td>
            <td style='text-align: center;' width='20%'>
            </td>
        </tr>
        <tr style='margin-top:2px;margin-bottom:2px;'>
            <td>
            </td>
            <td style='text-align: center;' width='50%'>
                <p style='font-size:20px'>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
            </td>
            <td>
            </td>
        </tr>
        <tr style='margin-top:0px;margin-bottom:0px;'>
            <td>
            </td>
            <td style='margin-top:0px; margin-bottom:0px; text-align: center;'>
                <h1 style='color: #7230A0; margin: 0px; font-size: 28px'>$uniouninfo->full_name</h3>
            </td>
            <td>
            </td>
        </tr>
        <tr style='margin-top:2px;margin-bottom:2px;'>
            <td>
            </td>
            <td style='text-align: center; '>
                <p style='font-size:20px'>উপজেলা: $uniouninfo->thana, জেলা: $uniouninfo->district ।</p>
            </td>
            <td>
            </td>
        </tr>
    </table>

<table width='100%' style='margin-bottom:10px'>
    <tr>
        <td style='text-align:center;background:#60497A;color:white'>ক্যাশ বহি</td>

    </tr>
</table>

<table width='100%'>
    <tr>
        <td width='20%' style='text-align:right;background:#92D050'>ব্যাংক একাউন্ট নম্বর:</td>
        <td width='15%' style='text-align:left'>৬৪৭৯৬৪৬৩১৩</td>
        <td width='15%' style='text-align:right;background:#95B3D7'>অর্থ বছর:</td>
        <td width='15%' style='text-align:left'>২০২২-২০২৩</td>
        <td width='15%' style='text-align:right;background:#E6B8B7'>মাসের নাম:</td>
        <td width='15%' style='text-align:left'>সেপ্টেম্বর/২০২২</td>
    </tr>
</table>

        ";


    }


    public function pdfBody($sells,$paymentTotalAmount,$buyTotalAmount,$paymentBalance,$buyBalance)
    {
       $html = "

       <style>
       .td,
       .th {
           border: 1px solid #E26B0A;
           font-size: 15px;
       }
       table.table {
           width: 100%;
           border-collapse: collapse;
       }
   </style>
       <table class='table' width='100%'>
       <thead>
           <tr>
               <td class='th' width='10%'>তারিখ</td>
               <td class='th' width='29%'>প্রাপ্তির বিবরণ</td>
               <td class='th' width='10%'>টাকার পরিমাণ</td>
               <td class='th' width='2%' style='background: #002060;border-bottom:0px'></td>
               <td class='th' width='10%'>তারিখ</td>
               <td class='th' width='29%'>ব্যয়ের বিবরণ</td>
               <td class='th' width='10%'>টাকার পরিমাণ</td>
           </tr>
       </thead>
       <tbody>";


           foreach ($sells as $sell){
           $html .= "<tr>
               <td class='td'>".int_en_to_bn($sell->date)."</td>
               <td class='td'>". $sell->description ."</td>
               <td class='td'>". int_en_to_bn(round($sell->price,2)) ."</td>
               <td class='td' style='background: #002060;border-bottom:0px;border-top:0px'></td>
               <td class='td'>". int_en_to_bn($sell->Bdate) ."</td>
               <td class='td'>". $sell->Bdescription ."</td>
               <td class='td'>". int_en_to_bn(round($sell->Bprice,2)) ."</td>
           </tr>";
           }

           $html .= "
           <tr style='background: #E6B8B7'>
               <td class='td' colspan='2' style='text-align:right;'>চলতি মাসের মোট জমা</td>
               <td class='td'>". int_en_to_bn(round($paymentTotalAmount,2)) ."</td>
               <td class='td' style='background: #002060;border-bottom:0px;border-top:0px'></td>
               <td class='td' colspan='2' style='text-align:right;'>চলতি মাসের মোট ব্যয়</td>
               <td class='td'>". int_en_to_bn(round($buyTotalAmount,2)) ."</td>
           </tr>
           <tr style='background: #B8CCE4'>
               <td class='td' colspan='2' style='text-align:right;'>চলতি মাসের প্রারম্ভিক স্থিতি (বিগত মাসের
                   উদ্বৃত্ত জের)</td>
               <td class='td'>". int_en_to_bn(round($paymentBalance,2)) ."</td>
               <td class='td' style='background: #002060;border-bottom:0px;border-top:0px'></td>
               <td class='td' colspan='2' style='text-align:right;'>চলতি মাসের উদ্বৃত্ত স্থিতি (Balance)/দেনা</td>
               <td class='td'>". int_en_to_bn(round($buyBalance,2)) ."</td>
           </tr>
           <tr style='background: #92D050'>
               <td class='td' colspan='2' style='text-align:right;'>সর্বমোট</td>
               <td class='td'>". int_en_to_bn(round($paymentTotalAmount,2)+round($paymentBalance,2)) ."</td>
               <td class='td' style='background: #002060;border-top:0px'></td>
               <td class='td' colspan='2' style='text-align:right;'>সর্বমোট</td>
               <td class='td'>". int_en_to_bn(round($buyTotalAmount,2)+round($buyBalance,2)) ."</td>
           </tr>
       </tbody>
   </table>

       ";

    return $html;

    }


        public function pdfFooter($uniouninfo)
        {
           $html = "

           <table width='100%' style='border-collapse: collapse;margin-top:50px' border='0'>
        <tr>
            <td style='text-align: center;' width='40%'>
            </td>
            <td style='text-align: center; width: 200px;' width='30%'>
            </td>
            <td style='text-align: center;' width='40%'>";




                $C_color = '#7230A0';
                $C_size = '18px';
                $color = 'black';
                if($uniouninfo->short_name_e=='dhamor'){
                $C_color = '#5c1caa';
                $C_size = '20px';
                $color = '#5c1caa';
                }

               $html.=" <div class='signature text-center position-relative' style='color:{{ $color }}'>
                    <b><span style='color:$C_color;font-size:$C_size;'>$uniouninfo->c_name</span>
                        <br />
                    </b><span style='font-size:16px;'>চেয়ারম্যান</span><br />
                     $uniouninfo->full_name<br> $uniouninfo->thana , $uniouninfo->district ।
                </div>
            </td>
        </tr>
    </table>

           ";

           return $html;
        }



}
