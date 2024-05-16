<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Availability;

class AvailabilityController extends Controller
{
    //
    public function getAvailability(Request $request)
    {
        $doctor = Auth::user();
        $availability = Availability::where('user_id', $doctor->id)->first();

        return response()->json([
            'availability' => $availability
        ]);
    }

    public function updateAvailability(Request $request)
    {

        $doctor = Auth::user();
        $availability = Availability::where('user_id', $doctor->id)->first();
        if ($availability == null) {
            $availability = new Availability();
        }

        $availability->user_id = $doctor->id;
        $availability->start_date = $request->start_date;
        $availability->end_date = $request->end_date; 
        $availability->start_time = $request->start_time;
        $availability->end_time = $request->end_time; 
        $availability->days = implode(',', $request->days); 

        // Log::info("avail: {id}", ['id' => json_encode($availability)]);

        $availability->save();

        $availability = Availability::where('user_id', $doctor->id)->first();

        return response()->json([
            'availability' => $availability
        ]);
    }
}
