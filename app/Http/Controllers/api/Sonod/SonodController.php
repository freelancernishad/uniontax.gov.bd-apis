<?php

namespace App\Http\Controllers\API\Sonod;

use App\helper\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SonodController extends Controller
{
    public function sonod_submit(Request $request)
    {
        try {
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                // Define your validation rules here
                // Example: 'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Retrieve data from request
            $union = $request->unioun_name;
            $sonodname = $request->sonod_name;
            $orthoBchor = $request->orthoBchor; // Assuming this is part of the request data

            // Generate sonod_Id using allsonodId function
            $sonodId = makeSonodId($union, $sonodname, $orthoBchor);

            // Prepare data for Sonod creation
            $data = $request->except([
                'sonod_Id', 'image', 'applicant_national_id_front_attachment',
                'applicant_national_id_back_attachment', 'applicant_birth_certificate_attachment',
                'successors', 'charages', 'Annual_income', 'applicant_type_of_businessKhat',
                'applicant_type_of_businessKhatAmount', 'orthoBchor'
            ]);

            $sonodEnName =  Sonodnamelist::where('bnname', $request->sonod_name)->first();
            $filepath =  str_replace(' ', '_', $sonodEnName->enname);
            $dateFolder = date("Y/m/d");
            // Handle file uploads using FileHelper
            $imagePath = FileHelper::uploadFile($request->file('image'),  "sonod/$filepath/$dateFolder/$sonodId/", ['image/jpeg', 'image/png'], 1024 * 250, 'public');
            $data['image'] = $imagePath;

            // Repeat similar process for other file uploads
            $nationalIdFrontPath = FileHelper::uploadFile($request->file('applicant_national_id_front_attachment'), "sonod/$filepath/$dateFolder/$sonodId/", ['image/jpeg', 'image/png'], 1024 * 250, 'public');
            $data['applicant_national_id_front_attachment'] = $nationalIdFrontPath;

            $nationalIdBackPath = FileHelper::uploadFile($request->file('applicant_national_id_back_attachment'), "sonod/$filepath/$dateFolder/$sonodId/", ['image/jpeg', 'image/png'], 1024 * 250, 'public');
            $data['applicant_national_id_back_attachment'] = $nationalIdBackPath;

            $birthCertificatePath = FileHelper::uploadFile($request->file('applicant_birth_certificate_attachment'), "sonod/$filepath/$dateFolder/$sonodId/", ['image/jpeg', 'image/png'], 1024 * 250, 'public');
            $data['applicant_birth_certificate_attachment'] = $birthCertificatePath;

            // Handle Annual_income and generate Annual_income_text
            $annualIncome = $request->Annual_income;
            if (!empty($annualIncome)) {
                $data['Annual_income'] = $annualIncome;
                $data['Annual_income_text'] = $this->convertToBanglaMoney($annualIncome);
            }

            // Handle successor_list JSON encoding
            $successors = $request->successors;
            if (!empty($successors)) {
                $data['successor_list'] = json_encode($successors);
            }

            // Get Uniouninfo details
            $uniounInfo = Uniouninfo::where('short_name_e', $union)->latest()->first();
            if (!$uniounInfo) {
                throw new Exception('Uniouninfo not found for ' . $union);
            }

            // Assign chairman and socib details
            $data['chaireman_name'] = $uniounInfo->c_name;
            $data['c_email'] = $uniounInfo->c_email;
            $data['chaireman_sign'] = $uniounInfo->c_signture;
            $data['socib_name'] = $uniounInfo->socib_name;
            $data['socib_email'] = $uniounInfo->socib_email;
            $data['socib_signture'] = $uniounInfo->socib_signture;

            // Create Sonod record
            $sonod = Sonod::create($data);

            // Send notifications if necessary (example)
            $this->sendNotifications($sonod);

            // Return the created Sonod object as JSON response
            return response()->json(['sonod' => $sonod], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
