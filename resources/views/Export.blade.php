<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .td {
            border: 1px dotted black;
        }
    </style>
</head>
<body style="font-family: 'bangla', sans-serif;">




@if($union=='all')







    <table width="100%" style="border-collapse: collapse;" border="0" >
        <tr>
            <td style="text-align: center;" width="20%">
                {{-- <span style="color:#b400ff;"><b>
                        উন্নয়নের গণতন্ত্র, <br /> শেখ হাসিনার মূলমন্ত্র </b>
                </span> --}}
            </td>
            <td style="text-align: center;" width="20%">
                <img width="70px" src="{{ base64('backend/bd-logo.png') }}">
            </td>
            <td style="text-align: center;" width="20%">
            </td>
        </tr>
        <tr style="margin-top:2px;margin-bottom:2px;">
            <td>
            </td>
            <td style="text-align: center;" width="50%">
                <p style="font-size:20px">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                {{-- <p style="font-size:25px">চেয়ারম্যানের কার্যালয়</p> --}}
            </td>
            <td>
            </td>
        </tr>
        <tr style="margin-top:0px;margin-bottom:0px;">
            <td>
            </td>
            <td style="margin-top:0px; margin-bottom:0px; text-align: center;">
                <h1 style="color: #7230A0; margin: 0px; font-size: 28px">সকল ইউনিয়ন এর প্রতিবেদন</h3>
            </td>
            <td>
            </td>
        </tr>
        <tr style="margin-top:2px;margin-bottom:2px;">
            <td>
            </td>
            <td style="text-align: center; ">
                {{-- <p style="font-size:20px">উপজেলা: {{ $uniouninfo->thana }}, জেলা: {{ $uniouninfo->district }} ।</p> --}}
            </td>
            <td>
            </td>
        </tr>
    </table>

    @else


    <table width="100%" style="border-collapse: collapse;" border="0">
        <tr>
            <td style="text-align: center;" width="20%">
                {{-- <span style="color:#b400ff;"><b>
                        উন্নয়নের গণতন্ত্র, <br /> শেখ হাসিনার মূলমন্ত্র </b>
                </span> --}}
            </td>
            <td style="text-align: center;" width="20%">
                <img width="70px" src="{{ base64($uniouninfo->sonod_logo) }}">
            </td>
            <td style="text-align: center;" width="20%">
            </td>
        </tr>
        <tr style="margin-top:2px;margin-bottom:2px;">
            <td>
            </td>
            <td style="text-align: center;" width="50%">
                <p style="font-size:20px">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                {{-- <p style="font-size:25px">চেয়ারম্যানের কার্যালয়</p> --}}
            </td>
            <td>
            </td>
        </tr>
        <tr style="margin-top:0px;margin-bottom:0px;">
            <td>
            </td>
            <td style="margin-top:0px; margin-bottom:0px; text-align: center;">
                <h1 style="color: #7230A0; margin: 0px; font-size: 28px">{{ $uniouninfo->full_name }}</h3>
            </td>
            <td>
            </td>
        </tr>
        <tr style="margin-top:2px;margin-bottom:2px;">
            <td>
            </td>
            <td style="text-align: center; ">
                <p style="font-size:20px">উপজেলা: {{ $uniouninfo->thana }}, জেলা: {{ $uniouninfo->district }} ।</p>
            </td>
            <td>
            </td>
        </tr>
    </table>
    @endif


    <h2 style="width:350px;background:green;padding:10px 10px;margin:10px auto;text-align:center;color:white;border-radius: 20px;font-size:20px">সেবা প্রদান ও ফি আদায় সংক্রান্ত প্রতিবেদন</h2>

        <h3 style="text-align: center">
        @if($sonod_type=='holdingtax')
       হোল্ডিং ট্যাক্স
        @elseif($sonod_type=='all')
        সকল ফি এর প্রতিবেদন
        @else
            {{ $sonod_type }}
        @endif
        </h3>



    <table width="100%">
        <tr style="margin-top:2px;margin-bottom:2px;">
            <td colspan="2">
              <span>প্রতিবেদনের সময়কালঃ</span>  {{ int_en_to_bn(date("d/m/Y", strtotime($from))) }} থেকে {{ int_en_to_bn(date("d/m/Y", strtotime($to))) }} পর্যন্ত
            </td>
            <td style="text-align: right">
                অর্থ বছর: {{ int_en_to_bn(COB(1,date("m", strtotime($from)))) }}
            </td>
        </tr>
    </table>

    <table width="100%" style="border-collapse: collapse;" border="0">
        <thead>
            <tr>
                <th class="td" style="text-align:center" width="10%">ক্রমিক নং</th>

                <th class="td" style="text-align:center" width="20%">তারিখ</th>

                @if($sonod_type=='all')
                <th class="td" style="text-align:center" width="20%">সেবার ধরণ</th>
                @endif


                <th class="td" style="text-align:center" width="20%">সেবা গ্রহনকারীর নাম</th>
                <th class="td" style="text-align:center" width="30%">ঠিকানা (হোল্ডিং ও গ্রাম)</th>
                <th class="td" style="text-align:center" width="20%">মোবাইল নম্বর</th>

                <th class="td" style="text-align:center" width="20%">আদায়কৃত ফি এর পরিমান</th>


            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            $i = 1;
            @endphp
            @foreach ($row as $Product)
            <tr>
                <td class="td" style="text-align:center">{{ int_en_to_bn($i) }}</td>

                <td class="td" style="text-align:center">{{ int_en_to_bn(date("d-m-Y",strtotime($Product->created_at)))  }}</td>
                @if($sonod_type=='all')


                    @if($Product->sonod_type=='holdingtax')
                    <td class="td" style="text-align:center">হোল্ডিং ট্যাক্স</td>

                    @else
                     <td class="td" style="text-align:center">{{ $Product->sonod_type }}</td>
                    @endif



                @endif




                @if($Product->sonod_type=='holdingtax')

                @php
                    $PDO = \DB::connection()->getPdo();
                    $QUERY = $PDO->prepare("SELECT * FROM `holdingtaxes` WHERE `id`='".$Product->tax->holdingTax_id."'");
                    $QUERY->execute();
                     $holdingTax=$QUERY->fetch();
                // print_r($holdingTax);
                // print_r($holdingTax['maliker_name']);
                @endphp

                <td class="td" style="text-align:center">{{ $holdingTax['maliker_name'] }}</td>
                <td class="td" style="text-align:center">গ্রামঃ- {{ $holdingTax['gramer_name'] }},
                    হোল্ডিং নং- {{ int_en_to_bn($holdingTax['holding_no']) }}</td>
                <td class="td" style="text-align:center">{{ int_en_to_bn($holdingTax['mobile_no']) }}</td>

                @else



                <td class="td" style="text-align:center">{{ $Product->sonod->applicant_name }}</td>
                <td class="td" style="text-align:center">গ্রামঃ- {{ $Product->sonod->applicant_present_village }},
                    হোল্ডিং নং- {{ int_en_to_bn($Product->sonod->applicant_holding_tax_number) }}</td>
                <td class="td" style="text-align:center">{{ int_en_to_bn($Product->sonod->applicant_mobile) }}</td>
                @endif

                <td class="td" style="text-align:center">{{ int_en_to_bn(round($Product->amount,2)) }}</td>



            </tr>
            @php
            $i++;
            $total += $Product->amount;
            @endphp
            @endforeach
            <tr>
                @if($sonod_type=='all')
                <td colspan="6" class="td" style="text-align: right">মোট</td>
                @else
                <td colspan="5" class="td" style="text-align: right">মোট</td>
                @endif
                <td class="td" style="text-align:center">{{ int_en_to_bn(round($total,2)) }}</td>
            </tr>
        </tbody>
    </table>


    @if($union!='all')

    <table width="100%" style="border-collapse: collapse;margin-top:50px" border="0">
        <tr>
            <td style="text-align: center;" width="40%">
            </td>
            <td style="text-align: center; width: 200px;" width="30%">
            </td>
            <td style="text-align: center;" width="40%">
                @php
                $C_color = '#7230A0';
                $C_size = '18px';
                $color = 'black';
                if($uniouninfo->short_name_e=='dhamor'){
                $C_color = '#5c1caa';
                $C_size = '20px';
                $color = '#5c1caa';
                }
                @endphp
                <div class="signature text-center position-relative" style="color:{{ $color }}">
                    <b><span style="color:{{ $C_color }};font-size:{{ $C_size }};">{{ $uniouninfo->c_name }}</span>
                        <br />
                    </b><span style="font-size:16px;">চেয়ারম্যান</span><br />
                    {{ $uniouninfo->full_name }}<br> {{ $uniouninfo->thana }}, {{ $uniouninfo->district }} ।
                </div>
            </td>
        </tr>
    </table>
    @endif
</body>
</html>
