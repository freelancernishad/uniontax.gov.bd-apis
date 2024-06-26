<?php

namespace App\Http\Controllers\API\Sonod;

use App\Models\User;
use App\Models\Sonod;
use App\Models\Charage;
use App\Models\Citizen;
use App\Models\Payment;
use App\Models\SonodFee;
use App\Models\ActionLog;
use App\helper\FileHelper;
use App\Models\Uniouninfo;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Sonodnamelist;
use App\Models\TradeLicenseKhatFee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Rakibhstu\Banglanumber\NumberToBangla;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;

class SonodController extends Controller
{

    public function prottonupdate(Request $request, $id)
    {


        // Find the Sonod by id
        $sonod = Sonod::find($id);

        // Check if the Sonod exists
        if (!$sonod) {
            return response()->json(['error' => 'Sonod not found'], 404);
        }

        // Update the Sonod with the new data
        $sonod->sec_prottoyon = $request->sec_prottoyon;
        $sonod->save();

        // Return a success response
        return response()->json(['message' => 'Sonod updated successfully', 'sonod' => $sonod], 200);
    }

    public function sonodpaymentSuccessView(Request $request, $id)
    {
        // Find the Sonod by id
        $sonod = Sonod::find($id);

        // Check if the Sonod exists
        if (!$sonod) {
            // Return a view for not found or an error message
            return view('errors.sonod_not_found'); // Ensure this view exists or customize as needed
        }

        // Return the success view with the Sonod data
        return view('sonodsuccess', compact('sonod'));
    }

    public function sonodpaymentSuccess(Request $request)
    {
        $transId = $request->transId;
        $payment = Payment::where('trxId', $transId)->first();
        if (!$payment) {
            return $this->paymentErrorResponse();
        }

        $sonod = Sonod::find($payment->sonodId);
        if (!$sonod) {
            return $this->paymentErrorResponse();
        }

        $unionInfo = Uniouninfo::where('short_name_e', $sonod->unioun_name)->first();
        if (!$unionInfo) {
            return $this->paymentErrorResponse();
        }

        $paymentType = $unionInfo->payment_type;
        if ($paymentType == 'Prepaid') {
            return $this->handlePrepaidPayment($payment, $sonod);
        } elseif ($paymentType == 'Postpaid') {
            return $this->handlePostpaidPayment($payment, $sonod);
        }

        return $this->paymentErrorResponse();
    }

    private function paymentErrorResponse()
    {
        echo "
        <div style='text-align:center'>
            <h1 style='text-align:center'>Payment Failed</h1>
            <a href='/' style='border:1px solid black; padding:10px 12px; background:red; color:white'>Back To Home</a>
            <a href='/sonod/payment/retry' style='border:1px solid black; padding:10px 12px; background:green; color:white'>Pay Again</a>
        </div>";
    }

    private function handlePrepaidPayment($payment, $sonod)
    {
        if ($payment->status == 'Paid') {
            $description = "Congratulations! Your application {$sonod->sonod_Id} has been paid. Wait for approval.";
            // smsSend($description, $sonod->applicant_mobile);
            return view('applicationSuccess', compact('payment', 'sonod'));
        } else {
            return $this->paymentErrorResponse();
        }
    }

    private function handlePostpaidPayment($payment, $sonod)
    {
        $payment->update(['status' => 'Paid']);
        $sonod->update(['payment_status' => 'Paid']);

        $sonodUrl = url("/sonod/d/{$sonod->id}");
        $invoiceUrl = url("/invoice/d/{$sonod->id}");
        $description = "Congratulations! Your application {$sonod->sonod_Id} has been paid. Sonod: {$sonodUrl} Invoice: {$invoiceUrl}";
        // smsSend($description, $sonod->applicant_mobile);

        return redirect("/sonod/payment/success/{$sonod->id}");
    }


    public function sonodpayment(Request $request, $id)
    {
        $sonod = Sonod::find($id);
        if (!$sonod) {
            return "Sonod with ID $id not found";
        }

        $applicant_mobile = int_bn_to_en($sonod->applicant_mobile);
        $unioun_name = $sonod->unioun_name;
        $sonod_name = $sonod->sonod_name;

        $uniouninfo = Uniouninfo::where('short_name_e', $unioun_name)->first();
        if (!$uniouninfo) {
            return "Uniouninfo not found for $unioun_name";
        }

        $sonodnamelists = Sonodnamelist::where('bnname', $sonod_name)->first();
        if (!$sonodnamelists) {
            return "Sonodnamelist not found for $sonod_name";
        }

        $payment_type = $uniouninfo->payment_type;
        $total_amount = $sonod->total_amount ?: 1;

        if ($payment_type == 'Prepaid') {
            $sonodFees = SonodFee::where([
                'service_id' => $sonodnamelists->service_id,
                'unioun' => $unioun_name
            ])->first();

            $sonod_fee = $sonodFees ? $sonodFees->fees : 0;

            $unioninfos = Uniouninfo::where('short_name_e', $unioun_name)->first();
            if ($unioninfos) {
                $district = $unioninfos->district;
                $thana = $unioninfos->thana;

                $charge = Charage::where(['district' => $district, 'thana' => $thana])->first();
                $vat = $charge ? $charge->vat : 0;
                $tax = $charge ? $charge->tax : 0;
                $service = $charge ? $charge->service : 0;

                $tradeVat = 15;

                if ($sonod_name == 'ট্রেড লাইসেন্স') {
                    $khat_id_1 = $sonod->applicant_type_of_businessKhat;
                    $khat_id_2 = $sonod->applicant_type_of_businessKhatAmount;
                    $pesaKorFee = TradeLicenseKhatFee::where([
                        'khat_id_1' => $khat_id_1,
                        'khat_id_2' => $khat_id_2
                    ])->first();

                    $TradevatAmount = (($sonod_fee * $tradeVat) / 100);
                    $total_amount += $pesaKorFee ? $pesaKorFee->fee + $TradevatAmount : 0;
                }
            }

            $total_amount = max(1, $total_amount);


        $trnx_id = $sonod->u_code . '-' . time();
        $cust_info = [
            "cust_email" => "",
            "cust_id" => $sonod->id,
            "cust_mail_addr" => "Address",
            "cust_mobo_no" => $applicant_mobile,
            "cust_name" => "Customer Name"
        ];

        $trns_info = [
            "ord_det" => 'sonod',
            "ord_id" => "$sonod->sonod_Id",
            "trnx_amt" => $total_amount,
            "trnx_currency" => "BDT",
            "trnx_id" => "$trnx_id"
        ];


        $redirectutl = ekpayToken($trnx_id, $trns_info, $cust_info,'payment',$sonod->unioun_name);

        $req_timestamp = date('Y-m-d H:i:s');
        $customerData = [
            'union' => $sonod->unioun_name,
            'trxId' => $trnx_id,
            'sonodId' => $id,
            'sonod_type' => $sonod->sonod_name,
            'amount' => $total_amount,
            'mob' => $applicant_mobile,
            'status' => "Pending",
            'paymentUrl' => $redirectutl,
            'method' => 'ekpay',
            'payment_type' => 'online',
            'date' => date('Y-m-d'),
            'created_at' => $req_timestamp,
        ];
        Payment::create($customerData);
        return redirect($redirectutl);




        } elseif ($payment_type == 'Postpaid') {
            if ($sonod->stutus != 'approved') {
                return "আপনার অনুসন্ধানকৃত সনদ/প্রত্যয়নপত্র অত্র ইউনিয়ন পরিষদ থেকে এখনও অনুমোদন করা হয়নি।";
            }

            if ($sonod->payment_status != 'Unpaid' && $sonod->stutus == 'approved') {
                return redirect("/sonod/$sonod->sonod_name/$id");
            }

        }



    }


