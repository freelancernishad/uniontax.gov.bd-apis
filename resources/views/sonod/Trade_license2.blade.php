@php
$orthoBchor = explode('-',$row->orthoBchor);
@endphp
{{-- @php
$orthoBchor = explode('-',$row->orthoBchor);
@endphp

<p style="margin-bottom: 10px;">৩০ জুন, {{ int_en_to_bn('20'.$orthoBchor[1]) }} তারিখ পর্যন্ত বৈধ ফি প্রদানের পরিমাণ  {{ int_en_to_bn($row->total_amount) }}  টাকা  কথায়: {{ $row->the_amount_of_money_in_words }}   প্রাপ্ত হয়ে তার ব্যবসা/বৃত্তি/পেশা চালিয়ে যাওয়ার জন্য এই লাইসেন্স প্রদান করা হলো।<br>
</p> --}}


<p style="margin-bottom: 10px;font-size:12px;text-align:justify">স্থানীয় সরকার (ইউনিয়ন পরিষদ) আইন, ২০০৯ (২০০৯ সনের ৬১ নং আইন) এর ধারা ৬৬ তে প্রদত্ত ক্ষমতাবলে সরকার প্রণীত আদর্শ কর তফসিল, ২০১৩ এর ৬ ও ১৭ নং অনুচ্ছেদ অনুযায়ী ব্যবসা, বৃত্তি, পেশা, বা শিল্প প্রতিষ্ঠানের উপর আরোপিত কর আদায়ের লক্ষ্যে নির্ধারিত শর্তে নিম্নবর্ণিত ব্যক্তি/প্রতিষ্ঠানের অনুকূলে এই ট্রেড লাইসেন্সটি ইস্যু করা হলো: <br>
</p>

