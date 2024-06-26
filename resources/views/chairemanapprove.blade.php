<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cairman Approved</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        .app-heading {
            text-align: center;
            margin: 32px 0;
            font-size: 23px;
            border-bottom: 1px solid #159513;
            color: #ffffff;
            background: #255f95;
        }
        .form-pannel {
            border: 1px solid #159513;
            padding: 25px 25px 25px 25px;
        }
        .panel-heading {
            padding: 11px 0px;
            border-top-right-radius: 6px;
            border-top-left-radius: 6px;
            margin-top: 20px;
        }
        .form-pannel {
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        .dropdown-menu {
            z-index: 99999;
        }
        .labelColor {
            color: #493eff;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="container" style="    margin-top: 50px;    margin-bottom: 50px;">

    @if($Secretary_pay=='Secretary_pay')


        @if($sonod->stutus=='approved' && $sonod->payment_status=='Paid')
        <div class="text-center" style="width:50%;margin:0 auto" v-if="getunionInfos.payment_type == 'Prepaid'">
            <h3>আবেদনটির ফি পরিশোধ করা হয়েছে</h3>

            <a target="_blank" href="{{ url('/sonod/d/'.$sonod->id) }}"  class="btn btn-info">সনদ ডাউনলোড</a>
            <a target="_blank" href="{{ url('/invoice/d/'.$sonod->id) }}"  class="btn btn-info">রশিদ ডাউনলোড</a>

        </div>
        @else
        <div class="text-center" style="width:50%;margin:0 auto" v-if="getunionInfos.payment_type == 'Prepaid'">
            <h3>আপনি কি নিশ্চিত?আবেদনটির ফি পরিশোধ করা হয়েছে!</h3>

            <button type="button" onclick="feepay({{ $sonod->id }})" class="btn btn-info">হা নিশ্চিত!</button>

        </div>
        @endif









        @else












        @if ($sonodnamedata->enname == 'Certificate of Inheritance' ||
            $sonodnamedata->enname == 'Inheritance certificate')
            <div class="row">
                @if($sonodnamedata->enname == 'Certificate of Inheritance')
                <div class="col-md-4 col-6 mt-3"><b>মৃত
                        ব্যাক্তির নাম : </b>{{ $sonod->utname }}</div>
                @endif
                @if($sonodnamedata->enname == 'Inheritance certificate')

                <div class="col-md-4 col-6 mt-3"><b>জীবিত
                    ব্যক্তির নাম : </b>{{ $sonod->utname }}</div>
                @endif
                <div class="col-md-4 col-6 mt-3"><b>লিঙ্গ : </b>{{ $sonod->applicant_gender }}</div>
                <div class="col-md-4 col-6 mt-3"><b>বৈবাহিক সম্পর্ক : </b>{{ $sonod->applicant_marriage_status }}</div>
                <div class="col-md-4 col-6 mt-3"><b>পিতা/স্বামীর নাম : </b>{{ $sonod->ut_father_name }}</div>
                <div class="col-md-4 col-6 mt-3"><b>মাতার নাম : </b>{{ $sonod->ut_mother_name }}</div>
                <div class="col-md-4 col-6 mt-3"><b>পিতা জীবিত কিনা : </b>{{ $sonod->successor_father_alive_status }}
                </div>
                <div class="col-md-4 col-6 mt-3"><b>মাতা জীবিত কিনা : </b>{{ $sonod->successor_mother_alive_status }}
                </div>
                <div class="col-md-4 col-6 mt-3"><b>পেশা : </b>{{ $sonod->applicant_occupation }}</div>
                <div class="col-md-4 col-6 mt-3"><b>বাসিন্দা : </b>{{ $sonod->applicant_resident_status }}</div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="app-heading">আবেদনকারীর তথ্য</div>
            </div>
            <div class="col-md-4 mt-3"></div>
            <div class="col-md-4 mt-3"><img width="100%" :src="$sonod - > image" alt=""></div>
            <div class="col-md-4 mt-3"></div>
            <div class="col-md-4 col-6 mt-3"><b>আবেদনকারীর নাম : </b>{{ $sonod->applicant_name }}</div>
            @if($sonodnamedata->enname == 'Certification of the same name')

            <div class="col-md-4 col-6 mt-3">
                <b>দ্বিতীয় নাম : </b>{{ $sonod->applicant_second_name }}
            </div>
            @endif

            <div class="col-md-4 col-6 mt-3" v-else><b>লিঙ্গ : </b>{{ $sonod->applicant_gender }}</div>

            <div class="col-md-4 col-6 mt-3" v-else><b>পিতা/স্বামীর নাম : </b>{{ $sonod->applicant_father_name }}
            </div>
            <div class="col-md-4 col-6 mt-3"><b>মাতার নাম : </b>{{ $sonod->applicant_mother_name }}</div>
            <div class="col-md-4 col-6 mt-3"><b>ন্যাশনাল আইডি : </b>{{ $sonod->applicant_national_id_number }}</div>
            <div class="col-md-4 col-6 mt-3"><b>জন্ম নিবন্ধন নং : </b>{{ $sonod->applicant_birth_certificate_number }}
            </div>
            <div class="col-md-4 col-6 mt-3"><b>হোল্ডিং নং : </b>{{ $sonod->applicant_holding_tax_number }}</div>
            <div class="col-md-4 col-6 mt-3"><b>জম্ম তারিখ : </b>{{ $sonod->applicant_date_of_birth }}</div>
            @if ($sonodnamedata->enname == 'Family certificate')

            <div class="col-md-4 col-6 mt-3"><b>বংশের নাম :
            </b>{{ $sonod->family_name }}</div>
            @endif
            @if($sonodnamedata->enname == 'Certificate of annual income' || $sonodnamedata->enname == 'Parents Income Certificate')

            <div class="col-md-4 col-6 mt-3">
                <b>বার্ষিক আয় : </b>{{ $sonod->Annual_income }}
            </div>
            @endif
            @if($sonodnamedata->enname == 'permit')

            <div class="col-md-4 col-6 mt-3"><b>অনুমতি এর বিষয় :
            </b>{{ $sonod->Subject_to_permission }}</div>
            @endif
            @if($sonodnamedata->bnname == 'প্রতিবন্ধী সনদপত্র')

            <div class="col-md-4 col-6 mt-3"><b>প্রতিবন্ধী :
            </b>{{ $sonod->disabled }}</div>
            @endif
            @if($sonodnamedata->enname == 'No Objection Letter to Transfer of Constituency')

            <div class="col-md-4 col-6 mt-3"><b>স্থানান্তরিত
                এলাকার নাম : </b>{{ $sonod->Name_of_the_transferred_area }}</div>
                @endif
                @if($sonodnamedata->enname == 'Certificate')

                <div class="col-md-4 col-6 mt-3"><b>প্রত্যয়নপত্র এর বিষয় :
                </b>{{ $sonod->The_subject_of_the_certificate }}</div>
                @endif
            <div class="col-md-4 col-6 mt-3"><b>পাসপোর্ট নং : </b>{{ $sonod->applicant_passport_number }}</div>

            <div class="col-md-4 col-6 mt-3" v-else><b>বৈবাহিক সম্পর্ক : </b>{{ $sonod->applicant_marriage_status }}
            </div>
            <div class="col-md-4 col-6 mt-3" v-else><b>পেশা: </b>{{ $sonod->applicant_occupation }}</div>
            <div class="col-md-4 col-6 mt-3"><b>শিক্ষাগত যোগ্যতা: </b>{{ $sonod->applicant_education }}</div>
            <div class="col-md-4 col-6 mt-3"><b>ধর্ম: </b>{{ $sonod->applicant_religion }}</div>
            <div class="col-md-4 col-6 mt-3" v-else><b>বাসিন্দা: </b>{{ $sonod->applicant_resident_status }}</div>
            @if($sonod->sonod_name != 'ট্রেড লাইসেন্স')

            <div class="col-md-12 col-12 mt-3"><b>আবেদনকৃত প্রত্যয়নের
                : <br> </b>{{ $sonod->prottoyon }}</div>
                @endif
            <div class="col-md-12">
                <div class="app-heading">বর্তমান ঠিকানা</div>
            </div>
            <div class="col-md-4 col-6 mt-3"><b>গ্রাম/মহল্লা: </b>{{ $sonod->applicant_present_village }}</div>
            <div class="col-md-4 col-6 mt-3"><b>রোড/ব্লক/সেক্টর: </b>{{ $sonod->applicant_present_road_block_sector }}
            </div>
            <div class="col-md-4 col-6 mt-3"><b>ওয়ার্ড নং: </b>{{ $sonod->applicant_present_word_number }}</div>
            <div class="col-md-4 col-6 mt-3"><b>জেলা: </b>{{ $sonod->applicant_present_district }}</div>
            <div class="col-md-4 col-6 mt-3"><b>উপজেলা/থানা: </b>{{ $sonod->applicant_present_Upazila }}</div>
            <div class="col-md-4 col-6 mt-3"><b>পোষ্ট অফিস: </b>{{ $sonod->applicant_present_post_office }}</div>
            <div class="col-md-12">
                <div class="app-heading">স্থায়ী ঠিকানা</div>
            </div>
            <div class="col-md-4 col-6 mt-3"><b>গ্রাম/মহল্লা: </b>{{ $sonod->applicant_permanent_village }}</div>
            <div class="col-md-4 col-6 mt-3"><b>রোড/ব্লক/সেক্টর:
                </b>{{ $sonod->applicant_permanent_road_block_sector }}</div>
            <div class="col-md-4 col-6 mt-3"><b>ওয়ার্ড নং: </b>{{ $sonod->applicant_permanent_word_number }}</div>
            <div class="col-md-4 col-6 mt-3"><b>জেলা: </b>{{ $sonod->applicant_permanent_district }}</div>
            <div class="col-md-4 col-6 mt-3"><b>উপজেলা/থানা: </b>{{ $sonod->applicant_permanent_Upazila }}</div>
            <div class="col-md-4 col-6 mt-3"><b>পোষ্ট অফিস: </b>{{ $sonod->applicant_permanent_post_office }}</div>
            <div class="col-md-12">
                <div class="app-heading">যোগাযোগের ঠিকানা</div>
            </div>
            <div class="col-md-6 col-6 mt-3"><b>মোবাইল: </b>{{ $sonod->applicant_mobile }}</div>
            <div class="col-md-6 col-6 mt-3"><b>ইমেল: </b>{{ $sonod->applicant_email }}</div>
            <div class="col-md-12">
                <div class="app-heading">সংযুক্ত</div>
            </div>
            <div class="col-md-4 col-6 mt-3"><span>ন্যাশনাল আইডি (Front page)</span> <br> <img width="100%"
                    src="{{ $sonod->applicant_national_id_front_attachment }}" alt=""></div>
            <div class="col-md-4 col-6 mt-3"><span>ন্যাশনাল আইডি (Back page)</span> <br> <img width="100%"
                    src="{{ $sonod->applicant_national_id_back_attachment }}" alt=""></div>
            <div class="col-md-4 col-6 mt-3"><span>জন্ম নিবন্ধন</span> <br> <img width="100%"
                    src="{{ $sonod->applicant_birth_certificate_attachment }}" alt=""></div>

                    @if($sonodnamedata->enname == 'Certificate of Inheritance' || $sonodnamedata->bnname == 'Inheritance certificate')

                    <div class="col-md-12">
                        <div class="app-heading">ওয়ারিশগনের তালিকা </div>
                    </div>
                    @endif
                    @if($sonodnamedata->enname == 'Certificate of Inheritance' || $sonodnamedata->bnname == 'Inheritance certificate')

                    <table class="table">
                        <tr>
                            <th>ক্রমিক</th>
                            <th>নাম</th>
                            <th>সম্পর্ক</th>
                            <th>জন্ম তারিখ</th>
                            <th>জাতীয় পরিচয়পত্র নাম্বার</th>
                        </tr>
                        @php
                        // print_r($sonod->successor_list);
                            // die();
                        @endphp
                        @foreach (json_decode($sonod->successor_list) as $ut)


                        <tr>
                            <td>{{ int_en_to_bn($ut->w_id+1) }}</td>
                            <td>{{ $ut->w_name }}</td>
                            <td>{{ $ut->w_relation }}</td>
                            <td>{{ int_en_to_bn($ut->w_age) }}</td>
                            <td>{{ int_en_to_bn($ut->w_nid) }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
        </div>
        <br>
        <br>

        @if($role=='Secretary')



        @php



        if ($sonod->sonod_name == 'নাগরিকত্ব সনদ') {
            $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে কোন রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাই তাকে $sonod->sonod_name প্রদান করা হলো ।";
        } else if ($sonod->sonod_name == 'ট্রেড লাইসেন্স') {
             $readonly = false;
        } else if ($sonod->sonod_name == 'ওয়ারিশান সনদ') {
             $readonly = true;
        } else if ($sonod->sonod_name == 'উত্তরাধিকারী সনদ') {
             $readonly = true;
        } else if ($sonod->sonod_name == 'বিবিধ প্রত্যয়নপত্র') {

             $readonly = false;
             $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। $sonod->prottoyon";


        } else if ($sonod->sonod_name == 'চারিত্রিক সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার স্বভাব চরিত্র ভালো এবং উন্নত চরিত্রের অধিকারী।ইহা আমার জানামতে সত্য ।";
        } else if ($sonod->sonod_name == 'ভূমিহীন সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। আমার জানা মতে তার থাকার মতো এবং চাষাবাদ করার মত নিজস্ব কোনো জমি নেই । তিনি একজন ভূমিহীন মানুষ তাই তাকে $sonod->sonod_name প্রদান করা হলো ।";
        } else if ($sonod->sonod_name == 'পারবারিক সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। আমার জানা মতে সে $sonod->family_name বংশের একজন উত্তরাধিকারী । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'অবিবাহিত সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। আমার জানামতে সে একজন অবিবাহিত পুরুষ । বিগত সময়ে তার কোন বিবাহ ছিলনা বা বিবাহ করেনি । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই ।";
        } else if ($sonod->sonod_name == 'পুনঃ বিবাহ না হওয়া সনদ') {
             $readonly = true;



            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে একজন বিবাহিত $sonod->applicant_gender এবং তাহার কোনো পুনঃ বিবাহ হয়নি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'বার্ষিক আয়ের প্রত্যয়ন') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। তার বার্ষিক আয় $sonod->Annual_income/=  হাজার টাকা । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'একই নামের প্রত্যয়ন') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। প্রকাশ থাকে যে $sonod->pplicant_name ও $sonod->pplicant_second_name একই ব্যক্তি । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'অনুমতি পত্র') {
             $readonly = false;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাকে $sonod->Subject_to_permission অনুমতি দেওয়া হল ।";
        } else if ($sonod->sonod_name == 'প্রতিবন্ধী সনদপত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। আমার জানামতে সে একজন $sonod->disabled প্রতিবন্ধী । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'অনাপত্তি সনদপত্র') {
             $readonly = false;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সেই বর্তমানে প্রোগ্রামার পদে নিযুক্ত আছেন । তার পাসপোর্ট আবেদনের প্রয়োজনীয় কাগজপত্র এর সঙ্গে পেরন করা হলো  । ইতিপূর্বে পুলিশ ভেরিফিকেশন সম্পন্ন হয়েছে  । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'অগভীর নলকূপ স্থাপন') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তার বসতবাড়িতে বর্তমানে কোন টিউবওয়েল নেই । তাই তাকে অগভীর নলকূপ বসানোর অনুমতি দেওয়া হল ।";
        } else if ($sonod->sonod_name == 'অবকাঠামো নির্মাণের অনুমতি পত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। বর্তমানে তার থাকার জন্য কোনো অবকাঠামো ও বসতবাড়ি নেই । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।  তাই তাকে অবকাঠামো নির্মাণের অনুমতি প্রদান করা হল ।";
        } else if ($sonod->sonod_name == 'অভিভাবকের আয়ের সনদপত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। তার বাবার বাৎসরিক আয় $sonod->Annual_income/= হাজার টাকা । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'আনুমানিক বয়স প্রত্যয়ন পত্র') {
             $readonly = true;

            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। তার আনুমানিক বয়স this.age($onod->applicant_date_of_birth)} । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'প্রত্যয়নপত্র') {
             $readonly = false;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাকে $sonod->The_subject_of_the_certificate অনুমতি দেওয়া হল ।";
        } else if ($sonod->sonod_name == 'ভোটার এলাকা স্থানান্তর অনাপত্তি পত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি।  বর্তমানে তার স্থায়ী কমস্থল স্থানান্তরিত হয়েছে । তার স্থায়ী কর্মস্থান এখন $sonod->Name_of_the_transferred_area । তার দৈনন্দিন সকল কার্যক্রম সুন্দরভাবে পরিচালনার জন্য ভোটার এলাকা পরিবর্তন করা খুবই জরুরী। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";


        } else if ($sonod->sonod_name == 'জাতীয় পরিচয়পত্র সংশোধন প্রত্যয়ন') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। তার জাতীয় পরিচয় পত্র কিছু তথ্য অনাকাঙ্ক্ষিত ভুল হয়েছে এটি সংশোধন করা অতীব জরুরী । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'নিঃসন্তান প্রত্যয়ন') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। আমার জানামতে সে বিবাহিত কিন্তু তার কোন সন্তান নেই । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই। ";
        } else if ($sonod->sonod_name == 'ভোটার তালিকায় নাম অন্তর্ভুক্ত না হওয়ার প্রত্যয়ন') {
             $readonly = false;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে অসুস্থ থাকার কারণে ভোটার তালিকায় তার নাম অন্তর্ভুক্তি করন করা হয়নি । তার নামটি ভোটার তালিকায় অন্তর্ভুক্ত করার জন্য অনুরোধ করা হলো । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'সম্প্রদায় সনদপত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সম্প্রদায় নিয়ে লেখাগুলো এখানে হবে । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'আর্থিক অস্বচ্ছলতার সনদপত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে আমার ইউনিয়নের স্থায়ী বাসিন্দা এবং সে আর্থিকভাবে খুবি অসচ্ছল । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'জীবিত ব্যক্তির ওয়ারিশ সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে কোন রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাই তাকে $sonod->sonod_name প্রদান করা হলো ।";
        } else if ($sonod->sonod_name == 'এতিম সনদপত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। আমার জানা মতে তার বাবা-মা জীবিত নেই, সে একজন এতিম । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'জীবিত সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। তিনি জীবিত এবং সুস্থ আছেন । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'রিনিউ সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।  তাকে সনদ রিনিউ করার জন্য অনুমোদন দেওয়া হল ।";
        } else if ($sonod->sonod_name == 'বসতবাড়ি হোল্ডিং নিবন্ধন সনদ') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে তার বসত বাড়ির জন্য হোল্ডিং নাম্বার নিয়েছে । সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'রোহিঙ্গা নয় মর্মে প্রত্যয়ন পত্র') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে রোহিঙ্গা নয়, সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে রাষ্ট্রদ্রোহিতার অভিযোগ নেই।";
        } else if ($sonod->sonod_name == 'ইউনিয়ন পরিষদ নাগরিক লিস্ট') {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে কোন রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাই তাকে $sonod->sonod_name প্রদান করা হলো ।";
        } else {
             $readonly = true;
            $sec_prottoyon = "জনাব $sonod->applicant_name কে আমি ব্যক্তিগতভাবে চিনি ও জানি। সে জন্মসূত্রে বাংলাদেশের নাগরিক এবং অত্র ইউনিয়ন পরিষদের স্থায়ী বাসিন্দা। আমার জানামতে তার বিরুদ্ধে কোন রাষ্ট্রদ্রোহিতার অভিযোগ নেই। তাই তাকে $sonod->sonod_name প্রদান করা হলো ।";
        }


        @endphp



            <div class="row">
                @if ($sonod->sonod_name == 'বিবিধ প্রত্যয়নপত্র')

                <div class="col-md-12">
                    <h5>আবেদনকৃত প্রত্যয়নের বিবরণ</h5>
                    <p>{{ $sonod->prottoyon }}</p>
                </div>
                @endif
                @if($sonod->sonod_name == 'বিবিধ প্রত্যয়নপত্র')

                <div class="col-md-12" >
                    <div class="form-group">
                        <label for="amountab">প্রত্যয়ন প্রদানের বিবরণ </label>
                       
                        <textarea  class="form-control" style="height:100px;resize:none"  id="sec_prottoyon" readonly="{{ $readonly }}" >{{ $sec_prottoyon }}</textarea>
                    </div>
                </div>
                @else
                <input type="hidden" id="sec_prottoyon" value="{{ $sec_prottoyon }}">
                @endif




                @if($sonod->payment_status == 'Unpaid')


                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="khat">ফি আদায়ের খাত </label>
                        <select v-model="form.khat" id="khat" class="form-control" required>
                            <option value="বসতবাড়ীর বাৎসরিক মূল্যের উপর কর ">বসতবাড়ীর বাৎসরিক মূল্যের উপর কর </option>
                            <option value="ব্যবসা/পেশা/জীবিকার উপর কর">ব্যবসা/পেশা/জীবিকার উপর কর</option>
                            <option value="সিনেমা/যাত্রা/থিয়েটার/ অন্যান্য বিনোদনমূলক অনুষ্ঠানের উপর কর">
                                সিনেমা/যাত্রা/থিয়েটার/ অন্যান্য বিনোদনমূলক অনুষ্ঠানের উপর কর</option>
                            <option value="লাইসেন্স ও পারমিট ফি">লাইসেন্স ও পারমিট ফি</option>
                            <option value="হাট-বাজার/ফেরীঘাট/ জলমহাল/অন্যান্য ইজারা বাবদ">হাট-বাজার/ফেরীঘাট/ জলমহাল/অন্যান্য
                                ইজারা বাবদ</option>
                            <option value="ভূমি/ ইমারত ভাড়া/ রপ্তানী কর">ভূমি/ ইমারত ভাড়া/ রপ্তানী কর</option>
                            <option value="নিলামে বিক্রয়লব্ধ আয়">নিলামে বিক্রয়লব্ধ আয়</option>
                            <option value="জরিমানা (যদি থাকে)">জরিমানা (যদি থাকে)</option>
                            <option value="অন্যান্য দাবী আদায় (যদি থাকে)">অন্যান্য দাবী আদায় (যদি থাকে)</option>
                            <option value="পশু জবেহ ফিস">পশু জবেহ ফিস</option>
                            <option value="যানবাহন ফিস">যানবাহন ফিস</option>
                            <option value="ওয়ারিশান সনদ ফিস">ওয়ারিশান সনদ ফিস</option>
                            <option value="বিবিধ সনদপত্র/প্রত্যয়ন পত্র ফিস">বিবিধ সনদপত্র/প্রত্যয়ন পত্র ফিস</option>
                            <option value="বিবিধ ফিস">বিবিধ ফিস</option>
                        </select>
                    </div>
                </div>
                @endif
                @if($sonod->payment_status == 'Unpaid')

                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="amountab">বিগত বছরের টাকা (অংকে) </label>
                        <input id="amountab" class="form-control" onchange="totalAmount()" type="number"
                        v-model="form.last_years_money" required>
                    </div>
                </div>
                @endif
                @if($sonod->payment_status == 'Unpaid')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amountabb">বর্তমানে পরিশোধকৃত টাকা (অংকে) </label>
                        <input id="amountabb" class="form-control" onchange="totalAmount()"  type="number"
                        v-model="form.currently_paid_money" required>
                    </div>
                </div>
                @endif
                @if($sonod->payment_status == 'Unpaid')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amounta">মোট (অংকে) </label>
                        <input id="amounta" class="form-control"  type="number" v-model="form.amounta" readonly required>
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <input id="submit" onclick="submit({{  $sonod->id }})" class="btn btn-info" type="button" value="অনুমোদন করুন">
                </div>
            </div>

        @elseif($role=='Chairman')

        <form @submit.stop.prevent="finalSubmit" style="margin-top: 50px;">
            <div class="text-center" style="width:50%;margin:0 auto"
                v-else-if="getunionInfos.payment_type == 'Postpaid'">
                <h3>আবেদনটি অনুমোদন করার জন্য নিশ্চিত করুন এ চাপ দিন</h3>
                <a onclick="chairemanapprove({{  $sonod->id }})" href="javascript:void(0)" class="btn btn-info">নিশ্চিত
                    করুন</a>
            </div>
        </form>
        @endif

        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

