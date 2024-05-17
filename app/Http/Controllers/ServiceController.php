<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Availability;
use App\Models\Schedule;

class ServiceController extends Controller
{
    //

    /**
     * Get all services
    *
    * @return  services[] services by doctor
    */
    public function getAllServices(Request $request)
    {
        $services = Service::all();
        
        return response()->json([
            'services' => $services,
        ]);
    }

    /**
     * Login user and create token
    *
    * @return  services[] services by doctor
    */
    public function getAllServicesWithAvailability(Request $request)
    {
        $today = now()->toDateString();
        $services = Service::with('doctor')->get();
        $availability = Availability::all()->first();
        $schedules = Schedule::where('booked_date', '>=', $today)->get();

        return response()->json([
            'services' => $services,
            'availability' => $availability,
            'schedules' => $schedules
        ]);
    }

    /**
     * Login user and create token
    *
    * @return  services[] services by doctor
    */
    public function getDoctorServices(Request $request)
    {
        $services = Service::all();

        return response()->json([
            'services' =>$services
        ]);
    }

    public function updateDoctorService(Request $request)
    {
        $id = $request->id;
        $doctor = User::where('role', 'doctor')->first();

        if (!isset($doctor)){
            return response()->json([
                'message' => 'Please add doctor first in users menu.'
            ], 400);
        }
        
        if ($id == -1) {
            $service = new Service();
        } else {
            $service = Service::find($id);
            if ($service->user_id != $doctor->id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
        }

        $service->name = $request->name;
        $service->price = $request->price;
        $service->duration = $request->duration;
        $service->user_id = $doctor->id;
        $service->save();
        
        $services = Service::where('user_id', $doctor->id)->get();

        return response()->json([
            'services' =>$services
        ]);
    }

    public function deleteDoctorService($id)
    {
        $doctor = Auth::user();
        $service = Service::find($id);
        
        if ($service->user_id != $doctor->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $service->delete();

        $services = Service::where('user_id', $doctor->id)->get();

        return response()->json([
            'services' =>$services
        ]);
    }
}