    public function sonod_id(Request $request)
    {
        $sonodFinalId = '';

        // Get current year
        $sortYear = date('y');

        // Retrieve union name from request
        $union = $request->union;

        // Retrieve Uniouninfo and Sonod counts in one query for efficiency
        $uniounInfoAndSonod = Uniouninfo::where('short_name_e', $union)
            ->withCount('sonods')
            ->latest()
            ->first();

        if ($uniounInfoAndSonod) {
            $SonodCount = $uniounInfoAndSonod->sonods_count;

            if ($SonodCount > 0) {
                // If Sonod exists, get the latest one
                $latestSonod = $uniounInfoAndSonod->sonods()->latest()->first();

                if ($latestSonod->sonod_Id == '') {
                    // If the latest Sonod does not have an ID set
                    $sonod_Id = str_pad(1, 5, '0', STR_PAD_LEFT); // Start from 00001
                    $sonodFinalId = $uniounInfoAndSonod->u_code . $sortYear . $sonod_Id;
                } else {
                    // Increment the latest Sonod ID
                    $sonod_Id = str_pad($latestSonod->sonod_Id + 1, 5, '0', STR_PAD_LEFT);
                    $sonodFinalId = $uniounInfoAndSonod->u_code . $sortYear . $sonod_Id;
                }
            } else {
                // No Sonod exists, start from 00001
                $sonod_Id = str_pad(1, 5, '0', STR_PAD_LEFT);
                $sonodFinalId = $uniounInfoAndSonod->u_code . $sortYear . $sonod_Id;
            }
        }

        return $sonodFinalId;
    }


    function allsonodId($union, $sonodname, $orthoBchor)
    {
        $sonodFinalId = '';

        // Determine the sort year based on the current date
        $date = date('m');
        $sortYear = $date < 7 ? date('y') - 1 : date('y');

        // Retrieve Uniouninfo and Sonod counts in one query for efficiency
        $uniounInfoAndSonod = Uniouninfo::where('short_name_e', $union)
            ->withCount(['sonods' => function ($query) use ($sonodname, $orthoBchor) {
                $query->where('sonod_name', $sonodname)->where('orthoBchor', $orthoBchor);
            }])
            ->latest()
            ->first();

        if ($uniounInfoAndSonod) {
            $SonodCount = $uniounInfoAndSonod->sonods_count;

            if ($SonodCount > 0) {
                // If Sonod exists, get the latest one
                $latestSonod = $uniounInfoAndSonod->sonods()
                    ->where('sonod_name', $sonodname)
                    ->where('orthoBchor', $orthoBchor)
                    ->latest()
                    ->first();

                $sonodFinalId = $latestSonod->sonod_Id + 1;
            } else {
                // No Sonod exists, start from 00001
                $sonod_Id = str_pad(1, 5, '0', STR_PAD_LEFT);
                $sonodFinalId = $uniounInfoAndSonod->u_code . $sortYear . $sonod_Id;
            }
        }

        return $sonodFinalId;
    }

    public function sonod_update(Request $r)
    {
        $id = $r->id;
        $sonod = Sonod::find($id);
        if (!$sonod) {
            return sent_error("Sonod not found.", 404);
        }

        $successors = json_encode($r->successors);
        $sonodEnName = Sonodnamelist::where('bnname', $r->sonod_name)->first();


        $sonodId = (string) $sonod->sonod_Id;

        // Update successor list
        $r->merge(['successor_list' => $successors]);

        try {
            // Update Sonod record
            $sonod->update($r->except(['id', 'successors']));

            return $sonod; // Optionally return updated Sonod object
        } catch (Exception $e) {
            return sent_error($e->getMessage(), $e->getCode());
        }
    }

    public function sonod_submit(Request $r)
    {
        $id = $r->id;
        $stutus = $r->stutus;
        $successors = json_encode($r->successors);
        $sonodEnName = Sonodnamelist::where('bnname', $r->sonod_name)->first();
        $filepath = str_replace(' ', '_', $sonodEnName->enname);

        $Insertdata = $r->except([
            'sonod_Id', 'image', 'applicant_national_id_front_attachment',
            'applicant_national_id_back_attachment', 'applicant_birth_certificate_attachment',
            'successors', 'charages', 'Annual_income', 'applicant_type_of_businessKhat',
            'applicant_type_of_businessKhatAmount', 'orthoBchor'
        ]);

        $Insertdata['applicant_type_of_businessKhat'] = $r->applicant_type_of_businessKhat;
        $Insertdata['applicant_type_of_businessKhatAmount'] = $r->applicant_type_of_businessKhatAmount ?? 0;

        if (in_array($r->sonod_name, ['একই নামের প্রত্যয়ন', 'বিবিধ প্রত্যয়নপত্র'])) {
            $Insertdata['sameNameNew'] = 1;
        }

        $year = date('Y');
        $date = date('m');
        $orthobochor = $date < 7 ? ($year - 1) . "-" . date('y') : $year . "-" . (date('y') + 1);

        $Insertdata['orthoBchor'] = $r->sonod_name == 'ট্রেড লাইসেন্স' ? $r->orthoBchor : $orthobochor;

        $fileFields = [
            'image' => "sonod/$filepath/" . date("Y/m/d") . "/",
            'applicant_national_id_front_attachment' => "sonod/$filepath/" . date("Y/m/d") . "/",
            'applicant_national_id_back_attachment' => "sonod/$filepath/" . date("Y/m/d") . "/",
            'applicant_birth_certificate_attachment' => "sonod/$filepath/applicant_birth_certificate_attachment/"
        ];

        foreach ($fileFields as $field => $path) {
            if (count(explode(';', $r->$field)) > 1) {
                $Insertdata[$field] = fileupload($r->$field, $path . $this->allsonodId($r->unioun_name, $r->sonod_name, $orthobochor) . "/", 250, 300);
            }
        }

        if ($r->Annual_income) {
            $Insertdata['Annual_income'] = $r->Annual_income;
            $Insertdata['Annual_income_text'] = (new NumberToBangla())->bnMoney(int_bn_to_en($r->Annual_income)) . ' মাত্র';
        }

        $Insertdata['successor_list'] = $successors;
        $Uniouninfo = Uniouninfo::where('short_name_e', $r->unioun_name)->latest()->first();
        $Insertdata = array_merge($Insertdata, [
            'chaireman_name' => $Uniouninfo->c_name,
            'c_email' => $Uniouninfo->c_email,
            'chaireman_sign' => $Uniouninfo->c_signture,
            'socib_name' => $Uniouninfo->socib_name,
            'socib_email' => $Uniouninfo->socib_email,
            'socib_signture' => $Uniouninfo->socib_signture
        ]);

        try {
            $sonodId = (string)$this->allsonodId($r->unioun_name, $r->sonod_name, $orthobochor);
            $Insertdata['sonod_Id'] = $sonodId;

            if ($stutus == 'Prepaid') {
                $totalamount = $r->charages['totalamount'];
                $currently_paid_money = $totalamount - $r->last_years_money;
                $amount_deails = json_encode([
                    'total_amount' => $totalamount,
                    'pesaKor' => $r->charages['pesaKor'],
                    'tredeLisenceFee' => $r->charages['sonod_fee'],
                    'vatAykor' => $r->charages['tradeVat'],
                    'khat' => '',
                    'last_years_money' => $r->last_years_money,
                    'currently_paid_money' => $currently_paid_money
                ]);

                $Insertdata = array_merge($Insertdata, [
                    'khat' => '',
                    'last_years_money' => $r->last_years_money,
                    'currently_paid_money' => $currently_paid_money,
                    'total_amount' => $totalamount,
                    'the_amount_of_money_in_words' => (new NumberToBangla())->bnMoney($totalamount) . ' মাত্র',
                    'amount_deails' => $amount_deails
                ]);
            }

            $sonod = Sonod::create($Insertdata);

            if ($stutus == 'Pending') {
                $deccription = "Congratulation! Your application $sonod->sonod_Id has been submitted. Wait for approval.";
                // smsSend($deccription, $sonod->applicant_mobile);
            }

            $notifiData = ['union' => $sonod->unioun_name, 'roles' => 'Secretary'];
            $notificationsCount = Notifications::where($notifiData)->count();

            if ($notificationsCount > 0) {
                $action = makeshorturl(url('/secretary/approve/' . $sonod->id));
                $notifications = Notifications::where($notifiData)->latest()->first();
                $data = json_encode([
                    "to" => $notifications->key,
                    "notification" => [
                        "body" => $sonod->applicant_name . " একটি " . $sonod->sonod_name . " এর নুতুন আবেদন করেছে",
                        "title" => "সনদ নং " . int_en_to_bn($sonod->sonod_Id),
                        "icon" => asset('assets/img/bangladesh-govt.png'),
                        "click_action" => $action
                    ]
                ]);
                pushNotification($data);
            }

            return $sonod;
        } catch (Exception $e) {
            return sent_error($e->getMessage(), $e->getCode());
        }
    }

    public function sonodByKey(Request $request)
    {
        $sToken = $request->sToken;

        $sonod = Sonod::where('uniqeKey', $sToken)->first();

        if ($sonod) {
            return $sonod;
        }

        return response()->json(['message' => 'Sonod not found'], 404);
    }


