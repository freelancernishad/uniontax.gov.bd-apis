<?php

namespace App\Http\Controllers\API\Others;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testPayment()
    {
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
            "trns_info" => [
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

        $post = json_encode($post);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("$Apiurl/merchant-api", $post);

        $response = $response->json();
        $sToken = $response['secure_token'];

        return "$Apiurl?sToken=$sToken&trnsID=$trnx_id";
    }


    public function sendEmail()
    {
        $year = date('Y');
        $date = date('m');
        $orthobochor = '';

        if ($date < 7) {
            $orthobochor = ($year - 1) . "-" . date('y');
        } else {
            $orthobochor = $year . "-" . (date('y') + 1);
        }

        // Example email data
        $descriptionEmail = "Your application no. 77349852200010 has been approved. Document is available at https://test.uniontax.gov.bd/sonod/d/22425";
        $emailData = ['description' => $descriptionEmail];

        // Return a view with email data
        return view('email_view', $emailData);
    }



    public function sendEmail2()
    {
        $details = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp'
        ];
        $subject = 'hello subject';

        Mail::to('freelancernishad123@gmail.com')->send(new MyTestMail($details, $subject));

        return "Email is Sent.";
    }


}
