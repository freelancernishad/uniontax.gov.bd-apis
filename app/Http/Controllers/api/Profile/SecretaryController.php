<?php

namespace App\Http\Controllers\API\Profile;

use App\Models\Secretary;
use App\helper\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SecretaryController extends Controller
{
    /**
     * Display the profile of the authenticated secretary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $token = $request->bearerToken(); // Fetch bearer token from request

        $secretary = Auth::guard('secretary')->setToken($token)->user(); // Authenticate secretary user

        if (!$secretary) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['secretary' => $secretary], 200);
    }

    /**
     * Update the profile of the authenticated secretary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $token = $request->bearerToken(); // Fetch bearer token from request

        $secretary = Auth::guard('secretary')->setToken($token)->user(); // Authenticate secretary user

        if (!$secretary) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data including file upload
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'union' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'signature' => 'sometimes|file|max:10240|mimes:jpeg,png,gif', // Example validation for image files, adjust as needed
        ]);

        // Handle file upload if 'signature' file is present in the request
        if ($request->hasFile('signature')) {
            try {
                $file = $request->file('signature');
                $filePath = FileHelper::uploadFile($file, 'secretary/signature', ['image/jpeg', 'image/png', 'image/gif']); // Call helper function with mime types
                $secretary->signature = $filePath; // Save file path to database field
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
        }

        // Update other fields based on request data
        $secretary->fill($request->only(['name', 'union', 'type']));

        // Save changes
        $secretary->save();

        return response()->json(['message' => 'Profile updated successfully', 'secretary' => $secretary], 200);
    }
}