    public function sonod_delete($id)
    {
        $sonod = Sonod::find($id);

        if (!$sonod) {
            return 'Sonod not found!';
        }

        $sonod->delete();

        return 'Sonod deleted!';
    }


    public function sec_sonod_action(Request $request, $id)
    {
        $sonod = Sonod::find($id);

        if (!$sonod) {
            return "Sonod not found!";
        }

        $sec_prottoyon = $request->sec_prottoyon;

        $arraydata = [
            'total_amount' => $request->amounta,
            'pesaKor' => $request->pesaKor,
            'tredeLisenceFee' => $request->tredeLisenceFee,
            'vatAykor' => $request->vatAykor,
            'khat' => $request->khat,
            'last_years_money' => $request->last_years_money,
            'currently_paid_money' => $request->currently_paid_money,
        ];
        $amount_deails = json_encode($arraydata);

        $numto = new NumberToBangla();
        $the_amount_of_money_in_words = $numto->bnMoney($request->amounta) . ' মাত্র';

        $approveData = $request->approeDatav ?? 'Secretary_approved';

        $updateData = [
            'sec_prottoyon' => $sec_prottoyon,
            'stutus' => $approveData,
        ];

        if ($sonod->payment_status != 'Paid') {
            $updateData = array_merge($updateData, [
                'khat' => $request->khat,
                'last_years_money' => $request->last_years_money,
                'currently_paid_money' => $request->currently_paid_money,
                'total_amount' => $request->amounta,
                'the_amount_of_money_in_words' => $the_amount_of_money_in_words,
                'amount_deails' => $amount_deails,
            ]);
        }

        $updateResult = $sonod->update($updateData);

        if ($updateResult) {
            $notifiData = ['union' => $sonod->unioun_name, 'roles' => 'Chairman'];
            $notificationsCount = Notification::where($notifiData)->count();

            if ($notificationsCount > 0) {
                $notifications = Notification::where($notifiData)->latest()->first();
                $data = '{"to":"' . $notifications->key . '","notification":{"body":"সচিব ' . $sonod->applicant_name . ' এর ' . $sonod->sonod_name . ' এর আবেদনটি অনুমোদন করেছে","title":"সনদ নং ' . int_en_to_bn($sonod->sonod_Id) . '","icon":"' . asset('assets/img/bangladesh-govt.png') . '","click_action":"' . url('/chairman/approve/' . $sonod->id) . '"}}';
                pushNotification($data);
            }

            return "Sonod updated successfully!";
        }

        return "Failed to update Sonod!";
    }


    public function sonod_pay(Request $request, $id)
    {
        $type = $request->type;
        $sonod = Sonod::find($id);

        if (!$sonod) {
            return "Sonod not found!";
        }

        $sonodUrl = url("/sonod/d/$id");
        $invoiceUrl = url("/invoice/d/$id");
        $description = "Congratulation! Your application {$sonod->sonod_Id} has been Paid. Sonod: {$sonodUrl} Invoice: {$invoiceUrl}";

        // Uncomment the following line when you have the SMS sending functionality
        // smsSend($description, $sonod->applicant_mobile);

        $req_timestamp = now();

        $customerData = [
            'union' => $sonod->unioun_name,
            'trxId' => time(),
            'sonodId' => $id,
            'sonod_type' => $sonod->sonod_name,
            'amount' => $sonod->total_amount,
            'mob' => $sonod->applicant_mobile,
            'status' => "Paid",
            'date' => $req_timestamp->format('Y-m-d'),
            'month' => $req_timestamp->format('F'),
            'year' => $req_timestamp->format('Y'),
            'balance' => 0,
            'created_at' => $req_timestamp,
        ];

        // Create a payment record
        Payment::create($customerData);

        // Update the payment status based on the type of request
        if ($type == 'notify') {
            $sonod->update(['payment_status' => 'Paid']);
            return Sonod::find($id); // Return the updated Sonod record
        } else {
            $updated = $sonod->update(['payment_status' => 'Paid']);
            return $updated ? "Sonod payment status updated!" : "Failed to update Sonod payment status!";
        }
    }


    public function cancelsonod(Request $request, $id)
    {
        $sonod = Sonod::find($id);

        if (!$sonod) {
            return "Sonod not found!";
        }

        // Log the action
        ActionLog::create($request->all());

        // Update Sonod with cancellation details
        $sonod->update([
            'cancedby' => $request->names,
            'cancedbyUserid' => $request->user_id,
        ]);

        // Prepare and send SMS notification
        $InvoiceUrl = url("api/reject/$id");
        $description = "Opps! Your application {$sonod->sonod_Id} has been Not Approved. Details: {$InvoiceUrl}";

        // Uncomment the following line when you have the SMS sending functionality
        // SmsNocSmsSend($description, $sonod->applicant_mobile, $sonod->unioun_name);

        // Update Sonod status
        $updateData = [
            'stutus' => $request->status, // Assuming 'stutus' should be 'status'
        ];

        return $sonod->update($updateData) ? "Sonod status updated!" : "Failed to update Sonod status!";
    }

    public function sonod_action(Request $request, $action, $id)
    {
        $sonod = Sonod::find($id);

        if (!$sonod) {
            return "Sonod not found!";
        }

        $uniouninfos = Uniouninfo::where(['short_name_e' => $sonod->unioun_name])->first();

        if ($action == 'approved') {
            $updatedata = [
                'chaireman_name' => $uniouninfos->c_name,
                'c_email' => $uniouninfos->c_email,
                'chaireman_sign' => $uniouninfos->c_signture,
                'chaireman_type' => $uniouninfos->c_type,
                'stutus' => $action,
                'socib_name' => $uniouninfos->socib_name,
                'socib_email' => $uniouninfos->socib_email,
                'socib_signture' => $uniouninfos->socib_signture,
            ];

            // Determine the format based on sonod_name
            if (in_array($sonod->sonod_name, ['ট্রেড লাইসেন্স', 'ওয়ারিশান সনদ', 'উত্তরাধিকারী সনদ'])) {
                $updatedata['format'] = $uniouninfos->format;
            } else {
                $updatedata['format'] = 1; // Set default format value
            }

            // Prepare SMS and email messages
            $sonodUrl = url("/sonod/d/$id");
            $description = "Congratulation! Your application {$sonod->sonod_Id} has been approved. Document is available at $sonodUrl";
            SmsNocSmsSend($description, $sonod->applicant_mobile, $sonod->unioun_name);

            // Prepare email content (commented out for future implementation)
            // $emailContent = "<p>প্রিয় সেবা গ্রহীতা...</p>";

        } else {
            $updatedata = [
                'stutus' => $action,
            ];
        }

        // Send notification to Secretary role
        $notificationData = ['union' => $sonod->unioun_name, 'roles' => 'Secretary'];
        $notificationsCount = Notification::where($notificationData)->count();
        if ($notificationsCount > 0) {
            $notification = Notification::where($notificationData)->latest()->first();
            $data = '{"to":"' . $notification->key . '","notification":{"body":"চেয়ারম্যান ' . $sonod->applicant_name . ' এর ' . $sonod->sonod_name . ' এর আবেদনটি অনুমোদন করেছে","title":"সনদ নং ' . int_en_to_bn($sonod->sonod_Id) . '","icon":"' . asset('assets/img/bangladesh-govt.png') . '","click_action":"' . url('/secretary/pay/' . $sonod->id) . '"}}';
            pushNotification($data);
        }

        // Update Sonod with the updated data
        $sonod->update($updatedata);

        // Handle redirection based on type parameter
        if ($request->type == 'notify') {
            return redirect('/dashboard');
        }

        return $sonod;
    }

