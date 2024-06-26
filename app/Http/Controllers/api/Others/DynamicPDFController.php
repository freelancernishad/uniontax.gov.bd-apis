<?php

namespace App\Http\Controllers\API\Others;

use App\Http\Controllers\Controller;

use App\Models\Uniouninfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use QrCode;

class DynamicPDFController extends Controller
{
    function viewpdf($id)
    {
        $data['result'] = DB::table('citizen_services')
            ->where('id', $id)
            ->get();
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('https://softlabltd.com/invoice/' . $id));
        //    $pdf = PDF::loadView('dynamic_pdf', compact('qrcode'),$data);
        return view('dynamic_pdf', compact('qrcode'), $data);
        /*
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($this->convert_customer_data_to_html($id,$qrcode));
     return $pdf->stream(); */
    }
    function pdf($Sname, $id)
    {


        ini_set('max_execution_time', 180);

        $wheredata = [
            'id' => $id,
            'stutus' => 'approved',
            'payment_status' => 'Paid',
        ];

            if ($Sname == 'trade_license_apps') {
                $userCount =  DB::table('trade_licenses')->where($wheredata)->count();
            } else if ($Sname == 'successor_apps') {
                $userCount =  DB::table('successors')->where($wheredata)->count();
            } else if ($Sname == 'familly_apps') {
                $userCount =  DB::table('famillys')->where($wheredata)->count();
            } else if ($Sname == 'onapotti') {
                $userCount =  DB::table('onapottis')->where($wheredata)->count();
            } else {
                $userCount =  DB::table('citizen_services')->where($wheredata)->count();
            }


            if ($userCount == 0) {
                return view('errors/404');
                // return redirect('/error');
            }


            if ($Sname == 'trade_license_apps') {
                $userInfo =  DB::table('trade_licenses')->where($wheredata)->first();
            } else if ($Sname == 'successor_apps') {
                $userInfo =  DB::table('successors')->where($wheredata)->first();
            } else if ($Sname == 'familly_apps') {
                $userInfo =  DB::table('famillys')->where($wheredata)->first();
            } else if ($Sname == 'onapotti') {
                $userInfo =  DB::table('onapottis')->where($wheredata)->first();
            } else {
                $userInfo =  DB::table('citizen_services')->where($wheredata)->first();
            }

 $unioun_name = $userInfo->unioun_name;

//  return $this->pdfHTML($Sname, $id,$unioun_name,$userInfo);
                $mpdf = new \Mpdf\Mpdf([
                    'default_font_size' => 12,
                    'default_font' => 'bangla',
                ]);
                $mpdf->SetWatermarkText('');
                $mpdf->showWatermarkText = true;
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML($this->pdfHTML($Sname, $id,$unioun_name,$userInfo));
                $mpdf->Output();


    }
    public function pdfHTML($Sname, $id,$uniounName,$userInfo)
    {
        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "5000000000000000050000000000000000");
        ini_set('memory_limit', '12008M');

        $uniounInfo =  Uniouninfo::where('short_name_e', $uniounName)->first();

        if ($Sname == 'trade_license_apps') {
            $filepath = 'oldData/trade_license/';
        } else {
            $filepath = 'oldData/citizen_services/';
        }
        if ($Sname == 'trade_license_apps') {
            $filepath = 'oldData/trade_license/';
        } else if ($Sname == 'successor_apps') {
            $filepath = 'oldData/successor/';
        } else if ($Sname == 'familly_apps') {
        } else if ($Sname == 'onapotti') {
        } else {
            $filepath = 'oldData/citizen_services/';
        }
        $orgDate = $userInfo->updated_at;
         $newDate = date("d F Y", strtotime($orgDate));

        // $newDateMonth = 'May';
        $newDateMonth = date("F", strtotime($orgDate));
        $newDateYear = (int)date("Y", strtotime($orgDate));

        $monthNumber = month_to_number($newDateMonth);
        if($monthNumber>6){
            $nextYear = $newDateYear+1;
            $ortoBotsor = int_en_to_bn($newDateYear.'-'.$nextYear);
        }else{
            $preYear = $newDateYear-1;
            $ortoBotsor = int_en_to_bn($preYear .'-'.$newDateYear);

        }

        // return $ortoBotsor;


        //in Controller

         $logos = base64('oldData/c_signture/' . $userInfo->chaireman_sign);
         $govlogo = base64('assets/img/bd-logo.png');
         $logo = base64($uniounInfo->sonod_logo);





        if ($Sname == 'familly_apps') {
            $logoPofile = '';
        } else if ($Sname == 'onapotti') {
            $logoPofile = '';
        } else if ($Sname == 'successor_apps') {
            $logoPofile = '';
        } else {

            // $pathPofile = $filepath . $userInfo->file;
            // $typePofile = pathinfo($pathPofile, PATHINFO_EXTENSION);
            // $dataiPofile = file_get_contents($pathPofile);
            // $logoPofile = 'data:image/' . $typePofile . ';base64,' . base64_encode($dataiPofile);

            $logoPofile = base64($filepath . $userInfo->file);
        }
        //in Controller
        $qrUrl = url("/pdf/download/$Sname/$id");
        $qrcode = \QrCode::size(70)
            ->format('svg')
            ->generate($qrUrl);
        /*
================================================================================================================
                                                citizen_apps1
================================================================================================================
*/
        if ($Sname == 'citizen_apps1') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                <div style="
                background-color: #11083a;
                color: #fff;
                font-size: 30px;
                border-radius: 30em;
                width:300px;
                margin:20px auto;
                text-align:center
                ">জাতীয়তা/নাগরিকত্ব সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।<br>
                    <br>
                    &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                trade_license_apps
