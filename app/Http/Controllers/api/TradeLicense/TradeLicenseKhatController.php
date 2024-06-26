<?php

namespace App\Http\Controllers\api\TradeLicense;

use App\Http\Controllers\Controller;

use App\Models\TradeLicenseKhat;
use App\Models\TradeLicenseKhatFee;
use Illuminate\Http\Request;

class TradeLicenseKhatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searhtype = $request->searhtype;
        if($searhtype=='main'){
            return TradeLicenseKhat::where(['type'=>'main'])->get();
        }elseif($searhtype=='sub'){
            $main_khat_id = $request->main_khat_id;

            $tradeMain =  TradeLicenseKhat::where('khat_id',$main_khat_id)->first();
            $tradeSub =  TradeLicenseKhat::where(['main_khat_id'=>$main_khat_id,'type'=>'sub'])->get();

            $data = [
                'tradeMain' => $tradeMain,
                'tradeSub' => $tradeSub

            ];
            return $data;










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
        $datas =  $request->all();


        $insertDatas = [
            [
                'khat_id'=>'101',
                'name'=>'গুদাম (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'101101',
                        'name'=>'মূলধন ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'101',
                        'type'=>'sub',
                        'fee'=>'500',
                    ],
                    [
                        'khat_id'=>'101102',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৫ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'101',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],
                    [
                        'khat_id'=>'101103',
                        'name'=>'মূলধন ৫ লক্ষ টাকা হইতে ১০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'101',
                        'type'=>'sub',
                        'fee'=>'1500',
                    ],
                    [
                        'khat_id'=>'101104',
                        'name'=>'মূলধন ১০ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'101',
                        'type'=>'sub',
                        'fee'=>'2000',
                    ],






            [
                'khat_id'=>'102',
                'name'=>'হিমাগার (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'102101',
                        'name'=>'মূলধন ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'102',
                        'type'=>'sub',
                        'fee'=>'400',
                    ],
                    [
                        'khat_id'=>'102102',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৫ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'102',
                        'type'=>'sub',
                        'fee'=>'800',
                    ],
                    [
                        'khat_id'=>'102103',
                        'name'=>'মূলধন ৫ লক্ষ টাকা হইতে ১০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'102',
                        'type'=>'sub',
                        'fee'=>'1200',
                    ],
                    [
                        'khat_id'=>'102104',
                        'name'=>'মূলধন ১০ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'102',
                        'type'=>'sub',
                        'fee'=>'2000',
                    ],









            [
                'khat_id'=>'103',
                'name'=>'ক্ষুদ্র ও কুটির শিল্প (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'103101',
                        'name'=>'মূলধন ১০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'103',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'103102',
                        'name'=>'মূলধন ১০ হাজার টাকা হইতে ২৫ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'103',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'103103',
                        'name'=>'মূলধন ২৫ হাজার টাকা হইতে ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'103',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'103104',
                        'name'=>'মূলধন ৫০ হাজার টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'103',
                        'type'=>'sub',
                        'fee'=>'300',
                    ],












            [
                'khat_id'=>'104',
                'name'=>'শিল্প কারখানা (লিমিটেড কোম্পানী)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],

                    [
                        'khat_id'=>'104101',
                        'name'=>'পরিশোধিত মূলধন ৫০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'104',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],
                    [
                        'khat_id'=>'104102',
                        'name'=>'পরিশোধিত মূলধন ৫০ লক্ষ টাকা হইতে ১ কোটি টাকা পর্যন্ত',
                        'main_khat_id'=>'104',
                        'type'=>'sub',
                        'fee'=>'10000',
                    ],
                    [
                        'khat_id'=>'104103',
                        'name'=>'পরিশোধিত মূলধন ১ কোটি টাকা হইতে ৫ কোটি টাকা পর্যন্ত',
                        'main_khat_id'=>'104',
                        'type'=>'sub',
                        'fee'=>'25000',
                    ],
                    [
                        'khat_id'=>'104104',
                        'name'=>'পরিশোধিত মূলধন ৫ কোটি টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'104',
                        'type'=>'sub',
                        'fee'=>'40000',
                    ],










            [
                'khat_id'=>'105',
                'name'=>'কৃষি খামার, দুগ্ধ খামার, হাঁস-মুরগীর খামার, মৎস্য খামার, গবাদি পশুর খামার ইত্যাদি (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],

                    [
                        'khat_id'=>'105101',
                        'name'=>'মূলধন ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'105',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'105102',
                        'name'=>'মূলধন ৫০ হাজার টাকা হইতে ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'105',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'105103',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৩ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'105',
                        'type'=>'sub',
                        'fee'=>'150',
                    ],
                    [
                        'khat_id'=>'105104',
                        'name'=>'মূলধন ৩ লক্ষ টাকা হইতে ১০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'105',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'105105',
                        'name'=>'মূলধন ১০ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'105',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],








            [
                'khat_id'=>'106',
                'name'=>'ধান ভাঙানো কল, আটা বা ময়দার কল বা মিল, তেলের কল (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'106101',
                        'name'=>'মূলধন ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'106',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'106102',
                        'name'=>'মূলধন ৫০ হাজার টাকা হইতে ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'106',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'106103',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৩ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'106',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'106104',
                        'name'=>'মূলধন ৩ লক্ষ টাকা হইতে ৫ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'106',
                        'type'=>'sub',
                        'fee'=>'400',
                    ],
                    [
                        'khat_id'=>'106105',
                        'name'=>'মূলধন ৫ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'106',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],











            [
                'khat_id'=>'107',
                'name'=>'স-মিল, বিদ্যুৎ চালিত অন্যান্য মিল (লিমিটেড কোম্পানী ব্যতীত)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'107101',
                        'name'=>'মূলধন ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'107',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'107102',
                        'name'=>'মূলধন ৫০ হাজার টাকা হইতে ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'107',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'107103',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৩ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'107',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'107104',
                        'name'=>'মূলধন ৩ লক্ষ টাকা হইতে ৫ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'107',
                        'type'=>'sub',
                        'fee'=>'400',
                    ],
                    [
                        'khat_id'=>'107105',
                        'name'=>'মূলধন ৫ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'107',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],






            [
                'khat_id'=>'108',
                'name'=>'ইট ভাটা বা অন্যান্য সিরামিক প্রস্তুতকারক',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'108101',
                        'name'=>'মূলধন বা পরিশোধিত মূলধন ২০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'108',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],
                    [
                        'khat_id'=>'108102',
                        'name'=>'মূলধন বা পরিশোধিত মূলধন ২০ লক্ষ টাকা হইতে ৪০ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'108',
                        'type'=>'sub',
                        'fee'=>'15000',
                    ],
                    [
                        'khat_id'=>'108103',
                        'name'=>'মূলধন বা পরিশোধিত মূলধন ৪০ লক্ষ টাকার অধিক',
                        'main_khat_id'=>'108',
                        'type'=>'sub',
                        'fee'=>'50000',
                    ],









            [
                'khat_id'=>'109',
                'name'=>'সিনেমা হল',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'109101',
                        'name'=>'সাধারণ',
                        'main_khat_id'=>'109',
                        'type'=>'sub',
                        'fee'=>'300',
                    ],
                    [
                        'khat_id'=>'109102',
                        'name'=>'শীতাতপ নিয়ন্ত্রিত',
                        'main_khat_id'=>'109',
                        'type'=>'sub',
                        'fee'=>'500',
                    ],








            [
                'khat_id'=>'110',
                'name'=>'বিউটি পারলার, হেয়ার ড্রেসিং সেলুন',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'111101',
                        'name'=>'সাধারণ',
                        'main_khat_id'=>'111',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'111102',
                        'name'=>'শীতাতপ নিয়ন্ত্রিত',
                        'main_khat_id'=>'111',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],







            [
                'khat_id'=>'111',
                'name'=>'লন্ড্রী',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'111101',
                        'name'=>'সাধারণ',
                        'main_khat_id'=>'111',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'111102',
                        'name'=>'অটোমেটিক মেশিনযুক্ত লন্ড্রী',
                        'main_khat_id'=>'111',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'111102',
                        'name'=>'লন্ড্রী শোরুম',
                        'main_khat_id'=>'111',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],













            [
                'khat_id'=>'112',
                'name'=>'ব্যাংক, আর্থিক প্রতিষ্ঠান, বেসরকারি অফিস, প্রতিষ্ঠান বা সংস্থা বা উহাদের কোন শাখা',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
            [
                'khat_id'=>'113',
                'name'=>'ঠিকাদারী ফার্ম বা প্রতিষ্ঠান',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'113101',
                        'name'=>'তৃতীয় শ্রেণীর ঠিকাদারী প্রতিষ্ঠান',
                        'main_khat_id'=>'113',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],
                    [
                        'khat_id'=>'113102',
                        'name'=>'দ্বিতীয় শ্রেণীর ঠিকাদারী প্রতিষ্ঠান',
                        'main_khat_id'=>'113',
                        'type'=>'sub',
                        'fee'=>'2000',
                    ],
                    [
                        'khat_id'=>'113102',
                        'name'=>'প্রথম শ্রেণীর ঠিকাদারী প্রতিষ্ঠান',
                        'main_khat_id'=>'113',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],












            [
                'khat_id'=>'114',
                'name'=>'কৃষি পণ্যের আড়ত',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
            [
                'khat_id'=>'115',
                'name'=>'পেশা, বৃত্তি (কলিং)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],

                    [
                        'khat_id'=>'115101',
                        'name'=>'যে কোন ধরনের ইঞ্জিনিয়ারিং ফার্ম',
                        'main_khat_id'=>'115',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],
                    [
                        'khat_id'=>'115102',
                        'name'=>'কনসালটেন্সি ফার্ম',
                        'main_khat_id'=>'115',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],
                    [
                        'khat_id'=>'115102',
                        'name'=>'সলিসিটর ফার্ম',
                        'main_khat_id'=>'115',
                        'type'=>'sub',
                        'fee'=>'5000',
                    ],









            [
                'khat_id'=>'116',
                'name'=>'আত্মকর্মে নিয়োজিত চিকিৎসক, প্রকৌশলী, আইনজীবী',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'116101',
                        'name'=>'আয়করযোগ্য আয় না হইবার ক্ষেত্রে',
                        'main_khat_id'=>'116',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'116102',
                        'name'=>'আয়করযোগ্য আয় হইবার ক্ষেত্রে',
                        'main_khat_id'=>'116',
                        'type'=>'sub',
                        'fee'=>'500',
                    ],








            [
                'khat_id'=>'117',
                'name'=>'আবাসিক হোটেল বা মোটেল',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'117101',
                        'name'=>'মূলধন ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'117',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'117102',
                        'name'=>'মূলধন ৫০ হাজার টাকা হইতে ১ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'117',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'117103',
                        'name'=>'মূলধন ১ লক্ষ টাকা হইতে ৩ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'117',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'117104',
                        'name'=>'মূলধন ৩ লক্ষ টাকা হইতে ৫ লক্ষ টাকা পর্যন্ত',
                        'main_khat_id'=>'117',
                        'type'=>'sub',
                        'fee'=>'400',
                    ],
                    [
                        'khat_id'=>'117105',
                        'name'=>'মূলধন ৫ লক্ষ টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'117',
                        'type'=>'sub',
                        'fee'=>'2500',
                    ],













            [
                'khat_id'=>'118',
                'name'=>'রেস্তোঁরা, খাবার দোকান, মিষ্টির দোকান',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'118101',
                        'name'=>'মূলধন ১০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'118',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'118102',
                        'name'=>'মূলধন ১০ হাজার টাকা হইতে ২০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'118',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'118103',
                        'name'=>'মূলধন ২০ হাজার টাকা হইতে ৫০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'118',
                        'type'=>'sub',
                        'fee'=>'150',
                    ],
                    [
                        'khat_id'=>'118104',
                        'name'=>'মূলধন ৫০ হাজার টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'118',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],














            [
                'khat_id'=>'119',
                'name'=>'দোকানদার বা ব্যবসায়ী (খোলা জায়গায় যে সকল হকার্সগণ কেনাবেচা করেন, তাহারা ইহার অন্তর্ভুক্ত হইবেন না)',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'119101',
                        'name'=>'মূলধন নির্বিশেষে যে কোন পাইকারী দোকান',
                        'main_khat_id'=>'119',
                        'type'=>'sub',
                        'fee'=>'1000',
                    ],
                    [
                        'khat_id'=>'119102',
                        'name'=>'মূলধন ১০ হাজার টাকা পর্যন্ত',
                        'main_khat_id'=>'119',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'119103',
                        'name'=>'মূলধনের পরিমাণ ১০ হাজার টাকা হইতে ৫০ হাজার টাকা',
                        'main_khat_id'=>'119',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'119104',
                        'name'=>'মূলধনের পরিমাণ ৫০ হাজার টাকার ঊর্ধ্বে',
                        'main_khat_id'=>'119',
                        'type'=>'sub',
                        'fee'=>'150',
                    ],
















            [
                'khat_id'=>'120',
                'name'=>'ভাড়ায় চালিত যানবাহন',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'120101',
                        'name'=>'রিক্সার মালিক/প্রতিষ্ঠান (প্রতিটির জন্য )',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'20',
                    ],
                    [
                        'khat_id'=>'120102',
                        'name'=>'তিন চাকা বা দুই চাকাবিশিষ্ট যান্ত্রিক যানবাহনের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'120103',
                        'name'=>'টেম্পোর মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'150',
                    ],
                    [
                        'khat_id'=>'120104',
                        'name'=>'বাস, মিনিবাসের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'120105',
                        'name'=>'ট্রাক/কার্গোভ্যান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'120106',
                        'name'=>'পরিবহন এজেন্সী বা পরিবহন ঠিকাদার (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'300',
                    ],
                    [
                        'khat_id'=>'120107',
                        'name'=>'যাত্রী পরিবহনের যান্ত্রিক নৌযানের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'120108',
                        'name'=>'মালামাল পরিবহনের যান্ত্রিক নৌযানের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'150',
                    ],
                    [
                        'khat_id'=>'120109',
                        'name'=>'যাত্রী পরিবহনকারী লঞ্চ, স্টীমার (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],
                    [
                        'khat_id'=>'120110',
                        'name'=>'মালামাল পরিবহনকারী কার্গো (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'250',
                    ],
                    [
                        'khat_id'=>'120111',
                        'name'=>'কার বা মাইক্রোবাসের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'120',
                        'type'=>'sub',
                        'fee'=>'200',
                    ],














            [
                'khat_id'=>'121',
                'name'=>'ভাড়ায় চালিত নয় এইরূপ যানবাহন',
                'main_khat_id'=>'',
                'type'=>'main',
            ],
                    [
                        'khat_id'=>'121101',
                        'name'=>'রিক্সার মালিক/প্রতিষ্ঠান (প্রতিটির জন্য )',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'10',
                    ],
                    [
                        'khat_id'=>'121102',
                        'name'=>'তিন চাকা বা দুই চাকাবিশিষ্ট যান্ত্রিক যানবাহনের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'121103',
                        'name'=>'টেম্পোর মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'75',
                    ],
                    [
                        'khat_id'=>'121104',
                        'name'=>'বাস, মিনিবাসের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'125',
                    ],
                    [
                        'khat_id'=>'121105',
                        'name'=>'ট্রাক/কার্গোভ্যান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'125',
                    ],
                    [
                        'khat_id'=>'121106',
                        'name'=>'যান্ত্রিক নৌযানের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'50',
                    ],
                    [
                        'khat_id'=>'121107',
                        'name'=>'লঞ্চ, স্টীমার (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],
                    [
                        'khat_id'=>'121108',
                        'name'=>'কার বা মাইক্রোবাসের মালিক/প্রতিষ্ঠান (প্রতিটির জন্য)',
                        'main_khat_id'=>'121',
                        'type'=>'sub',
                        'fee'=>'100',
                    ],

        ];


        // return $insertDatas;

        $data = [];
        $khat_fee_id = 111111;
        foreach ($insertDatas as $value) {

            $insertdatas = [
                'khat_id'=>$value['khat_id'],
                'name'=>$value['name'],
                'main_khat_id'=>$value['main_khat_id'],
                'type'=>$value['type'],
            ];
            $insert =  TradeLicenseKhat::create($insertdatas);
            array_push($data,$insert);



            // $khatCount = TradeLicenseKhat::where('name',$value['name'])->count();
            // if($khatCount){
            //     $khatCount2 = TradeLicenseKhat::where('khat_id',$value['khat_id'])->count();
            //     if($khatCount2){
            //         array_push($data,'Khat id already inserted');
            //     }else{

            //         array_push($data,'Khat already inserted');
            //     }

            // }else{
            //     $khatCount2 = TradeLicenseKhat::where('khat_id',$value['khat_id'])->count();
            //     if($khatCount2){
            //         array_push($data,'Khat id already inserted');
            //     }else{

            //         $insertdatas = [
            //             'khat_id'=>$value['khat_id'],
            //             'name'=>$value['name'],
            //             'main_khat_id'=>$value['main_khat_id'],
            //             'type'=>$value['type'],
            //         ];
            //         $insert =  TradeLicenseKhat::create($insertdatas);
            //         array_push($data,$insert);
            //     }
            // }

            if($value['type']=='sub'){



                $datass = [
                    'khat_fee_id'=>$khat_fee_id,
                    'khat_id_1'=>$value['main_khat_id'],
                    'khat_id_2'=>$value['khat_id'],
                    'fee'=>$value['fee'],
                ];
                TradeLicenseKhatFee::create($datass);
                $khat_fee_id++;
            }







        }

        return $data;


return ;


        $khatCount = TradeLicenseKhat::where('name',$request->name)->count();
        if($khatCount){
            return 'Khat already inserted';
        }

        $khatCount2 = TradeLicenseKhat::where('khat_id',$request->khat_id)->count();
        if($khatCount2){
            return 'Khat id already inserted';
        }

        return TradeLicenseKhat::create($datas);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TradeLicenseKhat  $tradeLicenseKhat
     * @return \Illuminate\Http\Response
     */
    public function show(TradeLicenseKhat $tradeLicenseKhat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TradeLicenseKhat  $tradeLicenseKhat
     * @return \Illuminate\Http\Response
     */
    public function edit(TradeLicenseKhat $tradeLicenseKhat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TradeLicenseKhat  $tradeLicenseKhat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TradeLicenseKhat $tradeLicenseKhat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TradeLicenseKhat  $tradeLicenseKhat
     * @return \Illuminate\Http\Response
     */
    public function destroy(TradeLicenseKhat $tradeLicenseKhat)
    {
        //
    }
}