    public function ChairnamNotificationApprove($id)
    {
        $redirecturl = "?redirect=" . url('/chairman/approve/' . $id);

        // Redirect to login if user is not authenticated
        if (!Auth::user()) {
            return redirect('/login' . $redirecturl);
        }

        $user = Auth::user();
        $sonod = Sonod::find($id);

        // Check if the user's union matches the sonod's union
        if ($user->unioun != $sonod->unioun_name) {
            $unionname = Uniouninfo::where('short_name_e', $sonod->unioun_name)->first();
            $unionname2 = Uniouninfo::where('short_name_e', $user->unioun)->first();
            return "আপনি $unionname->full_name এর তথ্য $unionname2->full_name থেকে অনুমোদন করতে পারবেন না";
        }

        // Check if the user is a Secretary
        if ($user->position == 'Secretary') {
            return "সচিব এই এড্রেসটি অ্যাক্সেস করতে পারবে না <a href='/dashboard/logout$redirecturl'>Logout</a>";
        }

        // Get the English name of the sonod
        $enname = str_replace("_", " ", sonodEnName($sonod->sonod_name));
        $sonodnamedata = Sonodnamelist::where('enname', $enname)->first();

        // Determine the role for view rendering
        $role = 'Chairman';
        $Secretary_pay = '';

        // Handle different status scenarios for sonod
        if ($sonod->stutus == 'Secretary_approved') {
            return view('chairemanapprove', compact('sonod', 'sonodnamedata', 'role', 'Secretary_pay'));
        } elseif ($sonod->stutus == 'approved') {
            if ($sonod->payment_status == 'Paid') {
                $Secretary_pay = 'Secretary_pay';
                return view('chairemanapprove', compact('sonod', 'sonodnamedata', 'role', 'Secretary_pay'));
            }
            return 'সনদটি ইতিমধ্যে চেয়ারম্যান কর্তৃক অনুমোদিত হয়েছে';
        } else {
            return 'সনদটি এখনো সচিব এর প্যানেল এ আছে';
        }
    }

    public function SecretariNotificationApprove($id)
    {
        $redirecturl = "?redirect=" . url('/secretary/approve/' . $id);

        // Redirect to login if user is not authenticated
        if (!Auth::user()) {
            return redirect('/login' . $redirecturl);
        }

        $user = Auth::user();
        $sonod = Sonod::find($id);

        // Check if the user's union matches the sonod's union
        if ($user->unioun != $sonod->unioun_name) {
            $unionname = Uniouninfo::where('short_name_e', $sonod->unioun_name)->first();
            $unionname2 = Uniouninfo::where('short_name_e', $user->unioun)->first();
            return "আপনি $unionname->full_name এর তথ্য $unionname2->full_name থেকে অনুমোদন করতে পারবেন না";
        }

        // Check if the user is a Chairman
        if ($user->position == 'Chairman') {
            return "চেয়ারম্যান এই এড্রেসটি অ্যাক্সেস করতে পারবে না <a href='/dashboard/logout$redirecturl'>Logout</a>";
        }

        // Get the English name of the sonod
        $enname = str_replace("_", " ", sonodEnName($sonod->sonod_name));
        $sonodnamedata = Sonodnamelist::where('enname', $enname)->first();

        // Determine the role for view rendering
        $role = 'Secretary';
        $Secretary_pay = '';

        // Handle different status scenarios for sonod
        if ($sonod->stutus == 'Pending') {
            return view('chairemanapprove', compact('sonod', 'sonodnamedata', 'role', 'Secretary_pay'));
        } elseif ($sonod->stutus == 'approved') {
            if ($sonod->payment_status == 'Paid') {
                $Secretary_pay = 'Secretary_pay';
                return view('chairemanapprove', compact('sonod', 'sonodnamedata', 'role', 'Secretary_pay'));
            }
            return 'সনদটি ইতিমধ্যে চেয়ারম্যান কর্তৃক অনুমোদিত হয়েছে';
        } else {
            return 'সনদটি চেয়ারম্যান এর প্যানেল এ আছে';
        }
    }


    public function SecretariNotificationPay($id)
    {
        $redirecturl = "?redirect=" . url('/secretary/pay/' . $id);

        if (!Auth::check()) {
            return redirect('/login' . $redirecturl);
        }

        $user = Auth::user();
        $sonod = Sonod::find($id);
        if (!$sonod) {
            // Handle case where Sonod with given $id is not found
        }

        $unioun = $user->unioun;
        if ($unioun != $sonod->unioun_name) {
            $unionname = Uniouninfo::where('short_name_e', $sonod->unioun_name)->first();
            $unionname2 = Uniouninfo::where('short_name_e', $unioun)->first();
            return "আপনি $unionname->full_name এর তথ্য $unionname2->full_name থেকে অনুমোদন করতে পারবেন না";
        }

        $position = $user->position;
        if ($position == 'Chairman') {
            return "চেয়ারম্যান এই এড্রেসটি অ্যাক্সেস করতে পারবে না <a href='/dashboard/logout$redirecturl'>Logout</a>";
        }

        $enname = str_replace("_", " ", sonodEnName($sonod->sonod_name));
        $sonodnamedata = Sonodnamelist::where('enname', $enname)->first();

        $role = 'Secretary';
        $Secretary_pay = 'Secretary_pay';

        if ($sonod->status == 'approved') {
            return view('chairemanapprove', compact('sonod', 'sonodnamedata', 'role', 'Secretary_pay'));
        } else {
            return 'সনদটি ' . ($sonod->status == 'approved' ? 'ইতিমধ্যে চেয়ারম্যান কর্তৃক অনুমোদিত হয়েছে' : 'এখনো চেয়ারম্যান এর প্যানেল এ আছে');
        }
    }

    public function enBnName($data)
    {
        $data =  str_replace("_", " ", $data);
        return Sonodnamelist::where('enname', $data)->first();
    }


    public function index(Request $request)
    {
        $sonod_name = $request->sonod_name;
        $stutus = $request->stutus;
        $payment_status = $request->payment_status;
        $unioun_name = $request->unioun_name;
        $sondId = $request->sondId;

        // Fetch Sonodnamelist based on $sonod_name
        $Sonodnamelist = Sonodnamelist::where('bnname', $this->enBnName($sonod_name)->bnname)->first();

        // Determine the filtering criteria based on request parameters
        $query = Sonod::query()
            ->where('sonod_name', $sonod_name)
            ->where('stutus', $stutus);

        if ($unioun_name) {
            $query->where('unioun_name', $unioun_name);
        }

        if ($sondId) {
            $query->where('sonod_Id', 'LIKE', "%$sondId%");
        }

        if ($payment_status) {
            $query->where('payment_status', $payment_status);
        }

        // If userid is provided, filter by user's 'thana'
        if ($request->has('userid')) {
            $user = User::find($request->userid);
            if ($user) {
                $query->where('applicant_present_Upazila', $user->thana);
            }
        }

        // Execute query with pagination and return data
        $sonods = $query->orderBy('id', 'DESC')->paginate(20);

        $returnData = [
            'sonods' => $sonods,
            'sonod_name' => $Sonodnamelist,
        ];

        return $returnData;
    }

    public function sonodDownload(Request $request, $name, $id)
    {
        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "50000000000000000");
        ini_set('memory_limit', '12008M');

        $row = Sonod::find($id);

        // Handle cancellation status
        if ($row->stutus == 'cancel') {
            return "<h1 style='color:red;text-align:center'>সনদটি বাতিল করা হয়েছে!<h1>";
        }

        $sonod_name = $row->sonod_name;
        $sonodnames = Sonodnamelist::where('bnname', $row->sonod_name)->first();
        $EnsonodName = str_replace(" ", "_", $sonodnames->enname);
        $sonod = Sonodnamelist::where(['bnname' => $row->sonod_name])->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();

        // Load view based on sonod_name
        if ($sonod_name == 'ওয়ারিশান সনদ' || $sonod_name == 'উত্তরাধিকারী সনদ') {
            if ($row->format == 2) {
                $pdf = LaravelMpdf::loadView('Inheritance-certificate.sonod', compact('row', 'sonod', 'uniouninfo', 'sonodnames'));
            } else {
                $filename = "$EnsonodName-$row->sonod_Id.pdf";
                $mpdf = new \Mpdf\Mpdf([
                    'default_font_size' => 12,
                    'default_font' => 'bangla',
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'setAutoTopMargin' => 'stretch',
                    'setAutoBottomMargin' => 'stretch'
                ]);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->SetHTMLHeader($this->pdfHeader($id, $filename));
                $mpdf->SetHTMLFooter($this->pdfFooter($id, $filename));
                $mpdf->WriteHTML($this->pdfHTMLut($id, $filename));
                $mpdf->Output("$EnsonodName-$row->sonod_Id.pdf", 'I');
                exit;
            }
        } else {
            if ($row->sonod_name == 'ট্রেড লাইসেন্স') {
                if ($row->format == 2) {
                    $pdf = LaravelMpdf::loadView('sonod-tradelicense-format2', compact('row', 'sonod', 'uniouninfo', 'sonodnames'));
                } else {
                    $pdf = LaravelMpdf::loadView('sonod', compact('row', 'sonod', 'uniouninfo', 'sonodnames'));
                }
            } else {
                $pdf = LaravelMpdf::loadView('sonod', compact('row', 'sonod', 'uniouninfo', 'sonodnames'));
            }
        }

