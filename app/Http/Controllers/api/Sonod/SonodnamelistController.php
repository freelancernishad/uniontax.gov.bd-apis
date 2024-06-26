<?php

namespace App\Http\Controllers\api\Sonod;

use Illuminate\Http\Request;
use App\Models\Sonodnamelist;
use App\Http\Controllers\Controller;

class SonodnamelistController extends Controller
{
    public function sonodCount(Request $request)
    {
        $userid = $request->userid;
        $union = $request->union;
        $position = $request->position;
        $Upazila = '';

        if ($userid) {
            $user = User::find($userid);
            $Upazila = $user->thana;
        }

        $sonodCount = [];
        $sonodnamelist = Sonodnamelist::all();

        foreach ($sonodnamelist as $value) {
            $penddingStatus = 'Pending';
            $Secretary_approvedstatus = 'Secretary_approved';
            $approvedstatus = 'approved';

            if ($position == 'Chairman') {
                $penddingStatus = 'Secretary_approved';
            }

            $conditions = [
                'sonod_name' => $value->bnname,
            ];

            if ($union != '') {
                $conditions['unioun_name'] = $union;
            }

            if ($userid) {
                $conditions['applicant_present_Upazila'] = $Upazila;
            }

            $sonodCount['Pending'][str_replace(" ", "_", $value->enname)] = Sonod::where(array_merge($conditions, ['stutus' => $penddingStatus]))->count();
            $sonodCount['Secretary_approved'][str_replace(" ", "_", $value->enname)] = Sonod::where(array_merge($conditions, ['stutus' => $Secretary_approvedstatus]))->count();

            if ($Secretary_approvedstatus == 'Secretary_approved') {
                $sonodCount['approved'][str_replace(" ", "_", $value->enname)] = Sonod::where(array_merge($conditions, ['stutus' => $approvedstatus, 'payment_status' => 'Unpaid']))->count();
            } else {
                $sonodCount['approved'][str_replace(" ", "_", $value->enname)] = Sonod::where(array_merge($conditions, ['stutus' => $approvedstatus]))->count();
            }
        }

        return $sonodCount;
    }

}
