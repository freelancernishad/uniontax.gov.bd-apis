<?php

use App\Models\Sonod;
use App\Models\Uniouninfo;
use App\Models\Sonodnamelist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Rakibhstu\Banglanumber\NumberToBangla;


function unionname($unionname){
    return  $sonodList = Uniouninfo::where(['short_name_e'=>$unionname])->first();

  }

  function sonodEnName($sonodBnName){
    $sonodList = Sonodnamelist::where(['bnname'=>$sonodBnName])->first();
    return  str_replace(" ", "_", $sonodList->enname);
}

function ekpayToken($trnx_id=123456789,$trns_info=[],$cust_info=[],$path='payment',$unioun_name=''){


    $url = env('AKPAY_IPN_URL');


   $req_timestamp = date('Y-m-d H:i:s');

 $uniounDetials =  unionname($unioun_name);
 $AKPAY_MER_REG_ID = $uniounDetials->AKPAY_MER_REG_ID;
$AKPAY_MER_PASS_KEY = $uniounDetials->AKPAY_MER_PASS_KEY;

    if($AKPAY_MER_REG_ID=='tetulia_test'){
        $Apiurl = 'https://sandbox.ekpay.gov.bd/ekpaypg/v1';
        $whitelistip = '1.1.1.1';
    }else{
        $Apiurl = env('AKPAY_API_URL');
        $whitelistip = env('WHITE_LIST_IP');
    }


   $post = [
      'mer_info' => [
         "mer_reg_id" => $AKPAY_MER_REG_ID,
         "mer_pas_key" => $AKPAY_MER_PASS_KEY
      ],
      "req_timestamp" => "$req_timestamp GMT+6",
      "feed_uri" => [
         "c_uri" => url("$path/cancel"),
         "f_uri" => url("$path/fail"),
         "s_uri" => url("$path/success")
      ],
      "cust_info" => $cust_info,
      "trns_info" =>$trns_info,
      "ipn_info" => [
         "ipn_channel" => "3",
         "ipn_email" => "freelancernishad123@gmail.com",
         "ipn_uri" => "$url/api/ipn"
      ],
      "mac_addr" => "$whitelistip"
   ];

   // 148.163.122.80
   $post = json_encode($post);
   Log::info($post);

   $ch = curl_init($Apiurl.'/merchant-api');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
   $response = curl_exec($ch);
   curl_close($ch);

/*      echo '<pre>';
   print_r($response); */

   Log::info($response);
     $response = json_decode($response);
   $sToken =  $response->secure_token;


   return "$Apiurl?sToken=$sToken&trnsID=$trnx_id";

//  return    'https://sandbox.ekpay.gov.bd/ekpaypg/v1?sToken=eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJla3BheWNvcmUiLCJhdXRoIjoiUk9MRV9NRVJDSEFOVCIsImV4cCI6MTU0NTMyMjcxMn0.lqjBuvtqyUbhy4pteKa0IaqpjYQoEDjjnJWSFwcv0Ho2JJHN-8xqr8Q7r-tIJUy_dLajS2XbmrR6lBGrlGFYhQ&trnsID=1234'


//   return "https://sandbox.ekpay.gov.bd/ekpaypg/v1?sToken=$sToken&trnsID=$trnx_id";

}




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


function pushNotification($data){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$data,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: key=AAAA-EA0BlM:APA91bEjaymOOGtnp1u9K7RymKyswgYqkI390pCj2R63ritYAHWmYbdI5D9O9h7XB6G6ADa3Nk9sZg9SDCWkwreJnrvcjGGOEI6_euAbgHezKblGxD68_CJEZdLOhyfafJ0u4ZKxQD9D'
      ),
    ));

     $response = curl_exec($curl);

    curl_close($curl);
        }



