<?php

use App\Models\Sonod;


function int_en_to_bn($number)
{

    $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    return str_replace($en_digits, $bn_digits, $number);
}
function int_bn_to_en($number)
{

    $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
    $en_digits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    return str_replace($bn_digits, $en_digits, $number);
}

function month_number_en_to_bn_text($number)
{
    $en = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    $bn = array('জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'অগাস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর');

    // Adjust the number to be within 1-12 range
    $number = max(1, min(12, $number));

    return str_replace($en, $bn, $number);
}

function month_name_en_to_bn_text($name)
{
    $en = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $bn = array('জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'অগাস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর');
    return str_replace($en, $bn, $name);
}

 function extractUrlFromIframe($iframe)
{
    $dom = new \DOMDocument();
    @$dom->loadHTML($iframe);

    $iframes = $dom->getElementsByTagName('iframe');
    if ($iframes->length > 0) {
        $src = $iframes->item(0)->getAttribute('src');
        return $src;
    }

    return $iframe;
}


function routeUsesMiddleware($route, $middlewareName)
{
   return $middlewares = $route->gatherMiddleware();

    foreach ($middlewares as $middleware) {
        if (preg_match("/^$middlewareName:/", $middleware)) {
            return true;
        }
    }

    return false;
}




function makeSonodId($union, $sonodname, $orthoBchor)
{
    $sonodFinalId = '';

    // Determine the sorting year based on current date
    $sortYear = date('y');
    if (date('m') < 7) {
        $sortYear -= 1;
    }
    // Retrieve Uniouninfo and Sonod counts
    $Uniouninfo = Uniouninfo::where('short_name_e', $union)->latest()->first();
    if ($Uniouninfo) {
        $Sonod = Sonod::where([
            'unioun_name' => $union,
            'sonod_name' => $sonodname,
            'orthoBchor' => $orthoBchor
        ])->latest()->first();

        // Determine next sonod_Id
        if ($Sonod) {
            $sonodFinalId = $Sonod->sonod_Id + 1;
        } else {
            // If no Sonod exists, create a new sonod_Id
            $sonod_Id = str_pad(1, 5, '0', STR_PAD_LEFT); // Start from 00001
            $sonodFinalId = $Uniouninfo->u_code . $sortYear . $sonod_Id;
        }
    }

    return $sonodFinalId;
}


 function convertToBanglaMoney($amount)
{
    $numToBangla = new NumberToBangla();
    return $numToBangla->bnMoney(int_bn_to_en($amount)) . ' মাত্র';
}
