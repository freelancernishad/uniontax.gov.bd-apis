<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
</head>
<body style="font-family: 'bangla', sans-serif;">


    <table width="100%" style="border-collapse: collapse;" border="0">
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

<table width="100%" style="margin-bottom:10px">
    <tr>
        <td style="text-align:center;background:#60497A;color:white">ক্যাশ বহি</td>

    </tr>
</table>

<table width="100%">
    <tr>
        <td width="20%" style="text-align:right;background:#92D050">ব্যাংক একাউন্ট নম্বর:</td>
        <td width="15%" style="text-align:left">৬৪৭৯৬৪৬৩১৩</td>
        <td width="15%" style="text-align:right;background:#95B3D7">অর্থ বছর:</td>
        <td width="15%" style="text-align:left">২০২২-২০২৩</td>
        <td width="15%" style="text-align:right;background:#E6B8B7">মাসের নাম:</td>
        <td width="15%" style="text-align:left">সেপ্টেম্বর/২০২২</td>
    </tr>
</table>


        <table class="table" width="100%">
            <thead>
                <tr>
                    <th class="th" width="10%">তারিখ</th>
                    <th class="th" width="29%">প্রাপ্তির বিবরণ</th>
                    <th class="th" width="10%">টাকার পরিমাণ</th>
                    <th class="th" width="2%" style="background: #002060;border-bottom:0px"></th>
                    <th class="th" width="10%">তারিখ</th>
                    <th class="th" width="29%">ব্যয়ের বিবরণ</th>
                    <th class="th" width="10%">টাকার পরিমাণ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sells as $sell)
                <tr>
                    <td class="td">{{ int_en_to_bn($sell->date) }}</td>
                    <td class="td">{{ $sell->description }}</td>
                    <td class="td">{{ int_en_to_bn(round($sell->price,2)) }}</td>
                    <td class="td" style="background: #002060;border-bottom:0px;border-top:0px"></td>
                    <td class="td">{{ int_en_to_bn($sell->Bdate) }}</td>
                    <td class="td">{{ $sell->Bdescription }}</td>
                    <td class="td">{{ int_en_to_bn(round($sell->Bprice,2)) }}</td>
                </tr>
                @endforeach
                <tr style="background: #E6B8B7">
                    <td class="td" colspan='2' style="text-align:right;">চলতি মাসের মোট জমা</td>
                    <td class="td">{{ int_en_to_bn(round($paymentTotalAmount,2)) }}</td>
                    <td class="td" style="background: #002060;border-bottom:0px;border-top:0px"></td>
                    <td class="td" colspan='2' style="text-align:right;">চলতি মাসের মোট ব্যয়</td>
                    <td class="td">{{ int_en_to_bn(round($buyTotalAmount,2)) }}</td>
                </tr>
                <tr style="background: #B8CCE4">
                    <td class="td" colspan='2' style="text-align:right;">চলতি মাসের প্রারম্ভিক স্থিতি (বিগত মাসের
                        উদ্বৃত্ত জের)</td>
                    <td class="td">{{ int_en_to_bn(round($paymentTotalAmount,2)) }}</td>
                    <td class="td" style="background: #002060;border-bottom:0px;border-top:0px"></td>
                    <td class="td" colspan='2' style="text-align:right;">চলতি মাসের উদ্বৃত্ত স্থিতি (Balance)/দেনা</td>
                    <td class="td">{{ int_en_to_bn(round($buyTotalAmount,2)) }}</td>
                </tr>
                <tr style="background: #92D050">
                    <td class="td" colspan='2' style="text-align:right;">সর্বমোট</td>
                    <td class="td">{{ int_en_to_bn(round($paymentTotalAmount,2)) }}</td>
                    <td class="td" style="background: #002060;border-top:0px"></td>
                    <td class="td" colspan='2' style="text-align:right;">সর্বমোট</td>
                    <td class="td">{{ int_en_to_bn(round($buyTotalAmount,2)) }}</td>
                </tr>
            </tbody>
        </table>

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
</body>
</html>