        return $pdf->stream("$EnsonodName-$row->sonod_Id.pdf");
    }


    public function invoice(Request $request, $name, $id)
    {
        $row = Sonod::find($id);
        $sonod = Sonodnamelist::where('bnname', $row->sonod_name)->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();
        $sonodnames = Sonodnamelist::where('bnname', $row->sonod_name)->first();
        $EnsonodName = str_replace(" ", "_", $sonodnames->enname);

        $TaxInvoice = Payment::where('sonodId', $row->id)->latest()->first();

        if ($name == 'c') {
            $pdf = LaravelMpdf::loadView('cinvoice', compact('row', 'sonod', 'uniouninfo'));
        } else {
            // Uncommented code removed for clarity

            $pdf = LaravelMpdf::loadView('invoice', compact('row', 'sonod', 'uniouninfo', 'TaxInvoice'));
        }

        return $pdf->stream("$EnsonodName-$row->sonod_Id.pdf");
    }


    public function invoicePdf($sonod, $sonodName, $unions, $TaxInvoice, $amountWord)
    {
        $full_name = $unions->full_name;
        $short_name_b = $unions->short_name_b;
        $thana = $unions->thana;
        $district = $unions->district;

        $maliker_name = $sonod->applicant_name;
        $father_or_samir_name = $sonod->applicant_father_name;
        $gramer_name = $sonod->applicant_present_village;
        $word_no = $sonod->applicant_present_word_number;
        $mobile_no = $sonod->applicant_mobile;

        $khatlist = json_decode($sonod->amount_deails);
        $total = $khatlist->tredeLisenceFee;
        $amount = ($total * $khatlist->vatAykor) / 100;
        $totalAmount = $khatlist->pesaKor + $total + $amount;

        $invoiceId = int_en_to_bn($TaxInvoice->trxId);
        $status = $TaxInvoice->status;
        $created_at = int_en_to_bn(date("d/m/Y", strtotime($TaxInvoice->created_at)));
        $subtotal = number_format($TaxInvoice->amount, 2);

        // Build HTML for the invoice
        $html = "
            <style>
                /* Your CSS styles here */
            </style>
            <div id='body'>
                <div class='memoborder' style='float:left'>
                    <div class='memobg memobg1'>
                        <div class='memo'>
                            <div class='memoHead'>
                                <p class='defalttext'>ইউপি ফরম-১০</p>
                                <h2 style='font-weight: 500;' class='companiname'>$full_name</h2>
                                <p class='defalttext'>উপজেলা: $thana, জেলা: $district</p>
                                <h2 class='companiname' style='color: #410fcc;'>ট্যাক্স, রেট ও বিবিধ প্রাপ্তি আদায় রশিদ </h2>";

        if ($status == 'Paid') {
            $html .= "<h2 class='companiname' style='width: 160px; margin: 0 auto; background: green; color: white; border-radius: 50px; font-size: 16px; padding: 6px 0px;'>পরিশোধিত </h2>";
        } else {
            $html .= "<h2 class='companiname' style='width: 160px; margin: 0 auto; background: red; color: white; border-radius: 50px; font-size: 16px; padding: 6px 0px;'>অপরিশোধিত </h2>";
        }

        $html .= "
                            </div>
                            <table style='width:100%'>
                                <tr>
                                    <td colspan='2'></td>
                                    <td style='text-align:right'>রশিদ নং- $invoiceId</td>
                                </tr>
                                <tr>
                                    <td colspan='3'>$sonodName->bnname</td>
                                </tr>
                                <tr>
                                    <td>নাম: $maliker_name </td>
                                    <td colspan='2'>পিতার নাম- $father_or_samir_name</td>
                                </tr>
                                <tr>
                                    <td>ঠিকানা: গ্রাম- $gramer_name,</td>
                                    <td>ওয়ার্ড- " . int_en_to_bn($word_no) . "</td>
                                    <td>ডাকঘর- $short_name_b</td>
                                </tr>
                                <tr>
                                    <td>উপজেলা: $thana </td>
                                    <td>জেলা: $district</td>
                                    <td>মোবাইল: " . int_en_to_bn($mobile_no) . "</td>
                                </tr>
                            </table>
                            <p></p>
                            <div class='memobody' style='position: relative;'>
                                <div class='productDetails'>
                                    <table class='table' style='border:1px solid #444B8F;width:100%' cellspacing='0'>
                                        <thead class='thead'>
                                            <tr class='tr'>
                                                <td class='th defaltfont' colspan='4' width='10%'>আদায়ের বিবরণ</td>
                                            </tr>
                                            <tr class='tr'>
                                                <td class='td defaltfont' width='5%'>ক্র. নং</td>
                                                <td class='td defaltfont' width='25%'>খাত</td>
                                                <td class='td defaltfont' width='15%'>বর্তমানে পরিশোধকৃত টাকা</td>
                                                <td class='td defaltfont' width='15%'>মোট টাকার পরিমাণ</td>
                                            </tr>
                                        </thead>
                                        <tbody class='tbody'>
                                            <tr class='tr'>
                                                <td class='td defaltfont'>" . int_en_to_bn(1) . "</td>
                                                <td class='td defaltfont'>পেশা কর</td>
                                                <td class='td defaltfont'>" . int_en_to_bn($khatlist->pesaKor) . "</td>
                                                <td class='td defaltfont'>" . int_en_to_bn($khatlist->pesaKor) . "</td>
                                            </tr>
                                            <tr class='tr'>
                                                <td class='td defaltfont'>" . int_en_to_bn(2) . "</td>
                                                <td class='td defaltfont'>ট্রেড লাইসেন্স ফি</td>
                                                <td class='td defaltfont'>" . int_en_to_bn($khatlist->tredeLisenceFee) . "</td>
                                                <td class='td defaltfont'>" . int_en_to_bn($khatlist->tredeLisenceFee) . "</td>
                                            </tr>
                                            <tr class='tr'>
                                                <td class='td tdlist defaltfont'>" . int_en_to_bn(3) . "</td>
                                                <td class='td tdlist defaltfont'>ভ্যাট ও আয়কর</td>
                                                <td class='td tdlist defaltfont'>" . int_en_to_bn($amount) . "</td>
                                                <td class='td tdlist defaltfont'>" . int_en_to_bn($amount) . "</td>
                                            </tr>
                                        </tbody>
                                        <tfoot class='tfoot'>
                                            <tr class='tr'>
                                                <td colspan='3' class='defalttext td defaltfont' style='text-align:right; padding: 0 13px;'><p> মোট </p></td>
                                                <td class='td defaltfont'>" . int_en_to_bn($totalAmount) . "</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <p style='margin-top:15px;padding:0 15px;' class='defaltfont'>কথায় : $amountWord</p>
                                </div>
                            </div>
                            <div class='memofooter' style='margin-top:25px'>
                                <p style='float:left;width:30%;padding:10px 15px' class='defaltfont'>ইউপি সচিব/আদায়কারীর স্বাক্ষর</br>তারিখ: $created_at</p>
                                <p style='float:right;width:30%;text-align:right;padding:10px 15px' class='defaltfont'>ইউপি চেয়ারম্যানের স্বাক্ষর</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";

        return $html;
    }


    public function userDocument(Request $request, $name, $id)
    {
        ini_set('max_execution_time', '60000');
        ini_set("pcre.backtrack_limit", "50000000000000000");
        ini_set('memory_limit', '12008M');

        // Find the Sonod record by ID
        $row = Sonod::find($id);

        // Find the Sonodname and Unioninfo records
        $sonod = Sonodnamelist::where('bnname', $row->sonod_name)->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();

        // Prepare the English version of Sonodname for file naming
        $EnsonodName = str_replace(" ", "_", $sonod->enname);

        // Determine which PDF view to load based on Sonodname
        if ($EnsonodName == 'Certificate_of_Inheritance' || $EnsonodName == 'Inheritance_certificate') {
            $pdf = LaravelMpdf::loadView('userdocumentUt', compact('row', 'sonod', 'uniouninfo'));
        } else if ($EnsonodName == 'Miscellaneous_certificates' || $EnsonodName == 'Certification_of_the_same_name') {
            $pdf = LaravelMpdf::loadView('userdocument2', compact('row', 'sonod', 'uniouninfo'));
        } else {
            $pdf = LaravelMpdf::loadView('userdocument', compact('row', 'sonod', 'uniouninfo'));
        }

        // Generate and stream the PDF with a meaningful filename
        return $pdf->stream("$EnsonodName-$row->sonod_Id.pdf");
    }


    public function sonod_search(Request $request)
    {
        // Check for approved Sonod records matching the provided criteria
        $approvedSonod = Sonod::where([
            'sonod_name' => $request->sonod_name,
            'sonod_Id' => $request->sonod_Id,
            'status' => 'approved'
        ])->first();

        if ($approvedSonod) {
            // If approved Sonod record found, prepare response with URL and status
            $Sonodnamelist = Sonodnamelist::where('bnname', $request->sonod_name)->first();
            $approvedSonod['sonodUrl'] = "/sonod/$Sonodnamelist->enname/$approvedSonod->id";
            $approvedSonod['searchstatus'] = "approved";
            return $approvedSonod;
        } else {
            // If no approved Sonod record found, check for any Sonod record matching the criteria
            $sonod = Sonod::where([
                'sonod_name' => $request->sonod_name,
                'sonod_Id' => $request->sonod_Id
            ])->first();

            if ($sonod) {
                // If any Sonod record found (approved or not), return with status "all"
                $sonod['searchstatus'] = "all";
                return $sonod;
            }
        }

        // Return 0 if no Sonod records found
        return 0;
    }


    public function singlesonod(Request $request, $id)
    {
        $admin = $request->admin;

        if ($admin) {
            // If admin, fetch detailed data including asset URLs
            $sonod = Sonod::with('sonodnamelist')
                ->where('id', $id)
                ->firstOrFail();

            $sonod['image'] = asset($sonod->image);
            $sonod['applicant_national_id_front_attachment'] = asset($sonod->applicant_national_id_front_attachment);
            $sonod['applicant_national_id_back_attachment'] = asset($sonod->applicant_national_id_back_attachment);
            $sonod['applicant_birth_certificate_attachment'] = asset($sonod->applicant_birth_certificate_attachment);

            return [
                'sonod' => $sonod,
                'sonodnamedata' => $sonod->sonodnamelist, // Assuming 'sonodnamelist' is a relationship
            ];
        }

        // If not admin, return basic Sonod data
        return Sonod::findOrFail($id);
    }


    public function sonodcountall(Request $request)
    {
        $userid = $request->userid;
        $union = $request->union;

        if ($userid) {
            $user = User::find($userid);

            if ($user->position == 'District_admin') {
                // If user is a district admin, fetch union list based on district
                $unionlist = Uniouninfo::where('district', $user->district)->get();
            } else {
                // Otherwise, fetch union list based on thana
                $unionlist = Uniouninfo::where('thana', $user->thana)->get();
            }

            // Initialize an array to hold the total counts
            $total = [];

            // Iterate through each union and populate the total counts
            foreach ($unionlist as $value) {
                $union_name = $value->short_name_e;

                $counts = Sonod::selectRaw('stutus, count(*) as count')
                    ->where('unioun_name', $union_name)
                    ->whereIn('stutus', ['approved', 'Secretary_approved', 'Pending', 'cancel'])
                    ->groupBy('stutus')
                    ->pluck('count', 'stutus')
                    ->toArray();

                // Map status names to more readable values if needed
                $total[] = [
                    'Unionname' => unionname($value->short_name_e)->full_name,
                    'approved' => $counts['approved'] ?? 0,
                    'Secretary_approved' => $counts['Secretary_approved'] ?? 0,
                    'Pending' => $counts['Pending'] ?? 0,
                    'cancel' => $counts['cancel'] ?? 0,
                ];
            }

            return $total;
        }

        // If no user ID provided, return an empty response or handle accordingly
        return [];
    }

    public function totlaAmount(Request $request)
    {
        $userid = $request->userid;
        $union = $request->union;

        if ($union) {
            // Case 1: If union is provided, return total amount for that union
            return Payment::where(['status' => 'Paid', 'union' => $union])->sum('amount');
        } elseif ($userid) {
            // Case 2: If user ID is provided, fetch user's thana and calculate total amount for all unions in that thana
            $user = User::find($userid);
            $thana = $user->thana;

            // Fetch union list based on thana
            $unionlist = Uniouninfo::where('thana', $thana)->pluck('short_name_e');

            // Calculate total amount across all unions in the thana
            $totalamount = Payment::whereIn('union', $unionlist)
                ->where('status', 'Paid')
                ->sum('amount');

            return $totalamount;
        } else {
            // Case 3: If neither union nor user ID is provided, return total amount for all paid payments
            return Payment::where('status', 'Paid')->sum('amount');
        }
    }


    public function counting(Request $request, $status)
    {
        $union = $request->union;
        $userid = $request->userid;

        if ($userid) {
            // Fetch user details and determine union list based on user's role
            $user = User::find($userid);
            $thana = $user->thana;
            $unionlist = Uniouninfo::where('thana', $thana)->pluck('short_name_e');

            // Initialize counts
            $allSonodCount = 0;
            $pendingSonodCount = 0;
            $approvedSonodCount = 0;
            $cancelSonodCount = 0;

            foreach ($unionlist as $value) {
                // Calculate counts based on status
                if ($status == 'all') {
                    $allSonodCount += Sonod::where('stutus', '!=', 'Prepaid')->where('unioun_name', $value)->count();
                    $pendingSonodCount += Sonod::where('stutus', 'Pending')->where('unioun_name', $value)->count();
                    $approvedSonodCount += Sonod::where('stutus', 'approved')->where('unioun_name', $value)->count();
                    $cancelSonodCount += Sonod::where('stutus', 'cancel')->where('unioun_name', $value)->count();
                } else {
                    $pendingSonodCount += Sonod::where('stutus', 'Pending')->where('unioun_name', $value)->count();
                    $approvedSonodCount += Sonod::where('stutus', 'approved')->where('unioun_name', $value)->count();
                    $cancelSonodCount += Sonod::where('stutus', 'cancel')->where('unioun_name', $value)->count();
                }
            }

            // Prepare and return data
            $ReturnData = [
                'allSonodCount' => $allSonodCount,
                'pendingSonodCount' => $pendingSonodCount,
                'approvedSonodCount' => $approvedSonodCount,
                'cancelSonodCount' => $cancelSonodCount,
            ];

            return $ReturnData;
        }

        if ($union) {
            // Calculate counts based on union and status
            if ($status == 'all') {
                $allSonodCount = Sonod::where('stutus', '!=', 'Prepaid')->where('unioun_name', $union)->count();
                $pendingSonodCount = Sonod::where('stutus', 'Pending')->where('unioun_name', $union)->count();
                $approvedSonodCount = Sonod::where('stutus', 'approved')->where('unioun_name', $union)->count();
                $cancelSonodCount = Sonod::where('stutus', 'cancel')->where('unioun_name', $union)->count();

                $ReturnData = [
                    'allSonodCount' => $allSonodCount,
                    'pendingSonodCount' => $pendingSonodCount,
                    'approvedSonodCount' => $approvedSonodCount,
                    'cancelSonodCount' => $cancelSonodCount,
                ];
                return $ReturnData;
            }

            return Sonod::where(['stutus' => $status, 'unioun_name' => $union])->count();
        }

        // Calculate counts for all unions if no specific user or union is provided
        if ($status == 'all') {
            $allSonodCount = Sonod::where('stutus', '!=', 'Prepaid')->count();
        }
        $pendingSonodCount = Sonod::where('stutus', 'Pending')->count();
        $approvedSonodCount = Sonod::where('stutus', 'approved')->count();
        $cancelSonodCount = Sonod::where('stutus', 'cancel')->count();

        $ReturnData = [
            'allSonodCount' => $allSonodCount,
            'pendingSonodCount' => $pendingSonodCount,
            'approvedSonodCount' => $approvedSonodCount,
            'cancelSonodCount' => $cancelSonodCount,
        ];

        return $ReturnData;
    }

    public function niddob(Request $request)
    {
        $applicant_national_id_number = $request->applicant_national_id_number;
        $applicant_birth_certificate_number = $request->applicant_birth_certificate_number;

        if ($applicant_national_id_number) {
            return Citizen::where('nidno', $applicant_national_id_number)->count();
        }

        if ($applicant_birth_certificate_number) {
            return Citizen::where('dobno', $applicant_birth_certificate_number)->count();
        }

        return 0; // or handle the case where neither condition is met
    }

