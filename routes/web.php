<?php

use App\Models\Article;
use App\Models\HoldingTax;
use App\Models\HoldingBokeya;
use App\Services\DateService;
use App\Services\ContentService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\Sonod\SonodController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});




require __DIR__.'/auth.php';





Route::get('/holding/tax/update/{union}', function ($unioun) {

    $holdingtaxs =  HoldingTax::with(['holdingBokeyas' => function ($query) use ($unioun) {
        $query->where(['year'=>'2023-2024','price'=>0]);

    }])->where('unioun', $unioun)->orderBy('id','desc')->get();


    $holdingtaxs =  $filteredHoldingTaxs = $holdingtaxs->filter(function ($holdingTax) {
        return !$holdingTax->holdingBokeyas->isEmpty();
    });

    $html = "
        <table border='1' width='100%'>
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Holding No</th>
                    <th>Name</th>
                    <th>Word no</th>
                    <th>NId</th>
                    <th>Mobile</th>
                    <th>griher_barsikh_mullo</th>
                    <th>Return Holding</th>
                </tr>
            </thead>
            <tbody>


    ";
    // return $holdingtaxs;
    $i = 1;
    foreach ($holdingtaxs as $value) {


       $griher_barsikh_mullo =  $value->griher_barsikh_mullo ? int_bn_to_en($value->griher_barsikh_mullo) : 0;
       $jomir_vara =  $value->jomir_vara ? int_bn_to_en($value->jomir_vara) : 0;
       $barsikh_vara =  $value->barsikh_vara ? int_bn_to_en($value->barsikh_vara) : 0;


            $currentYearAmount =  holdingTaxAmount($value->category,$griher_barsikh_mullo,$jomir_vara,$barsikh_vara);

            foreach ($value->holdingBokeyas as $hB) {
                $bokeyaUpdate = HoldingBokeya::find($hB->id);
                 $bokeyaUpdate->update(['price'=>$currentYearAmount]);
            }



            // return $value->holdingBokeyas;



            // if($currentYearAmount<1){
                $html .= "

                <tr>
                    <td>$i</td>
                    <td>$value->holding_no</td>
                    <td>$value->gramer_name</td>
                    <td>$value->word_no</td>
                    <td>$value->nid_no</td>
                    <td>$value->mobile_no</td>
                    <td>$value->griher_barsikh_mullo</td>
                    <td>$currentYearAmount</td>
                </tr>
                ";
                $i++;
            // }
            // print_r("$currentYearAmount <br/>");




    }

$html .= "

        </tbody>

        </table>
        ";

        echo $html;
    // $holdingBokeya =  HoldingBokeya::where(['year'=>'2022-2023'])->get();
    // foreach ($holdingBokeya as $value) {

    //     $insertData = [
    //         "holdingTax_id"=> $value->holdingTax_id,
    //         "year"=> "2023-2024",
    //         "price"=> $value->price,
    //         "status"=> "Unpaid",
    //     ];
    //     HoldingBokeya::create($insertData);
    // }

    // 47085
   });

Route::get('/holding/tax/renew', function () {
    $holdingBokeya =  HoldingBokeya::where(['year'=>'2022-2023'])->get();
    foreach ($holdingBokeya as $value) {

        $insertData = [
            "holdingTax_id"=> $value->holdingTax_id,
            "year"=> "2023-2024",
            "price"=> $value->price,
            "status"=> "Unpaid",
        ];
        HoldingBokeya::create($insertData);
    }

    // 47085
   });



Route::get('/pdf/tenders/{tender_id}', [TenderListController::class,'viewpdf']);


















Route::get('/tenders/form/buy/{tender_id}', function ($tender_id) {


    $tender_list_count = TenderList::where('tender_id',$tender_id)->count();
    if($tender_list_count<1){
        return '<h1 style="text-align:center;color:red">কোনও তথ্য খুজে পাওয়া জায় নি</h1>';
    }

    $tender_list = TenderList::where('tender_id',$tender_id)->first();

      $currentDate = strtotime(date("d-m-Y H:i:s"));

    $form_buy_last_date = strtotime(date("d-m-Y H:i:s",strtotime($tender_list->form_buy_last_date)));



   if($currentDate<$form_buy_last_date){

    $tender_list->update(['status'=>'active']);
       return view('tender.formbuy',compact('tender_list'));

    }else{

        return '<h1 style="text-align:center;color:red">সিডিউল ফর্ম কেনার সময় শেষ</h1>';
   }





});




Route::get('/tenders/payment/{id}', [TenderListController::class,'PaymentCreate']);


Route::get('/tenders/{tender_id}', [TenderListController::class,'TenderForm']);
Route::post('/tenders/{tender_id}', [TenderListController::class,'TenderForm']);




