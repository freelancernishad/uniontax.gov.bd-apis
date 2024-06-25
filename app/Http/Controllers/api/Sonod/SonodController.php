<?php

namespace App\Http\Controllers\API\Sonod;

use App\helper\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SonodController extends Controller
{
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





}