<script>


function totalAmount() {
            var amountab=0;
            var amountabb=0;
            amountab = document.getElementById('amountab').value;
            amountabb = document.getElementById('amountabb').value;
            amountab = parseInt(amountab);
            amountabb = parseInt(amountabb);

            document.getElementById('amounta').value = amountab + amountabb;
        }

console.log(window.location.origin)



function submit(id){

    var sec_prottoyon = document.getElementById('sec_prottoyon').value;
    var khat = document.getElementById('khat').value;
    var last_years_money = document.getElementById('amountab').value;
    var currently_paid_money = document.getElementById('amountabb').value;
    var amounta = document.getElementById('amounta').value;


    var myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");

var raw = JSON.stringify({
  "sec_prottoyon": sec_prottoyon,
  "khat": khat,
  "last_years_money": last_years_money,
  "currently_paid_money": currently_paid_money,
  "amounta":amounta,
  "approveData": "Secretary_approved"
});

var requestOptions = {
  method: 'POST',
  headers: myHeaders,
  body: raw,
  redirect: 'follow'
};
var origin = window.location.origin;

fetch(origin+"/api/sonod/sec/approve/"+id, requestOptions)
  .then(response => {
    if(response.status==200){
        Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'সনদটি অনুমোদিত হয়েছে',
        showConfirmButton: false,
        timer: 1500
        })
        window.location.href = '/dashboard'
    }



  })
  .catch(error => console.log('error', error));
}


function chairemanapprove(id){



var myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");


var requestOptions = {
  method: 'GET',
  headers: myHeaders,
  redirect: 'follow'
};
var origin = window.location.origin;

fetch(origin+"/api/sonod/approved/"+id+"?type=notify", requestOptions)
  .then(response => {
    if(response.status==200){
        Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'সনদটি অনুমোদিত হয়েছে',
        showConfirmButton: false,
        timer: 1500
        })
        window.location.href = '/dashboard'
    }



  })
  .catch(error => console.log('error', error));
}


function feepay(id){


    var origin = window.location.origin;
var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function() {
  if(this.readyState === 4) {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'আবেদনটির ফি পরিশোধ করা হয়েছে',
        showConfirmButton: false,
        timer: 1500
        })
        window.open(origin+"/sonod/d/"+id, '_blank');
        window.location.reload();
    //  console.log(JSON.parse(this.responseText));
    // window.location.href = origin+"/sonod/d/"+id
  }
});

xhr.open("GET", origin+"/api/sonod/pay/"+id+"?type=notify");
xhr.setRequestHeader("Content-Type", "application/json");

xhr.send();




// console.log(xhr)



}



</script>

</body>
</html>
