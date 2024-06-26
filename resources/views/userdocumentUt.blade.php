<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

<style>
    td{
        font-size:14px;
    }
</style>

</head>

<body style="font-family: 'bangla', sans-serif;">

    <div class="pdfhead" style="text-align:center">
    <div style=":center;"  >
        @php
            // print_r($uniouninfo);
        @endphp
        <img width="70px" src="{{ base64($uniouninfo->sonod_logo) }}" />
    </div>

    <div style="width:300px;margin:0 auto;" ><p style="margin-bottom:0 !important;font-size:16px">  গণপ্রজাতন্ত্রী বাংলাদেশ
        <h2 style="    margin: 0;">{{ $uniouninfo->full_name }}</h2>
        উপজেলা:  {{ $uniouninfo->thana }}, জেলা:  {{ $uniouninfo->district }} ।

    </p></div>

    <p style="text-align:center" >   <div style="margin-bottom: 0px !important;
        font-size: 35px;
        color: white;
        background: green;
        padding: 13px 39px;
        border-radius: 14px;width: 200px;
    margin: 0 auto;" >অভিনন্দন !</div>  <br> <p style="font:size:16px;color:blue;margin-bottom:0px !important;">ডিজিটাল ইউনিয়ন ট্যাক্স ও সেবা সিস্টেমে আপনার আবেদনটি যথাযথভাবে দাখিল হয়েছে।</p>

    </div>


                <table style="margin-top:20px">

                    <tr>
                        <td>সেবার ধরণ</td>
                        <td>:</td>
                        <td>{{ changeSonodName($row->sonod_name) }}</td>
                    </tr>

                    <tr>
                        <td>আবেদনের ক্রমিক নং</td>
                        <td>:</td>
                        <td>{{ int_en_to_bn($row->sonod_Id) }}</td>
                    </tr>
                    <tr>
                        <td>আবেদনকারীর নাম</td>
                        <td>:</td>
                        <td>{{ int_en_to_bn($row->applicant_name) }}</td>
                    </tr>

                    <tr>
                        <td>আবেদনের তারিখ</td>
                        <td>:</td>
                        <td>{{ int_en_to_bn(date("d/m/Y", strtotime($row->created_at))) }}</td>
                    </tr>


                    <tr>
                        @if($row->sonod_name=='উত্তরাধিকারী সনদ')
                        <td>ব্যাক্তির নাম</td>
                        @else
                        <td>মৃত ব্যাক্তির নাম</td>
                        @endif
                        <td>:</td>
                        <td>{{ $row->utname }}</td>
                    </tr>

                    <tr>

                        @if($row->sonod_name=='উত্তরাধিকারী সনদ')

                        <td>ব্যাক্তির ঠিকানা</td>
                        @else
                        <td>মৃত ব্যাক্তির ঠিকানা</td>
                        @endif


                        <td>:</td>
                        <td>গ্রামঃ {{ $row->ut_grame }} , ডাকঘরঃ {{ $row->ut_post }}, উপজেলাঃ {{ $row->ut_thana }}, জেলাঃ {{ $row->ut_district }} </td>
                    </tr>



                    {{-- <tr>
                        <td>আবেদনকারীর নাম</td>
                        <td>:</td>
                        <td>{{ $row->applicant_name }}</td>
                    </tr>
                    @if ($row->sonod_name=='ওয়ারিশ সনদ')
                    <tr>
                        <td>মৃত ব্যাক্তির নাম</td>
                        <td>:</td>
                        <td>{{ $row->utname }}</td>
                    </tr>
                    @elseif ($row->sonod_name=='উত্তরাধিকারী সনদ')
                    <tr>
                        <td>জীবিত ব্যক্তির নাম</td>
                        <td>:</td>
                        <td>{{ $row->utname }}</td>
                    </tr>
                    @endif

                    <tr>
                        <td>এনআইডি নং</td>
                        <td>:</td>
                        <td>{{ int_en_to_bn($row->applicant_national_id_number) }}</td>
                    </tr>

                    <tr>
                        <td>পিতা/স্বামীর নাম</td>
                        <td>:</td>
                        <td>{{ $row->applicant_father_name }}</td>
                    </tr>

                    <tr>
                        <td>বর্তমান ঠিকানা</td>
                        <td>:</td>
                        <td>হোল্ডিং নং- {{ $row->applicant_holding_tax_number }}, গ্রাম: {{ $row->applicant_present_village }}, ডাকঘর: {{ $row->applicant_present_post_office }}, উপজেলা: {{ $row->applicant_present_Upazila }} , জেলা: {{ $row->applicant_present_district }}</td>
                    </tr>

                    <tr>
                        <td>মোবাইল নম্বর</td>
                        <td>:</td>
                        <td>{{ int_en_to_bn($row->applicant_mobile) }}</td>
                    </tr> --}}

                </table>
<style>
    .w_table tr,.w_table th,.w_table td{
        border: 1px solid;
        text-align:center;
    }

