<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>



</head>

<body style="font-family: 'bangla', sans-serif;">


    <div style="width:800px; padding:12px; border: 10px solid #787878">
        <div style="width:750px; padding:12px; border: 5px solid #11083a;position:relative;overflow: hidden; ">





            <table width="100%" style="border-collapse: collapse;" border="0">
                <tr>
                    <td style="text-align: center;" width="20%">

                            @php
                                $qrurl = url("/verification/sonod/$row->id?sonod_name=$sonod->enname&sonod_Id=$row->sonod_Id");
                            //  $qrurl = url("/verification/sonod/$row->id");
                                $qrcode = \QrCode::size(50)
                            ->format('svg')
                            ->generate($qrurl);
                            echo $output = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrcode);
                            @endphp
                                    <br/>
                                    <div class="signature text-center position-relative" style='font-size:11px'>
                                        সনদ নং: {{ int_en_to_bn($row->sonod_Id) }}  <br /> ইস্যুর তারিখ: {{ int_en_to_bn(date("d/m/Y", strtotime($row->created_at))) }}
                                    </div>

                                </div>
                    </td>
                    <td style="text-align: center;" width="20%">
                        <img width="50px" src="{{ base64('backend/bd-logo.png') }}">
                    </td>
                    <td style="text-align: center;" width="20%">

                    <img width="50px" src="{{ base64($row->image) }}">





               </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom:2px;">
                    <td>
                    </td>
                    <td style="text-align: center;" width="50%">
                        <p style="font-size:14px">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার </p>
                         {{-- @if($row->unioun_name!='gognagar')
                            <p style="font-size:25px">চেয়ারম্যানের কার্যালয়</p>
                        @endif --}}

                    </td>
                    <td>
                    </td>
                </tr>
                <tr style="margin-top:0px;margin-bottom:0px;">
                    <td>
                    </td>
                    <td style="margin-top:0px; margin-bottom:0px; text-align: center;" width=50%>
                        <h1 style="color: #7230A0; margin: 0px; font-size: 18px">{{ $uniouninfo->full_name }}</h3>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr style="margin-top:2px;margin-bottom:2px;">
                    <td>
                    </td>
                    <td style="text-align: center; " width="50%">

                        <p style="font-size:13px">উপজেলা:  {{ $uniouninfo->thana }}, জেলা:  {{ $uniouninfo->district }} ।</p>
                    </td>
                    <td>
                    </td>
                </tr>


                @php
                          if($row->unioun_name=='gognagar'){
        echo $website = '     <tr style="margin-top:2px;margin-bottom:2px;">
        <td>
        </td>
        <td style="text-align: center; " width="50%">
            <p style="font-size:11px">ওয়েবসাইটঃ https://gognagarup.narayanganj.gov.bd</p>
            <p style="font-size:11px">ই-মেইলঃ '. $row->c_email.'</p>
        </td>
        <td>
        </td>
    </tr>';
        }else{
           echo  $website = '';
        }
                @endphp

</table>


<div class="nagorik_sonod" style="margin-bottom:10px;">
    <?php


    $width = '200px';
    $fontsize = '15px';
           ?>
    <div
        style="
        background-color: #159513;
        color: #fff;
        font-size: {{ $fontsize }};
        border-radius: 30em;
        width:{{ $width }};
        margin:14px auto;
        text-align:center;
        padding:3px 0;
        ">

{{ changeSonodName($row->sonod_name) }} </div>

                        </div>


                        <?php
    //                     $data = '
    //    তাকে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তারবিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।<br><br>&nbsp; &nbsp; &nbsp; আমি তার ভবিষ্যৎ জীবনের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করি।

    //                     ';
    //                echo    $encode =  json_encode($data);
    // echo $sonod->template;
    // echo json_decode($sonod->template);
                        ?>
{!! sonodView_Inheritance_certificate($row->id) !!}

@php
// echo $row->unioun_name;


$C_color = '#7230A0';
$C_size = '14px';
$color = 'black';
$style = '';
$w_list = $row->successor_list;
    $w_list = json_decode($w_list);

$margin_top = 300 - (count($w_list) * 15);
$marginTop = "margin-top:$margin_top";



@endphp

<table width="100%" style="border-collapse: collapse;{{ $marginTop }}" border="0">
                    <tr>
                        <td style="text-align: center;vertical-align: bottom;"  width="40%">


                        <div class="signature text-center position-relative" style="color:black;font-size:12px;">
                            <br/>
                             <span style="font-size:12px;">ইউপি সদস্য/সদস্যা</span><br />
                             {{ $uniouninfo->full_name }}<br> {{ $uniouninfo->thana }}, {{ $uniouninfo->district }} ।
                         <br>

                         </div>


                        </td>
                        <td style="text-align: center; width: 200px;" width="30%">
                            <img width="100px" src="{{ base64($uniouninfo->sonod_logo) }}">
                        </td>
                        <td style="text-align: center;" width="40%">






                            <div class="signature text-center position-relative" style="color:{{ $color }};font-size:12px;">
                                <img width="170px" style="{{ $style }}"  src="{{ base64($row->chaireman_sign) }}"><br/>
                                <b><span style="color:{{ $C_color }};font-size:{{ $C_size }};">{{ $row->chaireman_name }}</span> <br />
                                        </b><span style="font-size:12px;">{{ $row->chaireman_type }}</span><br />

                                {{ $uniouninfo->full_name }}<br> {{ $uniouninfo->thana }}, {{ $uniouninfo->district }} ।
                            <br>
                            @if($row->unioun_name!='gognagar')
                            {{ $row->c_email }}
                            @endif
                            </div>





                        </td>
                    </tr>

                </table>
                  <p style="background: #787878;
  color: white;
  text-align: center;
  padding: 2px 2px;font-size: 12px;     margin-top: 0px;margin:0" class="m-0">"সময়মত ইউনিয়ন কর পরিশোধ করুন। ইউনিয়নের উন্নয়নমূলক কাজে সহায়তা করুন"</p>
                  <p class="m-0" style="font-size:12px;text-align:center;margin:0;position: fixed;
                  bottom: 0;">ইস্যুকৃত সনদটি যাচাই করতে QR কোড স্ক্যান করুন অথবা ভিজিট করুন {{ $uniouninfo->domain }}</p>
            </div>
        </div>
    </div>




</body>

</html>