================================================================================================================
*/ else if ($Sname == 'trade_license_apps') {
            $pdfHead = 'ইউপি ফরম-১৩';



            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                <div style="
                background-color: #11083a;
                color: #fff;
                font-size: 40px;
                border-radius: 30em;
                width:250px;
                margin:20px auto;
                text-align:center
                ">ট্রেড লাইসেন্স</div> <br>
               <p style="text-align:center; margin-top:-25px;font-size:20px">
               অর্থ বছর: '.$ortoBotsor.'
               </p>
                ';
            $nagoriinfo = '
            <table width="100%" style="margin-top:-20px">
            <tr>
                <td width="30%">লাইসেন্স নং</td><td>: ' . $uniounInfo->u_code . '2' . $userInfo->sonod_no . '</td>
                </tr>
                <tr>
                <td width="30%">প্রতিষ্ঠানের নাম</td><td>: ' . $userInfo->bcomname . '</td>
                </tr>
                <tr>
                <td width="30%">লাইসেন্সধারীর নাম</td><td>: ' . $userInfo->bwname . '</td>
                </tr>
                <tr>
                <td width="30%">পিতা/স্বামীর নাম</td><td>: ' . $userInfo->bfname . '</td>
                </tr>
                <tr>
                <td width="30%">মাতার নাম</td><td>: ' . $userInfo->bmname . '</td>
                </tr>
                <tr>
                <td width="30%">জাতীয় পরিচয়পত্র নং</td><td>: ' . $userInfo->Nid . '</td>
                </tr>
                <tr>
                <td width="30%">ঠিকানা</td><td>: ' . $userInfo->bb_gram . ', ' . $userInfo->bb_postof . ', ' . $userInfo->bb_thana . ', ' . $userInfo->bb_dis . '</td>
                </tr>
                <tr>
                <td width="30%">পেশার ধরন</td><td>: ' . $userInfo->business_type . '</td>
                </tr>
            </table>
            <p style="margin-bottom: 30px;"
            >৩০ জুন, ২০২২ তারিখ পর্যন্ত বৈধ ফি প্রদানের পরিমাণ টাকা '.$userInfo->amounta .' কথায়:'.$userInfo->amountk .'  প্রাপ্ত হয়ে তার ব্যবসা/বৃত্তি/পেশা চালিয়ে যাওয়ার জন্য এই লাইসেন্স প্রদান করা হলো।<br>
            </p>
         ';
            $sonodNO = '<div class="signature text-center position-relative">
         ইস্যুর তারিখ: ' . $newDate . '</div>';
            $lNO = '';
        }
        /*
================================================================================================================
                                                successor_apps
================================================================================================================
*/ else if ($Sname == 'successor_apps') {
            $w_list = $userInfo->w_list;
            $w_list = json_decode($w_list);
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                <div style="
                background-color: #11083a;
                color: #fff;
                font-size: 30px;
                border-radius: 30em;
                width:200px;
                margin:10px auto;
                margin-bottom:0px;
                text-align:center
                ">ওয়ারিশান সনদপত্র</div> <br>
                ';
            $nagoriinfo = '
            <p style="margin-top:0px;margin-bottom:5px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, মরহুম ' . $userInfo->bname . ', পিতা- ' . $userInfo->bfname . ', মাতা- ' . $userInfo->bmane . ',  হোল্ডিং নং ' . $userInfo->holding_no . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', গ্রাম- ' . $userInfo->pb_gram . ', ডাকঘর- ' . $userInfo->pb_postof . ', উপজেলা: তেঁতুলিয়া, জেলা- পঞ্চগড়। তিনি অত্র ইউনিয়নের একজন স্থায়ী বাসিন্দা এবং জন্ম সূত্রে বাংলাদেশের নাগরিক ছিলেন। তিনি মৃত্যুকালে নিম্নলিখিত উত্তরাধিকারী রাখিয়া মৃত্যুবরণ করেন।<br>
                </p>
<p style="text-align:center;margin-bottom:0px">ওয়ারিশ গনের নামের তালিকা</p>
<table class="table " style="width:100%;" cellspacing="0" cellpadding="0" border="1" >
<tr>
  <th width="10%">ক্রমিক নং</th>
  <th width="40%">ওয়ারিশান গনের নাম</th>
  <th width="10%">সম্পর্ক</th>
  <th width="10%">বয়স</th>
  <th width="20%">মন্তব্য</th>
</tr>';
            $i = 1;
            foreach ($w_list as $rowList) {
                if ($i == 1) {
                    $c = '১';
                }
                if ($i == 2) {
                    $c = '২';
                }
                if ($i == 3) {
                    $c = '৩';
                }
                if ($i == 4) {
                    $c = '৪';
                }
                if ($i == 5) {
                    $c = '৫';
                }
                if ($i == 6) {
                    $c = '৬';
                }
                if ($i == 7) {
                    $c = '৭';
                }
                if ($i == 8) {
                    $c = '৮';
                }
                if ($i == 9) {
                    $c = '৯';
                }
                if ($i == 10) {
                    $c = '১০';
                }
                $nagoriinfo .= '
    <tr>
      <td style="text-align:center">' . $c . '</td>
      <td style="text-align:center">' . $rowList->w_name . '</td>
      <td style="text-align:center">' . $rowList->w_relation . '</td>
      <td style="text-align:center">' . $rowList->w_age . '</td>
      <td style="text-align:center">' . $rowList->monttobbo . '</td>
    </tr>';
                $i++;
            }
            $nagoriinfo .= '
</table>
<br>
<p style="margin-top:-10px;margin-bottom:5px">
উল্লেখিত মরহুম ব্যক্তির বর্ণিত ........ জন উত্তরাধিকারী ছাড়া, আর কোন উত্তরাধিকারী নাই। আমি নিম্নস্বাক্ষরকারী ও সংশ্লিষ্ট ওয়ার্ডের ইউপি সদস্যদের দ্বারা সত্যায়নপূর্বক অত্র ওয়ারিশান সনদপত্র প্রদান করা হলো।
</p>
<p style="margin-top:-10px; margin-bottom:0px">
আমি মরহুমের বিদেহী আত্মার শান্তি এবং উত্তরাধিকারীগণের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করছি।
</p>
';
            $sonodNO = '<div class="signature text-center position-relative">
            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br />  ইস্যুর তারিখ: ' . $newDate . '</div>';
            $lNO = '';
        }
        /*
================================================================================================================
                                                citizen_apps3
================================================================================================================
*/ else if ($Sname == 'citizen_apps3') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:200px;
                            margin:20px auto;
                            text-align:center
                            ">চারিত্রিক সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps4