Route::post('/form/submit', function (Request $request) {

        $data = $request->except(['_token','bank_draft_image','deposit_details']);
        $bank_draft_image = $request->file('bank_draft_image');
        $extension = $bank_draft_image->getClientOriginalExtension();
        $path = public_path('files/bank_draft_image/');
        $fileName = $request->dorId.'-'.uniqid().'.'.$extension;
        $bank_draft_image->move($path, $fileName);
        $bank_draft_image = asset('files/bank_draft_image/'.$fileName);





        // $deposit_details = $request->file('deposit_details');
        // $extension = $deposit_details->getClientOriginalExtension();
        // $path = public_path('files/deposit_details/');
        // $fileName = $request->dorId.'-'.uniqid().'.'.$extension;
        // $deposit_details->move($path, $fileName);
        // $deposit_details = asset('files/deposit_details/'.$fileName);
        // $data['deposit_details'] = $deposit_details;



        $data['bank_draft_image'] = $bank_draft_image;
        $data['payment_status'] = 'Unpaid';





      $tender =  Tender::create($data);
      Session::flash('Smessage', 'আপনার দরপত্রটি দাখিল হয়েছে');

      return redirect("/tenders/payment/$tender->id");

    //   return redirect()->back();


    });


Route::get('/pdf/sder/download/{tender_id}', function (Request $request,$tender_id) {

    $html = '
    <style>
    td{
        border: 1px solid black;
        padding:4px 10px;
        font-size: 14px;
    }    th{
        border: 1px solid black;
        padding:4px 10px;
        font-size: 14px;
    }
    </style>
        <p style="text-align:center;font-size:25px">দরপত্র দাখিল কারীর তালিকা</p>


    <table class="table" border="1" style="border-collapse: collapse;width:100%">
    <thead>
        <tr>
        <td>দরপত্র নম্বর</td>
        <td>নাম</td>
        <td>পিতার নাম</td>
        <td>ঠিকানা</td>
        <td>মোবাইল</td>
        <td>দরের পরিমাণ</td>
        <td>কথায়</td>
        <td>জামানতের পরিমাণ</td>
        </tr>
    </thead>
    <tbody>';
            $tenders =  Tender::where('tender_id',$tender_id)->get();
        foreach ($tenders as $application) {


        $html .= " <tr>
            <td>$application->dorId</td>
            <td>$application->applicant_orgName</td>
            <td>$application->applicant_org_fatherName</td>
            <td>গ্রাম- $application->vill, ডাকঘর- $application->postoffice, উপজেলা- $application->thana, জেলা- $application->distric</td>
            <td>$application->mobile</td>
            <td>$application->DorAmount</td>
            <td>$application->DorAmountText</td>
            <td>$application->depositAmount</td>
        </tr>";
    }


        $html .= '

    </tbody>
    </table>



    ';
       return PdfMaker('A4',$html,'list',false);



    });





















Route::get('/invoice/view/{id}', [DynamicPDFController::class,'viewpdf']);


Route::get('/pdf/download/{Sname}/{id}', [DynamicPDFController::class,'pdf']);


Route::get('/invoices/{id}', [DynamicPDFController::class,'convert_customer_data_to_html']);

Route::get('/pdfC', [DynamicPDFController::class,'pdfC']);






Route::get('/akpay/test', function () {


    $trnx_id = time();

    $Apiurl = 'https://pg.ekpay.gov.bd/ekpaypg/v1';
    $url = 'https://uniontax.gov.bd';

    $whitelistip = '192.64.117.77';
   $req_timestamp = date('Y-m-d H:i:s');

 $AKPAY_MER_REG_ID = 'tetulia01_mer';
$AKPAY_MER_PASS_KEY = 'Tet@merEK091';





   $post = [
      'mer_info' => [
         "mer_reg_id" => $AKPAY_MER_REG_ID,
         "mer_pas_key" => $AKPAY_MER_PASS_KEY
      ],
      "req_timestamp" => "$req_timestamp GMT+6",
      "feed_uri" => [
         "c_uri" => url("payment/cancel"),
         "f_uri" => url("payment/fail"),
         "s_uri" => url("payment/success")
      ],
      "cust_info" => [
        "cust_email" => "",
        "cust_id" => "1",
        "cust_mail_addr" => "Address",
        "cust_mobo_no" => "01909756552",
        "cust_name" => "Customer Name"
    ],
      "trns_info" =>[
        "ord_det" => 'sonod',
        "ord_id" => "1",
        "trnx_amt" => 10,
        "trnx_currency" => "BDT",
        "trnx_id" => "$trnx_id"
    ],
      "ipn_info" => [
         "ipn_channel" => "3",
         "ipn_email" => "freelancernishad123@gmail.com",
         "ipn_uri" => "$url/api/ipn"
      ],
      "mac_addr" => "$whitelistip"
   ];

   // 148.163.122.80
   $post = json_encode($post);


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


  return   $response = json_decode($response);
   $sToken =  $response->secure_token;


   return "$Apiurl?sToken=$sToken&trnsID=$trnx_id";









});





