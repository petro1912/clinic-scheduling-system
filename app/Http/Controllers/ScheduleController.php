<?php

namespace App\Http\Controllers;

use App\Mail\Reserved;
use App\Models\User;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\BasicHealth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Zoom;
use Log;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function register(Request $request) {
        $cardNumer = $request->cardNumber;
        $cardHoler = $request->cardHolder;
        $cardExpiry = $request->cardExpiry;
        $cardCVV = $request->cardCVV;
        
        $clinicFile = $request->file('clinicFiles');
        $isMedicalOrder = $request->isMedicalOrder;
        
        if (!$isMedicalOrder) {
            if ($clinicFile == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "Debe cargar un documento sobre el resultado de su exámenes.",
                ]);
            } else {
                $destinationPath = 'uploads';
                $fileName = Str::random(20).".".$clinicFile->getClientOriginalExtension();

                $clinicFile->move($destinationPath, $fileName);
            }
        }

        $patient = User::create([
            "name" => $request->firstName." ".$request->lastName,
            "email" => $request->email,
            "phone" => $request->phone, 
            "sex" => $request->sex,
            "dob" => $request->dob,
            "idType" => $request->idType,
            "idNumber" => $request->idNumber,
            "password" => bcrypt($request->password),
            "role"=> "patient"
        ]);

        $patientId = $patient->fresh()->id;

        $healthInfo = BasicHealth::create([
            'user_id' => $patientId,
            'chronicIllness' => $request->chronicIllness,
            'drugsDaily' => $request->drugsDaily,
            'surgeryBefore' => $request->surgeryBefore,
            'cancerDiabetes' => $request->cancerDiabetes,
            'isMedicalOrder' => $request->isMedicalOrder? 1 : 0,
            'examResult' => $request->isMedicalOrder? null : $fileName,
        ]);

        $healthId = $healthInfo->fresh()->id; 
        $service = Service::find($request->service_id);
        list($start_at, $end_at) = explode(" - ", $request->time);

        $schedule = Schedule::create([
            'service_id' => $request->service_id,
            'patient_id' => $patientId,
            'doctor_id' => $service->user_id,
            'health_id' => $healthId,
            'booked_date' => Carbon::parse($request->booked_date)->toDateString(),
            'start_time' => $start_at,
            'end_time' => $end_at,
        ]);

        return response()->json([
            "status" => "success"            
        ]);        
    }

    public function getAllSchedules(Request $request) {
        $schedules = Schedule::with(['service', 'patient', 'health'])->get();
        
        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function getMySchedules(Request $request) {
        $user = Auth::user();
        $schedules = Schedule::with(['service', 'doctor', 'patient', 'health'])->where('patient_id', $user->id)->get();
        
        return response()->json([
            'schedules' => $schedules
        ]);
    }

    public function createMeeting(Request $request) {
        $schedule_id = $request->id;
        $schedule = Schedule::with('doctor', 'patient', 'service')->find($schedule_id);
        $schedule_for = $schedule->doctor->email;
        $start_time = $schedule->booked_date." ".$schedule->start_time;
        $agenda = "Clinic Consultation with ".$schedule->doctor->name;
        $topic = $schedule->service->name;
        $duration = $schedule->service->duration;

        $meetings = Zoom::createMeeting([
            "agenda" => $agenda,
            "topic" => $topic,
            "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => $duration, // in minutes
            "timezone" => 'America/Santiago', // set your timezone
            "password" => 'ghostman',
            "start_time" => $start_time, // set your start time
            "template_id" => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
            "pre_schedule" => false,  // set true if you want to create a pre-scheduled meeting
            "schedule_for" => "contacto@ssgastro.cl", // set your schedule for
            "settings" => [
                'join_before_host' => false, // if you want to join before host set true otherwise set false
                'host_video' => false, // if you want to start video when host join set true otherwise set false
                'participant_video' => false, // if you want to start video when participants join set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],
        ]);
        
        if ($meetings['status']) {
            $schedule->link = $meetings['data']['join_url'];
            $schedule->save();

            // send mail
            $mail = $schedule->patient->email;
            $name = $schedule->patient->name;

            Mail::to($mail)->send(new Reserved([
                'name' => $name,
                'link' => $meetings['data']['join_url']
            ]));

            $schedules = Schedule::with(['service', 'patient', 'health'])->get();
        
            return response()->json([
                'status' => 'success',
                'schedules' => $schedules
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
            ]);
        }
        
    }
}