///download
    public function pdfHeader($id, $filename)
    {
        $row = Sonod::find($id);
        $sonod_name = $row->sonod_name;
        $sonod = Sonodnamelist::where('bnname', $sonod_name)->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();
        $EnsonodName = str_replace(" ", "_", $sonod->enname);

        $qrurl = url("/verification/sonod/$row->id?sonod_name=$sonod->enname&sonod_Id=$row->sonod_Id");
        $qrcode = \QrCode::size(70)
                        ->format('svg')
                        ->generate($qrurl);
        $qrcode = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrcode);

        $sonodNO = '
            <div class="signature text-center position-relative">
                সনদ নং: ' . int_en_to_bn($row->sonod_Id) . ' <br />
                ইস্যুর তারিখ: ' . int_en_to_bn(date("d/m/Y", strtotime($row->created_at))) . '
            </div>';

        $headerLeft = '';
        if ($row->unioun_name == 'gognagar') {
            $headerLeft = '
                <div class="signature text-center position-relative">
                    ' . $qrcode . '<br/>
                    ' . $sonodNO . '
                </div>';
        } else {
            $headerLeft = '
                <span style="color:#b400ff;">
                    <b>উন্নয়নের গণতন্ত্র, <br /> শেখ হাসিনার মূলমন্ত্র </b>
                </span>';
        }

        $output = '
            <div class="nagorik_sonod" style="margin-bottom:10px;">
                <div style="
                    background-color: #159513;
                    color: #fff;
                    font-size: 30px;
                    border-radius: 30em;
                    width:320px;
                    margin:10px auto;
                    margin-bottom:0px;
                    text-align:center
                ">' . changeSonodName($sonod_name) . '</div>
                <br>
                <table width="100%" style="border-collapse: collapse;" border="0">
                    <tr>
                        <td style="text-align: center;" width="20%">
                            ' . $headerLeft . '
                        </td>
                        <td style="text-align: center;" width="20%">
                            <img width="70px" src="' . base64('backend/bd-logo.png') . '">
                        </td>
                        <td style="text-align: center;" width="20%">
                            <p style="font-size:20px">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                        </td>
                    </tr>
                    <tr style="margin-top:2px;margin-bottom:2px;">
                        <td colspan="3" style="text-align: center;">
                            <h1 style="color: #7230A0; margin: 0px; font-size: 28px">' . $uniouninfo->full_name . '</h3>
                        </td>
                    </tr>
                    <tr style="margin-top:2px;margin-bottom:2px;">
                        <td colspan="3" style="text-align: center;">
                            <p style="font-size:20px">উপজেলা: ' . $uniouninfo->thana . ', জেলা: ' . $uniouninfo->district . ' ।</p>
                        </td>
                    </tr>';

        if ($row->unioun_name == 'gognagar') {
            $output .= '
                <tr style="margin-top:2px;margin-bottom:2px;">
                    <td colspan="3" style="text-align: center;">
                        <p style="font-size:12px">ওয়েবসাইটঃ https://gognagarup.narayanganj.gov.bd</p>
                        <p style="font-size:12px">ইমেলঃ ' . $row->c_email . '</p>
                    </td>
                </tr>';
        }

        $output .= '
                </table>
            </div>';

        return $output;
    }


    public function pdfFooter($id, $filename)
    {
        $row = Sonod::find($id);
        $sonod_name = $row->sonod_name;
        $sonod = Sonodnamelist::where('bnname', $sonod_name)->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();
        $EnsonodName = str_replace(" ", "_", $sonod->enname);

        $sonodNO = '
            <div class="signature text-center position-relative">
                সনদ নং: ' . int_en_to_bn($row->sonod_Id) . ' <br />
                ইস্যুর তারিখ: ' . int_en_to_bn(date("d/m/Y", strtotime($row->created_at))) . '
            </div>';

        $C_color = '#7230A0';
        $C_size = '18px';
        $color = 'black';
        $style = '';

        if ($row->unioun_name == 'dhamor') {
            $C_color = '#5c1caa';
            $C_size = '20px';
            $color = '#5c1caa';
        }

        if ($row->unioun_name == 'toria') {
            $C_color = '#5c1caa';
            $style = "margin-bottom: -33px; margin-left: 83px;";
        }

        $ccc = '<img width="170px" style="'.$style.'" src="' . base64($row->chaireman_sign) . '"><br/>
                <b><span style="color:'.$C_color.';font-size:'.$C_size.';">' . $row->chaireman_name . '</span> <br />
                </b><span style="font-size:16px;">'.$row->chaireman_type.'</span><br />';

        $qrurl = url("/verification/sonod/$row->id?sonod_name=$sonod->enname&sonod_Id=$row->sonod_Id");
        $qrcode = \QrCode::size(70)
                        ->format('svg')
                        ->generate($qrurl);
        $qrcode = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrcode);

        $email = '';
        $footerLeft = '';

        if ($row->unioun_name == 'gognagar') {
            $footerLeft = "
                <div class='signature text-center position-relative' style='color:black'>
                    <br/>
                    <b><span style='color:#7230A0;font-size:18px;'></span> <br />
                    </b><span style='font-size:16px;'>ইউপি সদস্য/সদস্যা</span><br />
                    $uniouninfo->full_name <br>  $uniouninfo->thana ,  $uniouninfo->district  ।
                    <br>
                </div>";
        } else {
            $email = $row->c_email;
            $footerLeft = '
                <div class="signature text-center position-relative">
                    ' . $qrcode . '<br/>
                    ' . $sonodNO . '
                </div>';
        }

        $output = '
            <table width="100%" style="border-collapse: collapse;" border="0">
                <tr>
                    <td style="text-align: center;vertical-align: bottom;" width="40%">
                        '.$footerLeft.'
                    </td>
                    <td style="text-align: center; width: 200px;" width="30%">
                        <img width="100px" src="' . base64($uniouninfo->sonod_logo) . '">
                    </td>
                    <td style="text-align: center;" width="40%">
                        <div class="signature text-center position-relative" style="color:'.$color.'">
                            '.$ccc.' '.$uniouninfo->full_name.' <br> '.$uniouninfo->thana.', '.$uniouninfo->district.' ।<br/>
                            '.$email.'
                        </div>
                    </td>
                </tr>
            </table>
            <p style="background: #787878; color: white; text-align: center; padding: 2px 2px;font-size: 16px; margin-top: 0px;" class="m-0">"সময়মত ইউনিয়ন কর পরিশোধ করুন। ইউনিয়নের উন্নয়নমূক কাজে সহায়তা করুন"</p>
            <p class="m-0" style="font-size:14px;text-align:center">ইস্যুকৃত সনদটি যাচাই করতে QR কোড স্ক্যান করুন অথবা ভিজিট করুন ' . $uniouninfo->domain . '</p>
        ';

        $output = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $output);
        return $output;
    }


    public function pdfHTMLut($id, $filename)
    {
        $row = Sonod::find($id);
        $sonod_name = $row->sonod_name;

        // Determine the appropriate text based on sonod_name
        if ($sonod_name == 'ওয়ারিশান সনদ') {
            $text = 'ওয়ারিশ/ওয়ারিশগণের নাম ও সম্পর্ক';
            $deathStatus = ($row->ut_religion == 'ইসলাম') ? 'মরহুম' : 'স্বর্গীয়';
            $deathStatus2 = ($row->ut_religion == 'ইসলাম') ? 'মরহুমের' : 'স্বর্গীয় ব্যক্তির';
        } else {
            $text = 'উত্তরাধিকারীগণের নাম ও সম্পর্ক';
        }

        // Fetch necessary data
        $sonod = Sonodnamelist::where('bnname', $sonod_name)->first();
        $uniouninfo = Uniouninfo::where('short_name_e', $row->unioun_name)->first();
        $w_list = json_decode($row->successor_list);

        // URL for QR code
        $sonodurl = 'https://' . $_SERVER['HTTP_HOST'] . '/pdf/download' . '/' . $id;

        // Generate QR code
        $qrcode = \QrCode::size(70)
            ->format('svg')
            ->generate($sonodurl);
        $qrcode = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $qrcode);

        // Prepare the nagoriinfo HTML
        $nagoriinfo = '';

        if ($sonod_name == 'ওয়ারিশান সনদ') {
            $nagoriinfo .= '
                <p style="margin-top:0px;margin-bottom:5px;font-size:15px;text-align:justify">
                    &nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, ' . $deathStatus . ' ' . $row->utname . ', পিতা/স্বামী- ' . $row->ut_father_name . ', মাতা- ' . $row->ut_mother_name . ', গ্রাম- ' . $row->ut_grame . ', ডাকঘর- ' . $row->ut_post . ', উপজেলা: ' . $row->ut_thana . ', জেলা- ' . $row->ut_district . '। তিনি অত্র ইউনিয়নের ' . int_en_to_bn($row->ut_word) . ' নং ওয়ার্ডের ' . $row->applicant_resident_status . ' বাসিন্দা ছিলেন। মৃত্যুকালে তিনি নিম্নোক্ত ওয়ারিশগণ রেখে যান। নিম্নে তাঁর ওয়ারিশ/ওয়ারিশগণের নাম ও সম্পর্ক উল্লেখ করা হলো।<br>
                    <br>&nbsp; &nbsp; &nbsp; আমি ' . $deathStatus2 . ' বিদেহী আত্মার মাগফেরাত কামনা করি।
                </p>';

            if ($row->unioun_name == 'balarampur') {
                $nagoriinfo .= '<p style="margin: 0;font-size:14px;">বিঃদ্রঃ উক্ত ওয়ারিশান সনদের সকল দায়ভার  সংশ্লিষ্ট ইউপি সদস্য/সদস্যার যাচাইকারীর ওপর বর্তাইবে ।</p>';
            }
        } else {
            $nagoriinfo .= '
                <p style="margin-top:0px;margin-bottom:5px;font-size:15px;text-align:justify">
                    &nbsp; &nbsp; &nbsp; এই মর্মে প্রত্যয়ন করা যাচ্ছে যে, জনাব ' . $row->utname . ', পিতা/স্বামী- ' . $row->ut_father_name . ', মাতা- ' . $row->ut_mother_name . ', গ্রাম- ' . $row->ut_grame . ', ডাকঘর- ' . $row->ut_post . ', উপজেলা: ' . $row->ut_thana . ', জেলা- ' . $row->ut_district . '। তিনি অত্র ইউনিয়নের ' . int_en_to_bn($row->ut_word) . ' নং ওয়ার্ডের ' . $row->applicant_resident_status . ' বাসিন্দা। নিম্নে তাঁর উত্তরাধিকারী/উত্তরাধিকারীগণের নাম ও সম্পর্ক উল্লেখ করা হলো।<br>
                    <br>
                </p>';

            if ($row->unioun_name == 'balarampur') {
                $nagoriinfo .= '<p style="margin: 0;font-size:14px;">বিঃদ্রঃ উক্ত উত্তরাধিকারী সনদের সকল দায়ভার  সংশ্লিষ্ট ইউপি সদস্য/সদস্যার যাচাইকারীর ওপর বর্তাইবে ।</p>';
            }
        }

        // Table header and rows for successor list
        $nagoriinfo .= '
            <h4 style="text-align:center;margin-bottom:0px">' . $text . '</h4>
            <table class="table " style="width:100%;border-collapse: collapse;" cellspacing="0" cellpadding="0">
                <tr>
                    <th style="border: 1px dotted black;padding:4px 10px;font-size: 12px;" width="10%">ক্রমিক নং</th>
                    <th style="border: 1px dotted black;padding:4px 10px;font-size: 12px;" width="30%">নাম</th>
                    <th style="border: 1px dotted black;padding:4px 10px;font-size: 12px;" width="10%">সম্পর্ক</th>
                    <th style="border: 1px dotted black;padding:4px 10px;font-size: 12px;" width="10%">বয়স</th>
                    <th style="border: 1px dotted black;padding:4px 10px;font-size: 12px;" width="20%">জাতীয় পরিচয়পত্র নাম্বার/জন্মনিবন্ধন নাম্বার</th>
                </tr>';

        $i = 1;
        foreach ($w_list as $rowList) {
            $nagoriinfo .= '
                <tr>
                    <td style="text-align:center;border: 1px dotted black;padding:4px 10px;font-size: 12px;">' . int_en_to_bn($i) . '</td>
                    <td style="text-align:center;border: 1px dotted black;padding:4px 10px;font-size: 12px;">' . $rowList->w_name . '</td>
                    <td style="text-align:center;border: 1px dotted black;padding:4px 10px;font-size: 12px;">' . $rowList->w_relation . '</td>
                    <td style="text-align:center;border: 1px dotted black;padding:4px 10px;font-size: 12px;">' . int_en_to_bn($rowList->w_age) . '</td>
                    <td style="text-align:center;border: 1px dotted black;padding:4px 10px;font-size: 12px;">' . int_en_to_bn($rowList->w_nid) . '</td>
                </tr>';
            $i++;
        }

        $nagoriinfo .= '</table>';

        // Additional applicant information
        $nagoriinfo .= '
            <p style="margin-top:-10px;margin-bottom:5px">
                আবেদনকারীর নামঃ ' . $row->applicant_name . '।  পিতা/স্বামীর নামঃ ' . $row->applicant_father_name . '।  মাতার নামঃ ' . $row->applicant_mother_name . '
            </p><br>

            <p style="margin-top:-10px;margin-bottom:5px">
                সংশ্লিষ্ট ওয়ার্ডের ইউপি সদস্য কর্তৃক আবেদনকারীর দাখিলকৃত তথ্য যাচাই/সত্যায়নের পরিপ্রেক্ষিতে অত্র সনদপত্র প্রদান করা হলো।
            </p><br/>

            <p style="margin-top:-10px;margin-bottom:0px">
                &nbsp; &nbsp; &nbsp; আমি তাঁর/তাঁদের সর্বাঙ্গীন উন্নতি ও মঙ্গল কামনা করছি।
            </p>';

        // Final output
        $output = ' ';
        $output .= $nagoriinfo;
        return $output;
    }

    public function verifysonodId(Request $request)
    {
        // Validate incoming request data
        // $request->validate([
        //     'sonod_name' => 'required|string',  // Adjust validation rules as per your application's requirements
        //     'sonod_Id' => 'required|string',    // Adjust validation rules as per your application's requirements
        // ]);

        try {
            // Fetch the Bengali name corresponding to the English sonod_name
            $sonod_name = Sonodnamelist::where('enname', $request->sonod_name)->value('bnname');

            if (!$sonod_name) {
                throw new \Exception('Sonod name not found.');
            }

            // Fetch the Sonod record based on Bengali sonod_name and sonod_Id
            $sonod = Sonod::where('sonod_name', $sonod_name)
                          ->where('sonod_Id', $request->sonod_Id)
                          ->first();

            if (!$sonod) {
                throw new \Exception('Sonod record not found.');
            }

            return $sonod;

        } catch (\Exception $e) {
            // Handle exceptions here (log, notify, etc.)
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }


    public function sonod_submit_pre_pay(Request $r)
    {
        $data = $r->except(['sonod_Id']); // Exclude sonod_Id from the request data
        $random = Str::random(60);

        // Set unique key and payment type
        $data['uniqeKey'] = $random;
        $data['pBy'] = 'Pre Pay';
        $data['applicant_type_of_businessKhatAmount'] = $r->applicant_type_of_businessKhatAmount ?? 0;
        $data['applicant_type_of_businessKhat'] = $r->applicant_type_of_businessKhat;

        // Fetch sonod_Id based on unioun_name and sonod_name
        $unioun_name = $r->unioun_name;
        $sonod_name = $r->sonod_name;
        $data['sonod_Id'] = $this->allsonodId($unioun_name, $sonod_name);

        // Process data based on payment status
        $status = $r->stutus;
        if ($status == 'Prepaid') {
            $charages = $r->charages;

            // Prepare detailed payment information
            $arraydata = [
                'total_amount' => $charages['totalamount'],
                'pesaKor' => $charages['pesaKor'],
                'tredeLisenceFee' => $charages['sonod_fee'],
                'vatAykor' => $charages['tradeVat'],
                'khat' => '',
                'last_years_money' => 0,
                'currently_paid_money' => $charages['totalamount'],
            ];

            // Convert amount to Bangla words
            $numto = new NumberToBangla();
            $the_amount_of_money_in_words = $numto->bnMoney($charages['totalamount']) . ' মাত্র';

            // Assign calculated values to $data array
            $data['khat'] = '';
            $data['last_years_money'] = 0;
            $data['currently_paid_money'] = $charages['totalamount'];
            $data['total_amount'] = $charages['totalamount'];
            $data['the_amount_of_money_in_words'] = $the_amount_of_money_in_words;
            $data['amount_deails'] = json_encode($arraydata);
        }

        // Create Sonod record
        $sonod = Sonod::create($data);

        return $sonod;
    }


    public function preapidSonod(Request $request)
    {
        // Determine the date to filter by
        $dates = $request->date ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d');

        // Fetch Sonods with payments where stutus is 'Prepaid' and created_at matches the specified date
        $sonods = Sonod::with('payments')
                        ->where('stutus', 'Prepaid')
                        ->whereDate('created_at', $dates)
                        ->get();

        return $sonods;
    }





}