================================================================================================================
*/ else if ($Sname == 'citizen_apps4') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:200px;
                            margin:20px auto;
                            text-align:center
                            ">অবিবাহিত সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . ' ।
            তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে সে অবিবাহিত এবং তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps5
================================================================================================================
*/ else if ($Sname == 'citizen_apps5') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:200px;
                            margin:20px auto;
                            text-align:center
                            ">ভূমিহীন সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps6
================================================================================================================
*/ else if ($Sname == 'citizen_apps6') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:300px;
                            margin:20px auto;
                            text-align:center
                            ">পুনঃ বিবাহ না হওয়া সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তিনি তাহার স্বামীর মৃত্যুবরন করার পর অন্যত্র পুনঃ বিবাহ বন্ধনে আবদ্ধ না হয়ে স্বামীর বসত-ভিটায় সন্ততানদের নিয়ে শান্তিপূর্ণ ভাবে বসবাস করিতেছেন । <br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps7
================================================================================================================
*/ else if ($Sname == 'citizen_apps7') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:320px;
                            margin:20px auto;
                            text-align:center
                            ">বার্ষিক আয়ের প্রত্যয়ন/সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে ..... এর বার্ষিক আয় ..... (কথাই) টাকা মাত্র ।<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps8
================================================================================================================
*/ else if ($Sname == 'citizen_apps8') {


$namedata = $userInfo->bname;

$namedata = explode(",", $namedata);

            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:300px;
                            margin:20px auto;
                            text-align:center
                            ">একই নামের প্রত্যয়ন/সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $namedata . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। উল্লেখ থাকে যে '.$namedata.' ও '.$namedata[1].' নামধারী একই ব্যাক্তি ।<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps9
================================================================================================================
*/ else if ($Sname == 'citizen_apps9') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:200px;
                            margin:20px auto;
                            text-align:center
                            ">প্রতিবন্ধী সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। ..........<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                citizen_apps11
================================================================================================================
*/ else if ($Sname == 'citizen_apps11') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                            <div style="
                            background-color: #11083a;
                            color: #fff;
                            font-size: 30px;
                            border-radius: 30em;
                            width:200px;
                            margin:20px auto;
                            text-align:center
                            ">চারিত্রিক সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $userInfo->bname . ', পিতা/স্বামী: ' . $userInfo->bfname . ', ওয়ার্ড নং- ' . $userInfo->pb_wordno . ', হোল্ডিং নং- ' . $userInfo->holding_no . ', গ্রাম: ' . $userInfo->pb_gram . ',  ডাকঘর: ' . $userInfo->pb_postof . ',  উপজেলা: ' . $userInfo->pb_thana . ' , জেলা: ' . $userInfo->pb_dis . '।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                                <br>
                                &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                            </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                            সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                                onapotti
================================================================================================================
*/ else if ($Sname == 'onapotti') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                    <div style="
                    background-color: #11083a;
                    color: #fff;
                    font-size: 30px;
                    border-radius: 30em;
                    width:200px;
                    margin:20px auto;
                    text-align:center
                    ">অনাপত্তি সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ........., পিতা/স্বামী: ........., ওয়ার্ড নং- ........., হোল্ডিং নং- ........., গ্রাম: .........,  ডাকঘর: .........,  উপজেলা: ......... , জেলা: .........।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                        <br>
                        &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                    </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                    সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                        familly_apps
================================================================================================================
*/ else if ($Sname == 'familly_apps') {
            $pdfHead = '';
            $ssName = '  <div class="nagorik_sonod" style="margin-bottom:10px;">
                    <div style="
                    background-color: #11083a;
                    color: #fff;
                    font-size: 30px;
                    border-radius: 30em;
                    width:200px;
                    margin:20px auto;
                    text-align:center
                    ">পারবারিক সনদপত্র</div>';
            $nagoriinfo = '<p style="margin-top:15px;margin-bottom:15px;font-size:15px;text-align:justify">&nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ........., পিতা/স্বামী: ........., ওয়ার্ড নং- ........., হোল্ডিং নং- ........., গ্রাম: .........,  ডাকঘর: .........,  উপজেলা: ......... , জেলা: .........।  তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র/আচরণ প্রশংসনীয়<br>
                        <br>
                        &nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।
                    </p>';
            $lNO = '';
            $sonodNO = ' <div class="signature text-center position-relative">
                    সনদ নং: ' . $uniounInfo->u_code . '1' . $userInfo->sonod_no . ' <br /> ইস্যুর তারিখ: ' . $newDate . '</div>';
        }
        /*
================================================================================================================
                                       end
================================================================================================================
*/
        $output = ' <!DOCTYPE html>
  <html>
  <head>
      <title></title>
  </head>
  <body>
      <div style="width:800px; padding:20px; border: 10px solid #787878">
          <div style="width:750px;  padding:20px; border: 5px solid #11083a;position:relative;overflow: hidden; ">
          ' . $pdfHead . '
              <table width="100%" style="border-collapse: collapse;" border="0">
                  <tr>
                      <td style="text-align: center;" width="20%">
                      </td>
                      <td style="text-align: center;" width="20%">
                          <img width="70px" src="' . $govlogo . '">
                      </td>
                      <td style="text-align: center;" width="20%">
                      <img width="100px" src="' . $logoPofile . '">
                      </td>
                  </tr>
                  <tr style="margin-top:2px;margin-bottom:2px;">
                      <td>
                      </td>
                      <td style="text-align: center;" width="50%">
                          <p style="font-size:20px">গণপ্রজাতন্ত্রী বাংলাদেশ</p>
                      </td>
                      <td>
                      </td>
                  </tr>
                  <tr style="margin-top:0px;margin-bottom:0px;">
                      <td>
                      </td>
                      <td style="margin-top:0px; margin-bottom:0px; text-align: center;" width=50%>
                          <h1 style="color: #7230A0; margin: 0px; font-size: 28px">' . $uniounInfo->full_name . '</h3>
                      </td>
                      <td>
                      </td>
                  </tr>
                  <tr style="margin-top:2px;margin-bottom:2px;">
                      <td>
                      </td>
                      <td style="text-align: center; " width="50%">
                          <p style="font-size:20px">উপজেলা: ' . $uniounInfo->thana . ', জেলা: ' . $uniounInfo->district . ' ।</p>
                      </td>
                      <td>
                      </td>
                  </tr>
  </table>
                ' . $ssName . '
                ' . $lNO . '
                          </div>
' . $nagoriinfo . '
<table width="100%" style="border-collapse: collapse;" border="0">
                      <tr>
                          <td  style="text-align: center;" width="40%">
	                 <div class="signature text-center position-relative">
                              ' . $qrcode . '<br/>
                            </div>
                          </td>
                          <td style="text-align: center; width: 200px;" width="30%">
                              <img width="100px" src="' . $logo . '">
                          </td>
                          <td style="text-align: center;" width="40%">
                              <div class="signature text-center position-relative">
                              <img width="170px"  src="' . $logos . '"><br/>
                              <b>কাজী আনিসুর রহমান <br /> চেয়ারম্যান
                                      <br /></b> ' . $uniounInfo->full_name . ' <br> ' . $uniounInfo->thana . ', ' . $uniounInfo->district . ' ।</div>
                          </td>
                      </tr>
					  <tr>
                          <td  style="text-align: center;" width="40%">
	                ' . $sonodNO . '
                          </td>
                          <td style="text-align: center; width: 200px;" width="30%">
                          </td>
                          <td style="text-align: center;" width="40%">
                          </td>
                      </tr>
                  </table>
                    <p style="background: #787878;
    color: white;
    text-align: center;
    padding: 2px 2px;font-size: 16px;     margin-top: 20px;" class="m-0">"সময়মত ইউনিয়ন কর পরিশোধ করুন। ইউনিয়নের উন্নয়নমূক কাজে সহায়তা করুন"</p>
                    <p class="m-0" style="font-size:14px;text-align:center">ইস্যুকৃত সনদটি যাচাই করতে QR কোড স্ক্যান করুন অথবা ভিজিট করুন www.upshebatetulia.gov.bd</p>
              </div>
          </div>
      </div>
  </body>
  </html>';
        $output = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $output);
        return $output;
    }
    function convert_customer_data_to_html($id)
    {
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('https://softlabltd.com/invoice/' . $id));
        $data['result'] = DB::table('citizen_services')
            ->where('id', $id)
            ->get();
        return view('dynamic_pdf', $data, compact('qrcode'));
    }










public function pdfC()
{
    $data = [
        'foo' => 'bar'
    ];
    $pdf = PDF::loadView('pdf', $data);
    return $pdf->stream('document.pdf');
}






}