function SmsNocSmsSend($deccription = '', $applicant_mobile = '01909756552',$union='test')
{
    $unionInfoCount = Uniouninfo::where('short_name_e',$union)->count();
    if(!$unionInfoCount){
        return 'union not found';
    }

    $unionInfo = Uniouninfo::where('short_name_e',$union)->first();
    $smsBalance = $unionInfo->smsBalance;
    if(!$smsBalance){
        return 'you dont have balace';
    }

    $smsnocapikey = '108|DuxHEDfb1kQKISfSZCJ980XfCKQ2mpwvCCLThVqf ';
    $smsnocsenderid = '8809617611301';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smsnoc.com/api/v3/sms/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"recipient":"88'.$applicant_mobile.'",
"sender_id":"'.$smsnocsenderid.'",
"type":"plain",
"message":"'.$deccription.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer '.$smsnocapikey.''
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$unionInfo->update(['smsBalance'=>$smsBalance-1]);
echo $response;

}



function changeSonodName($name){
    if($name=='ওয়ারিশান সনদ'){
        return 'ওয়ারিশ সনদ';
    }elseif($name=='বিবিধ প্রত্যয়নপত্র'){
        return 'প্রত্যয়নপত্র';
    }else{
        return $name;
    }
}


function base642($Image)
{
    $url = $Image;
    $image = file_get_contents($url);
    if ($image !== false){
        return 'data:image/jpg;base64,'.base64_encode($image);

    }
}

function base64($Image)
{
//  return $Image;

    if(File::exists(env('FILE_PATH').$Image)){

        $Image= env('FILE_PATH').$Image;
    }else{
        $Image= env('FILE_PATH').'backend/image.png';

    }

$ext =  pathinfo($Image, PATHINFO_EXTENSION);;
    return $b64image = "data:image/$ext;base64,".base64_encode(file_get_contents($Image));
}


function holdingTaxAmount($category = 'মালিক নিজে বসবাসকারী', $griher_barsikh_mullo = 0, $jomir_vara = 0, $barsikh_vara = 0)
{
    // Convert Bengali numbers to English for calculation if needed
    $griher_barsikh_mullo = int_bn_to_en($griher_barsikh_mullo);
    $jomir_vara = int_bn_to_en($jomir_vara);
    $barsikh_vara = int_bn_to_en($barsikh_vara);

    // Calculate holding tax based on category
    switch ($category) {
        case 'মালিক নিজে বসবাসকারী':
        case 'প্রতিষ্ঠান':
        case 'আংশিক ভাড়া':
            // Calculate common variables
            $barsikh_muller_percent = ($griher_barsikh_mullo * 7.5) / 100;
            $total_mullo = $jomir_vara + $barsikh_muller_percent;
            $rokhona_bekhon_khoroch = $total_mullo / 6;
            $prakklito_mullo = $total_mullo - $rokhona_bekhon_khoroch;
            $reyad = $prakklito_mullo / 4;
            $prodey_korjoggo_barsikh_mullo = $prakklito_mullo - $reyad;

            // Calculate specific variables based on category
            if ($category == 'আংশিক ভাড়া') {
                $angsikh_prodoy_korjoggo_barsikh_mullo = $prakklito_mullo - $reyad;
                $rokhona_bekhon_khoroch_percent = $barsikh_vara / 6;
                $prodey_korjoggo_barsikh_varar_mullo = $barsikh_vara - $rokhona_bekhon_khoroch_percent;
                $total_prodey_korjoggo_barsikh_mullo = $angsikh_prodoy_korjoggo_barsikh_mullo + $prodey_korjoggo_barsikh_varar_mullo;
            }

            $current_year_kor = ($prodey_korjoggo_barsikh_mullo * 7) / 100;
            break;

        case 'ভাড়া':
            $rokhona_bekhon_khoroch_percent = $barsikh_vara / 6;
            $prodey_korjoggo_barsikh_varar_mullo = $barsikh_vara - $rokhona_bekhon_khoroch_percent;
            $current_year_kor = ($prodey_korjoggo_barsikh_varar_mullo * 7) / 100;
            break;

        default:
            // Default case handles unexpected category values
            $current_year_kor = 0;
            break;
    }

    // Ensure the minimum holding tax is 500
    if ($current_year_kor >= 500) {
        $current_year_kor = 500;
    }

    // Convert the result to Bengali and return
    return int_bn_to_en((int)$current_year_kor);
}