Route::get('/textemail', function () {

    $year = date('Y');
    $date = date('m');
    $orthobochor = '';
    if($date<7){
        $orthobochor = ($year-1)."-".date('y');
    }else{
        $orthobochor = $year."-".date('y')+1;
    }

    return $orthobochor;
    die();


    $deccriptionEmail = "Your application no. 77349852200010 has been approved. Document is available at https://test.uniontax.gov.bd/sonod/d/22425";
    $emaildata= ['deccription'=>$deccriptionEmail];
return view('email_view',$emaildata);


});

Route::get('/smstest', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
    $subject = 'hello subject';

    \Mail::to('freelancernishad123@gmail.com')->send(new \App\Mail\MyTestMail($details,$subject));

    dd("Email is Sent.");


});

Route::get('details',[NotificationsController::class,'details']);




Route::get('/unioncreate', function () {

return view('unioncreate');


});

Route::post('unionCreate', [UniouninfoController::class,'unionCreate']);
// Auth::routes([
//     'login'=>false,
// ]);
Route::post('login',[LoginController::class,'login']);
Route::post('logout',[LoginController::class,'logout']);
// Route::group(['middleware' => ['is_admin']], function() {
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// });
// Route::group(['middleware' => ['CustomerMiddleware']], function() {
// Route::get('/sub', [App\Http\Controllers\HomeController::class, 'sub'])->name('sub');
// });






Route::get('/sonod/payment/success/{id}', [SonodController::class,'sonodpaymentSuccessView']);


Route::get('/payment/success', function (Request $request) {
    // return $request->all();
    $transId = $request->transId;

    $payment = Payment::where(['trxId' => $transId])->first();


    if($payment->sonod_type=='holdingtax'){
        $redirect = "/holdingPay/success?transId=$transId";
    }elseif($payment->sonod_type=='Tenders_form'){
        $redirect = "/tenderformpay/success?transId=$transId";
    }elseif($payment->sonod_type=='tender-deposit'){
        $redirect = "/tenderdeposit/success?transId=$transId";
    }else{
        $sonod = Sonod::find($payment->sonodId);
        if($sonod->pBy=='Pre Pay'){
            $redirect = "/applications/final/form?sToken=$sonod->uniqeKey";
        }else{
            $redirect = "/payment/success/confirm?transId=$transId";
        }
    }


// return;
    echo "
    <h3 style='text-align:center'>Please wait 10 seconds.This page will auto redirect you</h3>
    <script>
    setTimeout(() => {
    window.location.href='$redirect'
    }, 10000);
    </script>
    ";
    // return redirect("/payment/success/confirm?transId=$transId");
});


Route::get('/payment/success/confirm', [SonodController::class,'sonodpaymentSuccess']);




Route::get('/sonod/payment/{id}', [SonodController::class,'sonodpayment']);


Route::get('/sonod/{name}/{id}', [SonodController::class,'sonodDownload']);
Route::get('/invoice/{name}/{id}', [SonodController::class,'invoice']);
Route::get('/document/{name}/{id}', [SonodController::class,'userDocument']);
Route::get('/pay/holding/tax/{id}', [HoldingtaxController::class,'holding_tax_pay_Online']);


Route::get('/holdingPay/success', [HoldingtaxController::class,'holdingPaymentSuccess']);


Route::get('/tenderformpay/success', [TenderFormBuyController::class,'tenderFormPaymentSuccess']);
Route::get('/tenderdeposit/success', [TenderFormBuyController::class,'tenderdepositPaymentSuccess']);



Route::get('holding/tax/bokeya/list',[HoldingtaxController::class,'bokeyaReport']);



Route::get('/holding/tax/certificate_of_honor/{id}', [HoldingtaxController::class,'holdingCertificate_of_honor']);
Route::get('/holding/tax/invoice/all/list', [HoldingtaxController::class,'holdingAllPenddingInvoice']);
Route::get('/holding/tax/invoice/{id}', [HoldingtaxController::class,'holdingPaymentInvoice']);

Route::get('/report/export', [PaymentController::class,'export']);

Route::get('/cashbook/download', [ExpenditureController::class,'cashbook_download']);

Route::get('/secretary/approve/{id}', [SonodController::class,'SecretariNotificationApprove']);
Route::get('/chairman/approve/{id}', [SonodController::class,'ChairnamNotificationApprove']);
Route::get('/secretary/pay/{id}', [SonodController::class,'SecretariNotificationPay']);
Route::get('/allow/application/notification', function () {

    return view('applicationNotification');


    });





Route::get('/files/{path}', function ($path) {

    // Serve the file from the protected disk
    return response()->file(Storage::disk('protected')->path($path));
})->where('path', '.*');