</style>
            @if($row->sonod_name=='উত্তরাধিকারী সনদ')
            <h3 style="text-align:center">উত্তরাধিকারীগনের নাম ও সম্পর্ক</h3>
            @else
            <h3 style="text-align:center">ওয়ারিশগনের নাম ও সম্পর্ক</h3>

            @endif

                <table class="w_table" width="100%" border="1px" style="border-collapse: collapse; border: 1px solid;">
                    <tr >
                        <th width="5%">ক্রমিক নং</th>
                        <th width="20%">নাম</th>
                        <th width="15%">সম্পর্ক</th>
                        <th width="20%">জন্ম তারিখ</th>
                        <th width="20%">জাতীয় পরিচয়পত্র নাম্বার</th>
                        <th width="20%">মন্তব্য</th>
                    </tr>
                    @php
                        $i = 1;
                    @endphp
                    @foreach(json_decode($row->successor_list) as $value)


                    <tr>
                        <td>{{ int_en_to_bn($i) }}</td>

                        <td>{{ $value->w_name }}</td>
                        <td>{{ $value->w_relation }}</td>
                        <td>{{ int_en_to_bn($value->w_age) }}</td>
                        <td>{{ int_en_to_bn($value->w_nid) }}</td>
                        <td></td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </table>


<br>
	আবেদনকারীর স্বাক্ষর :-<br>
{{--
	<br>
	আবেদনকারীর নাম: {{ $row->applicant_name }}<br>
	বর্তমান ঠিকানা: হোল্ডিং নং- {{ int_en_to_bn($row->applicant_holding_tax_number) }} , গ্রাম:{{ $row->applicant_present_village }} , ডাকঘর: {{ $row->applicant_present_post_office }} , উপজেলা: {{ $row->applicant_present_Upazila }} , জেলা: {{ $row->applicant_present_district }} । <br>
		মোবাইল নম্বর	: {{ int_en_to_bn($row->applicant_mobile) }} <br> --}}




    <p style="text-align:center" >শীগ্রই আপনার আবেদনটি কর্তৃপক্ষ কর্তৃক যথাযথ প্রক্রিয়ায় অনুমোদন করা হবে।</p>


        <table></table>


        <p style="margin: 0;"><b>ইউপি সদস্যের মন্তব্য/সুপারিশ:</b></p>
        @if($row->sonod_name=='উত্তরাধিকারী সনদ')
        <p style="margin: 0;font-size:14px;">সরেজমিন তদন্ত পূর্বক বর্ণিত ব্যক্তির উল্লিখিত উত্তরাধিকারী/উত্তরাধিকারীগণ ছাড়া আর অন্য কোন উত্তরাধিকারী নেই। ইহা আমার জানা মতে সত্য।</p>

        <p style="margin: 0;font-size:14px;">** অন্য কোন উত্তরাধিকারী থাকলে তার বিবরণ উল্লেখ করুন (প্রযোজ্য ক্ষেত্রে):</p>
        @else


        <p style="margin: 0;font-size:14px;">সরেজমিন তদন্ত পূর্বক বর্ণিত মরহুম ব্যক্তির উল্লিখিত ওয়ারিশ/ওয়ারিশগণ ছাড়া আর অন্য কোন ওয়ারিশ নেই। ইহা আমার জানা মতে সত্য।</p>

        <p style="margin: 0;font-size:14px;">** অন্য কোন ওয়ারিশ থাকলে তার বিবরণ উল্লেখ করুন (প্রযোজ্য ক্ষেত্রে):</p>
        @endif




    <table width="100%" style="border-collapse: collapse;margin-top:60px" border="0">
        <tr>
            <td  style="text-align: center;" width="40%">
                <div class="signature text-center position-relative">
                    {{-- <img width="170px"  src="{{ base64($row->chaireman_sign) }}"><br/> --}}
                    <b><span style="color:#7230A0;font-size:18px;">সংশ্লিষ্ট ইউপি সদস্যের স্বাক্ষর ও সীল</span> <br />




                </div>
            </td>
            <td style="text-align: center; width: 200px;" width="30%">

            </td>
            <td style="text-align: center;" width="40%">
                <div class="signature text-center position-relative">
                    {{-- <img width="170px"  src="{{ base64($row->chaireman_sign) }}"><br/> --}}
                    <b><span style="color:#7230A0;font-size:18px;">{{ $row->chaireman_name }}</span> <br />
                            </b><span style="font-size:16px;">{{ $row->chaireman_type }}</span><br />

                    {{ $uniouninfo->full_name }}<br> {{ $uniouninfo->thana }}, {{ $uniouninfo->district }} । <br>


                </div>
            </td>
        </tr>


    </table>



<p style="background: #787878;
    color: white;
    text-align: center;
    padding: 2px 2px;font-size: 16px;     margin-top: 20px;margin-bottom:0px" class="m-0">"সময়মত ইউনিয়ন কর পরিশোধ করুন। ইউনিয়নের উন্নয়নমূলক কাজে সহায়তা করুন"</p>

<p class="m-0" style="font-size:14px;text-align:center;margin: 0;">'ডিজিটাল ইউনিয়ন ট্যাক্স ও সেবা সিস্টেম' {{ $uniouninfo->domain }} এর সাথে থাকার জন্য ধন্যবাদ</p>


    {{-- <p style="text-align: center"> {{ $uniouninfo->domain }}</p> --}}


    </div>




</body>

</html>
