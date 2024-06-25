<?php

namespace App\Http\Controllers\API\Profile;

use App\Models\Chairman;
use App\helper\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChairmanController extends Controller
{
    /**
     * Display the profile of the authenticated chairman.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $token = $request->bearerToken(); // Fetch bearer token from request

        $chairman = Auth::guard('chairman')->setToken($token)->user(); // Authenticate chairman user

        if (!$chairman) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['chairman' => $chairman], 200);
    }

    /**
     * Update the profile of the authenticated chairman.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $token = $request->bearerToken(); // Fetch bearer token from request

        $chairman = Auth::guard('chairman')->setToken($token)->user(); // Authenticate chairman user

        if (!$chairman) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data including file upload
        $request->validate([
            'name' => 'sometimes|string|max:255',
            // 'email' => 'sometimes|string|email|max:255',
            'union' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'signature' => 'sometimes|file|max:10240|mimes:jpeg,png,gif', // Example validation for image files, adjust as needed
        ]);

        // Handle file upload if 'signature' file is present in the request
        if ($request->hasFile('signature')) {
            try {
                $file = $request->file('signature');
                $filePath = FileHelper::uploadFile($file, 'chairman/signature', ['image/jpeg', 'image/png', 'image/gif']); // Call helper function with mime types
                $chairman->signature = $filePath; // Save file path to database field
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        // Update other fields based on request data
        $chairman->fill($request->only(['name', 'union', 'type']));

        // Save changes
        $chairman->save();

        return response()->json(['message' => 'Profile updated successfully', 'chairman' => $chairman], 200);
    }
}
