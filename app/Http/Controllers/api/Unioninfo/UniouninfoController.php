<?php

namespace App\Http\Controllers\API\Unioninfo;

use App\Http\Controllers\Controller;

use App\Models\Uniouninfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UniouninfoController extends Controller
{


    public function unionservicecheck($union)
    {

        $Uniouninfo = Uniouninfo::where('short_name_e',$union)->first();

         $nidServicestatus = (int)$Uniouninfo->nidServicestatus;
         $nidServicebalace = (int)$Uniouninfo->nidService;

        return ['nidServicestatus'=>$nidServicestatus,'nidServicebalace'=>$nidServicebalace];


    }


    public function unioncheck($union)
    {

        $Uniouninfo = Uniouninfo::where('short_name_e',$union)->first();

        $nidServiceOld = (int)$Uniouninfo->nidService;
        if($nidServiceOld>0){
            $nidService = (int)$Uniouninfo->nidService-1;
            $Uniouninfo->update(['nidService'=>$nidService]);
            return $Uniouninfo;
        }else{
            return 404;
        }

    }


    public function apicall($url,$data,$method=true)
    {


if($method==false){


    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    return   $response = json_decode($resp);
}else{
        // $url = "https://premium36.web-hosting.com:2083/login/?login_only=1";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);



        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
     return   $response = json_decode($resp);
}





    }

    public function unionCreate(Request $request)
    {



        $arrary = [
            // তেঁতুলিয়া
            [
                "unioun"=>"tetulia",
                "names"=>"Chairman",
                "email"=>"upctetulia@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],


            [
                "unioun"=>"salbahan",
                "names"=>"Chairman",
                "email"=>"upcsalbahan@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"buraburi",
                "names"=>"Chairman",
                "email"=>"upcburaburi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"bhojoanpur",
                "names"=>"Chairman",
                "email"=>"upcbhojoanpur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"debnagar",
                "names"=>"Chairman",
                "email"=>"upcdebnagar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            // পঞ্চগড় সদর
            [
                "unioun"=>"panchagarhsadar",
                "names"=>"Chairman",
                "email"=>"upcpanchagarhsadar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"satmara",
                "names"=>"Chairman",
                "email"=>"upcsatmara@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"amarkhana",
                "names"=>"Chairman",
                "email"=>"upcamarkhana@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"haribhasa",
                "names"=>"Chairman",
                "email"=>"upcharibhasa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"chaklahat",
                "names"=>"Chairman",
                "email"=>"upcchaklahat@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"hafizabad",
                "names"=>"Chairman",
                "email"=>"upchafizabad@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"kamatkajoldighi",
                "names"=>"Chairman",
                "email"=>"upckamatkajoldighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"dhakkamara",
                "names"=>"Chairman",
                "email"=>"upcdhakkamara@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"magura",
                "names"=>"Chairman",
                "email"=>"upcmagura@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"garinabari",
                "names"=>"Chairman",
                "email"=>"upcgarinabari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],


            // আটোয়ারী
            [
                "unioun"=>"mirgapur",
                "names"=>"Chairman",
                "email"=>"upcmirgapur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"radhanagar",
                "names"=>"Chairman",
                "email"=>"upcradhanagar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"toria",
                "names"=>"Chairman",
                "email"=>"upctoria@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"balarampur",
                "names"=>"Chairman",
                "email"=>"upcbalarampur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"alowakhowa",
                "names"=>"Chairman",
                "email"=>"upcalowakhowa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"dhamor",
                "names"=>"Chairman",
                "email"=>"upcdhamor@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],



            // বোদা
            [
                "unioun"=>"jholaishalshiri",
                "names"=>"Chairman",
                "email"=>"upcjholaishalshiri@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"moidandighi",
                "names"=>"Chairman",
                "email"=>"upcmoidandighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"banghari",
                "names"=>"Chairman",
                "email"=>"upcbanghari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"kajoldighikaligonj",
                "names"=>"Chairman",
                "email"=>"upckajoldighikaligonj@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"boroshoshi",
                "names"=>"Chairman",
                "email"=>"upcboroshoshi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"chandanbari",
                "names"=>"Chairman",
                "email"=>"upcchandanbari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"mareabamonhat",
                "names"=>"Chairman",
                "email"=>"upcmareabamonhat@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"boda",
                "names"=>"Chairman",
                "email"=>"upcboda@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"sakoa",
                "names"=>"Chairman",
                "email"=>"upcsakoa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"pachpir",
                "names"=>"Chairman",
                "email"=>"upcpachpir@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],


            // দেবীগঞ্জ

            [
                "unioun"=>"chilahati",
                "names"=>"Chairman",
                "email"=>"upcchilahati@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"shaldanga",
                "names"=>"Chairman",
                "email"=>"upcshaldanga@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"debiganjsadar",
                "names"=>"Chairman",
                "email"=>"upcdebiganjsadar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"pamuli",
                "names"=>"Chairman",
                "email"=>"upcpamuli@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"sundardighi",
                "names"=>"Chairman",
                "email"=>"upcsundardighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"sonaharmollikadaha",
                "names"=>"Chairman",
                "email"=>"upcsonaharmollikadaha@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"tepriganj",
                "names"=>"Chairman",
                "email"=>"upctepriganj@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"dandopal",
                "names"=>"Chairman",
                "email"=>"upcdandopal@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"debiduba",
                "names"=>"Chairman",
                "email"=>"upcdebiduba@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"chengthihazradanga",
                "names"=>"Chairman",
                "email"=>"upcchengthihazradanga@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Chairman",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],






            // তেঁতুলিয়া Secretary
            [
                "unioun"=>"tetulia",
                "names"=>"Secretary",
                "email"=>"upstetulia@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],


            [
                "unioun"=>"salbahan",
                "names"=>"Secretary",
                "email"=>"upssalbahan@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"buraburi",
                "names"=>"Secretary",
                "email"=>"upsburaburi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"bhojoanpur",
                "names"=>"Secretary",
                "email"=>"upsbhojoanpur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            [
                "unioun"=>"debnagar",
                "names"=>"Secretary",
                "email"=>"upsdebnagar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"তেঁতুলিয়া",
                "role"=>1
            ],
            // পঞ্চগড় সদর
            [
                "unioun"=>"panchagarhsadar",
                "names"=>"Secretary",
                "email"=>"upspanchagarhsadar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"satmara",
                "names"=>"Secretary",
                "email"=>"upssatmara@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"amarkhana",
                "names"=>"Secretary",
                "email"=>"upsamarkhana@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"haribhasa",
                "names"=>"Secretary",
                "email"=>"upsharibhasa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"chaklahat",
                "names"=>"Secretary",
                "email"=>"upschaklahat@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"hafizabad",
                "names"=>"Secretary",
                "email"=>"upshafizabad@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"kamatkajoldighi",
                "names"=>"Secretary",
                "email"=>"upskamatkajoldighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"dhakkamara",
                "names"=>"Secretary",
                "email"=>"upsdhakkamara@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"magura",
                "names"=>"Secretary",
                "email"=>"upsmagura@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],
            [
                "unioun"=>"garinabari",
                "names"=>"Secretary",
                "email"=>"upsgarinabari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"পঞ্চগড় সদর",
                "role"=>1
            ],


            // আটোয়ারী
            [
                "unioun"=>"mirgapur",
                "names"=>"Secretary",
                "email"=>"upsmirgapur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"radhanagar",
                "names"=>"Secretary",
                "email"=>"upsradhanagar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"toria",
                "names"=>"Secretary",
                "email"=>"upstoria@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"balarampur",
                "names"=>"Secretary",
                "email"=>"upsbalarampur@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"alowakhowa",
                "names"=>"Secretary",
                "email"=>"upsalowakhowa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],      [
                "unioun"=>"dhamor",
                "names"=>"Secretary",
                "email"=>"upsdhamor@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"আটোয়ারী",
                "role"=>1
            ],



            // বোদা
            [
                "unioun"=>"jholaishalshiri",
                "names"=>"Secretary",
                "email"=>"upsjholaishalshiri@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"moidandighi",
                "names"=>"Secretary",
                "email"=>"upsmoidandighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"banghari",
                "names"=>"Secretary",
                "email"=>"upsbanghari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"kajoldighikaligonj",
                "names"=>"Secretary",
                "email"=>"upskajoldighikaligonj@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"boroshoshi",
                "names"=>"Secretary",
                "email"=>"upsboroshoshi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"chandanbari",
                "names"=>"Secretary",
                "email"=>"upschandanbari@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"mareabamonhat",
                "names"=>"Secretary",
                "email"=>"upsmareabamonhat@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"boda",
                "names"=>"Secretary",
                "email"=>"upsboda@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"sakoa",
                "names"=>"Secretary",
                "email"=>"upssakoa@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],
            [
                "unioun"=>"pachpir",
                "names"=>"Secretary",
                "email"=>"upspachpir@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"বোদা",
                "role"=>1
            ],


            // দেবীগঞ্জ

            [
                "unioun"=>"chilahati",
                "names"=>"Secretary",
                "email"=>"upschilahati@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"shaldanga",
                "names"=>"Secretary",
                "email"=>"upsshaldanga@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"debiganjsadar",
                "names"=>"Secretary",
                "email"=>"upsdebiganjsadar@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"pamuli",
                "names"=>"Secretary",
                "email"=>"upspamuli@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"sundardighi",
                "names"=>"Secretary",
                "email"=>"upssundardighi@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"sonaharmollikadaha",
                "names"=>"Secretary",
                "email"=>"upssonaharmollikadaha@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"tepriganj",
                "names"=>"Secretary",
                "email"=>"upstepriganj@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"dandopal",
                "names"=>"Secretary",
                "email"=>"upsdandopal@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"debiduba",
                "names"=>"Secretary",
                "email"=>"upsdebiduba@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
            [
                "unioun"=>"chengthihazradanga",
                "names"=>"Secretary",
                "email"=>"upschengthihazradanga@gmail.com",
                "phone"=>"01909756552",
                "password"=>"uheba21",
                "position"=>"Secretary",
                "district"=>"পঞ্চগড়",
                "thana"=>"দেবীগঞ্জ",
                "role"=>1
            ],
        ];



        foreach ($arrary as $value) {
            $data = [
                "unioun"=>$value["unioun"],
                "names"=>$value["names"],
                "email"=>$value["email"],
                "phone"=>$value["phone"],
                "password"=> hash::make($value["password"]),
                "position"=>$value["position"],
                "district"=>$value["district"],
                "thana"=>$value["thana"],
                "role"=>$value["role"]
            ];

           User::create($data);
        }

        // return $arrary;



die();

           $data = $request->except('_token');

         $short_name_e = $request->short_name_e;
         $unionCount = Uniouninfo::where(['short_name_e'=>$short_name_e])->count();
        if($unionCount>0){
            return 'Already Created';
        }

    //    $login =  $this->apicall('https://premium36.web-hosting.com:2083/login/?login_only=1','{"user":"uniothcm","pass":"uybFmeUrZwHk"}',true);
    //     $security_token = $login->security_token;


    //     $url = "https://premium36.web-hosting.com:2083$security_token/frontend/paper_lantern/subdomain/doadddomain.html?domain=$short_name_e&rootdomain=uniontax.gov.bd&dir=public_html&go=Create";
    //    $curl = curl_init($url);
    //    curl_setopt($curl, CURLOPT_URL, $url);
    //    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //    //for debug only!
    //    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    //    $resp = curl_exec($curl);
    //    curl_close($curl);


        //  return $subdomain =  $this->apicall("https://premium36.web-hosting.com:2083/cpsess0351997767/frontend/paper_lantern/subdomain/doadddomain.html?domain=demo2&rootdomain=uniontax.gov.bd&dir=demo2.uniontax.gov.bd&go=Create",'',false);

        // return $security_token = $login->security_token;

        $data['domain'] = "www.$short_name_e.uniontax.gov.bd";
         return Uniouninfo::create($data);

    }





        public function contact(Request $request)
        {

            $Uniouninfo =  Uniouninfo::where(['short_name_e'=>$request->union])->first();

            // return $request->all();

            $details = [
                'title' => "Mail from Name: $request->name Email: <a href='mailto:$request->email'>$request->email</a>",
                'body' => "$request->message"
            ];

            \Mail::to("$Uniouninfo->contact_email")->send(new \App\Mail\MyTestMail($details));

            // dd("Email is Sent.");

        }

        public function unionInfo(Request $request)
        {
            // return $request->all();
          $uniouninfo =   Uniouninfo::where(['short_name_e'=>$request->union])->first();

          $Insertdata = $uniouninfo;
          $Insertdata['socib_signture'] =  asset($uniouninfo->socib_signture);
          $Insertdata['c_signture'] =  asset($uniouninfo->c_signture);
            $Insertdata['sonod_logo'] =  asset($uniouninfo->sonod_logo);
            $Insertdata['u_image'] =  asset($uniouninfo->u_image);
            $Insertdata['web_logo'] =  asset($uniouninfo->web_logo);
return $Insertdata;
        }


        public function unionInfoUpdate(Request $request)
        {
            $id = $request->id;

            $Insertdata = $request->except(['socib_signture','c_signture','sonod_logo','u_image','web_logo']);

            $socib_signtureCount =  count(explode(';',$request->socib_signture));
            $c_signtureCount =  count(explode(';',$request->c_signture));
            $sonod_logoCount =  count(explode(';',$request->sonod_logo));
            $u_imageCount =  count(explode(';',$request->u_image));
            $web_logoCount =  count(explode(';',$request->web_logo));

            if($socib_signtureCount>1){
                $Insertdata['socib_signture'] =  fileupload($request->socib_signture,"unioninfo/socib_signture/");
            }
            if($c_signtureCount>1){
                $Insertdata['c_signture'] =  fileupload($request->c_signture,"unioninfo/c_signture/");
            }
            if($sonod_logoCount>1){
                $Insertdata['sonod_logo'] =  fileupload($request->sonod_logo,"unioninfo/sonod_logo/");
            }
            if($u_imageCount>1){
                $Insertdata['u_image'] =  fileupload($request->u_image,"unioninfo/u_image/");
            }
            if($web_logoCount>1){
                $Insertdata['web_logo'] =  fileupload($request->web_logo,"unioninfo/web_logo/");
            }


            $uniouninfo =  Uniouninfo::find($id);
            $uniouninfo->update($Insertdata);
            return $request->all();
        //   return   Uniouninfo::where(['short_name_e'=>$request->union])->first();
        }
        public function paymentUpdate(Request $request)
        {
            $paymentType =  $request->paymentType;
            $district =  $request->district;
             $unions =  Uniouninfo::where('district',$district)->get();

            foreach ($unions as $value) {
                // return $value->id;
               $singleunion = Uniouninfo::find($value->id);
               $singleunion->update(['payment_type'=>$paymentType]);
            }
            return 'Payment Type Updated';

        }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $position =$request->position;
         $userid =$request->userid;

        //  $thana = $request->thana;
        //  $district = $request->district;


        if($userid){
            $user = User::find($userid);
            $position = $user->position;
        }



        if ($position) {

            $user = User::find($request->userid);
         $thana = $user->thana;
         $district = $user->district;

            if($position=='District_admin' || $position=='super_admin' || $position=='Sub_District_admin'){

                return Uniouninfo::where('district', $district)->get();



            }else
            if($position=='Thana_admin'){
                return Uniouninfo::where(['district'=> $district,'thana'=> $thana])->get();

            }else{
                return '';
            }


        }
        return Uniouninfo::all();
    }
    public function getunion(Request $request, $id)
    {
            // return $request->all();
            $uniouninfo =   Uniouninfo::find($id);

            $data = $uniouninfo;
            $data['c_signture'] =  asset($uniouninfo->c_signture);
              $data['sonod_logo'] =  asset($uniouninfo->sonod_logo);
              $data['u_image'] =  asset($uniouninfo->u_image);
              $data['web_logo'] =  asset($uniouninfo->web_logo);
  return $data;
    }
    public function deleteunion(Request $request, $id)
    {
        $sonodnamelist =  Uniouninfo::find($id);
        $sonodnamelist->delete();
        return 'Union deleted!';
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Uniouninfo  $uniouninfo
     * @return \Illuminate\Http\Response
     */
    public function show(Uniouninfo $uniouninfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Uniouninfo  $uniouninfo
     * @return \Illuminate\Http\Response
     */
    public function edit(Uniouninfo $uniouninfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Uniouninfo  $uniouninfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Uniouninfo $uniouninfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Uniouninfo  $uniouninfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Uniouninfo $uniouninfo)
    {
        //
    }
}