<table width="100%" style="margin-top:0px;font-size:12px">


        <tr>
            <td width="30%">প্রতিষ্ঠানের নাম</td><td>: {{ $row->applicant_name_of_the_organization }}</td>
        </tr>

        <tr>
            <td width="30%">লাইসেন্সধারীর নাম</td><td>: {{ $row->applicant_name }}</td>
        </tr>

        <tr>
            <td width="30%">পিতা/স্বামীর নাম</td><td>: {{ $row->applicant_father_name }}</td>
        </tr>
        <tr>
            <td width="30%">মাতার নাম</td><td>: {{ $row->applicant_mother_name }}</td>
        </tr>

        <tr>
            <td width="30%">ব্যবসার প্রকৃতি</td><td>: {{ $row->applicant_owner_type }}</td>
        </tr>

        <tr>
            <td width="30%">ব্যবসার ধরন</td><td>: {{ $row->applicant_type_of_business }}</td>
        </tr>

        <tr>
            <td width="30%">প্রতিষ্ঠানের ঠিকানা</td><td>: {{ $row->organization_address }}</td>
        </tr>

        <tr>
            <td width="30%">ওয়ার্ড নং</td><td>: {{ int_en_to_bn($row->applicant_present_word_number) }}</td>
        </tr>

        <tr>
            <td width="30%">জাতীয় পরিচয়পত্র নং</td><td>: {{ int_en_to_bn($row->applicant_national_id_number) }}</td>
        </tr>

        <tr>
            <td width="30%">অর্থবছর </td><td>: {{ int_en_to_bn($row->orthoBchor) }}</td>
        </tr>

    </table>



    <table width='100%' style="font-size: 12px">
        <tr>

            <td width='50%'>
                <p style='border-bottom:2px solid black;'>মালিক/স্বত্বাধিকারীর বর্তমান ঠিকানা</p>
                <ul style='list-style:none'>
                    <li>হোল্ডিং নং : {{ $row->applicant_holding_tax_number }}</li>
                    <li>রোড নং  : </li>
                    <li>গ্রাম/মহল্লা : {{ $row->applicant_present_village }}</li>
                    <li>ডাকঘর : {{ $row->applicant_present_post_office }}</li>
                    {{-- <li>পোস্ট কোড : {{ $row->applicant_present_village }}</li> --}}
                    <li>উপজেলা/থানা : {{ $row->applicant_present_Upazila }}</li>
                    <li>জেলা : {{ $row->applicant_present_district }}</li>
                </ul>
            </td>

        <td width='50%' align ="right">
                <p style='border-bottom:2px solid black;'>মালিক/স্বত্বাধিকারীর স্থায়ী ঠিকানা</p>
                <ul style='list-style:none'>
                    <li>হোল্ডিং নং : {{ $row->applicant_holding_tax_number }}</li>
                    <li>রোড নং  : </li>
                    <li>গ্রাম/মহল্লা : {{ $row->applicant_permanent_village }}</li>
                    <li>ডাকঘর : {{ $row->applicant_permanent_post_office }}</li>
                    {{-- <li>পোস্ট কোড : {{ $row->applicant_permanent_village }}</li> --}}
                    <li>উপজেলা/থানা : {{ $row->applicant_permanent_Upazila }}</li>
                    <li>জেলা : {{ $row->applicant_permanent_district }}</li>
                </ul>
            </td>


        </tr>
    </table>



    @php

        $amount_deails = $row->amount_deails;
        $amount_deails = json_decode($amount_deails);
        $tredeLisenceFee = $amount_deails->tredeLisenceFee;
        $vatAykor = ($tredeLisenceFee*$amount_deails->vatAykor)/100;


    @endphp


    <table width='100%' style="font-size: 12px;margin-top:10px">
        <tr>
            <td width='50%'>

                <ul style='list-style:none'>
                    <li>ট্রেড লাইসেন্স ফি (নবায়ন) :-</li>
                    <li>পারমিট ফি  : {{ int_en_to_bn($tredeLisenceFee) }} টাকা</li>
                    <li>সার্ভিস চার্জ : ০.০০ টাকা</li>
                    <li>বকেয়া : {{ int_en_to_bn($amount_deails->last_years_money) }} টাকা</li>
                    <li>সাবচার্জ  : ০.০০ টাকা</li>
                </ul>
            </td>

        <td width='50%' align ="right">

                <ul style='list-style:none'>
                    <li>পেশা ব্যবসা ও বৃত্তির উপর কর  :- {{ int_en_to_bn($amount_deails->pesaKor) }} টাকা</li>
                    <li>সাইনবোর্ড (পরিচিতিমূলক)  : ০.০০ টাকা</li>
                    <li>আয়কর/উৎস কর : ০.০০ টাকা</li>
                    <li>ভ্যাট : {{ int_en_to_bn($vatAykor) }} টাকা</li>
                    <li>সংশোধন ফি : ০.০০ টাকা</li>
                </ul>
            </td>
        </tr>
    </table>

    <hr>

    <table width='100%' style="font-size: 12px">
        <tr>
            <td width='50%'>

                <b style="color:#159513">অত্র ট্রেড লাইসেন্স এর মেয়াদ : {{ int_en_to_bn("30-06-20".$orthoBchor[1]) }} পর্যন্ত </b>
            </td>

        <td width='50%' align ="right">
            <b style="color:black">সর্বমোট : {{ int_en_to_bn($row->total_amount) }} টাকা মাত্র </b>
            </td>
        </tr>
    </table>




    {{-- <tr>
        <td width="30%">ঠিকানা</td><td>: {{ $row->applicant_present_village }}, {{ $row->applicant_present_post_office }}, {{ $row->applicant_present_Upazila }}, {{ $row->applicant_present_district }}</td>
    </tr> --}}

{{--
    <p style="margin-bottom: 10px;"> {!! int_en_to_bn($row->sec_prottoyon) !!}<br>
    </p> --}}

    {{-- <p style="margin-bottom: 10px;"
    > {!! $sonod->template  !!}<br>
    </p> --}}

    {{-- <p style="margin-bottom: 10px;"
    >৩০ জুন, ২০২৩ তারিখ পর্যন্ত বৈধ ফি প্রদানের পরিমাণ  {{ int_en_to_bn($row->total_amount) }} টাকা  কথায়: {{ $row->the_amount_of_money_in_words }}  প্রাপ্ত হয়ে তার ব্যবসা/বৃত্তি/পেশা চালিয়ে যাওয়ার জন্য এই লাইসেন্স প্রদান করা হলো।<br>
    </p> --}}
