<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>



</head>

<body style="font-family: 'bangla', sans-serif;">


    <div style="width:800px; padding:20px; border: 10px solid #787878">
        <div style="width:750px;  padding:20px; border: 5px solid #11083a;position:relative;overflow: hidden; ">

            <table width="100%" style="border-collapse: collapse;" border="0">
                <tr>
                    <td style="text-align: center;" width="20%">
                    <span style="color:#b400ff;"><b>
                    উন্নয়নের গণতন্ত্র,  <br /> শেখ হাসিনার মূলমন্ত্র </b>
                    </span>
                    </td>
                    <td style="text-align: center;" width="20%">
                        <img width="70px" src="{{ base64('backend/bd-logo.png') }}">
                    </td>
                    <td style="text-align: center;" width="20%">

                    <img width="100px" src="{{ base64($row->image) }}">





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
                        <h1 style="color: #7230A0; margin: 0px; font-size: 28px">{{ $uniouninfo->full_name }}</h3>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom:2px;">
                    <td>
                    </td>
                    <td style="text-align: center; " width="50%">
                        <p style="font-size:20px">উপজেলা:  {{ $uniouninfo->thana }}, জেলা:  {{ $uniouninfo->district }} ।</p>
                    </td>
                    <td>
                    </td>
                </tr>
</table>


<div class="nagorik_sonod" style="margin-bottom:10px;">
    <?php
    $string = "টিপ বোঝে না, টোপ বোঝে না টিপ বোঝে না, কেমন বাপু লোক";
     strlen('ভোটার এলাকা স্থানান্তর');
     $namelength =  strlen($row->sonod_name);
    $width = '300px';
    $fontsize = '30px';
    if($namelength>=100){
        $width = '400px';
        $fontsize = '20px';

    }elseif($namelength>=85){
        $width = '500px';
        $fontsize = '22px';
    }elseif($namelength>=75){
        $width = '450px';
        $fontsize = '25px';
    }elseif($namelength>=65){
        $width = '400px';
        $fontsize = '27px';
    }
           ?>
    <div
        style="
        background-color: #159513;
        color: #fff;
        font-size: {{ $fontsize }};
        border-radius: 30em;
        width:{{ $width }};
        margin:20px auto;
        text-align:center;
        padding:5px 0;
        ">

{{ $row->sonod_name }} </div>

                        </div>


                        <?php
    //                     $data = '
    //    তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তারবিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।<br><br>&nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।

    //                     ';
    //                echo    $encode =  json_encode($data);
    // echo $sonod->template;
    // echo json_decode($sonod->template);
                        ?>
{{ sonodView($row->id) }}



<table width="100%" style="border-collapse: collapse;" border="0">
                    <tr>
                        <td  style="text-align: center;" width="40%">
                   <div class="signature text-center position-relative">
                    @php
                     $qrurl = url("/verification/sonod/$row->id");
                        $qrcode = \QrCode::size(70)
                    ->format('svg')
                    ->generate($qrurl);

                   echo $output = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrcode);
                    @endphp


                           <br/>
                            <div class="signature text-center position-relative">
                                সনদ নং: {{ int_en_to_bn($row->sonod_Id) }} <br /> ইস্যুর তারিখ: {{ int_en_to_bn(date("d/m/Y", strtotime($row->created_at))) }} </div>

                          </div>
                        </td>
                        <td style="text-align: center; width: 200px;" width="30%">
                            <img width="100px" src="{{ base64($uniouninfo->sonod_logo) }}">
                        </td>
                        <td style="text-align: center;" width="40%">
                            <div class="signature text-center position-relative">
                                <img width="170px"  src="{{ base64($row->chaireman_sign) }}"><br/>
                                <b><span style="color:#7230A0;font-size:18px;">{{ $row->chaireman_name }}</span> <br />
                                        </b><span style="font-size:16px;">চেয়ারম্যান</span><br />

                                {{ $uniouninfo->full_name }}<br> {{ $uniouninfo->thana }}, {{ $uniouninfo->district }} ।</div>
                        </td>
                    </tr>

                </table>
                  <p style="background: #787878;
  color: white;
  text-align: center;
  padding: 2px 2px;font-size: 16px;     margin-top: 0px;" class="m-0">"সময়মত ইউনিয়ন কর পরিশোধ করুন। ইউনিয়নের উন্নয়নমূলক কাজে সহায়তা করুন"</p>
                  <p class="m-0" style="font-size:14px;text-align:center">ইস্যুকৃত সনদটি যাচাই করতে QR কোড স্ক্যান করুন অথবা ভিজিট করুন {{ $uniouninfo->domain }}</p>
            </div>
        </div>
    </div>




</body>

</html>
