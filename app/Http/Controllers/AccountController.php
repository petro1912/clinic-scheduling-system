<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Log;

class AccountController extends Controller
{

    private function getAllProfessionals() {
        $accounts = User::where('role', '!=', 'patient')->get();

        return response()->json([
            'status' => 'success',
            'accounts' => $accounts
        ]);
    }

    public function getProfessionals(Request $request) {
        return $this->getAllProfessionals();
    }

    public function updateProfessional(Request $request) {
        $admin = Auth::user();
        // if ($admin->role != 'admin') {
        //     return response()->json([
        //         'status' =>'failed',
        //         'message' => 'Required admin privileges'
        //     ]);
        // }


        if ($request->id == -1) {
            if (User::where('email', $request->email)->count() != 0) {
                return response()->json([
                    'status' =>'failed',
                    'message' => 'Email has already been registered'
                ]);
            }
            $user = new User();    
        } else {
            $user = User::find($request->id);
            if ($user == null) {
                return response()->json([
                    'status' =>'failed',
                    'message' => 'Account has not been registered'
                ]);
            }
        }

        $user->name = $request->firstName." ".$request->lastName;
        $user->email = $request->email;
        $user->phone = '-';
        $user->sex = 1;
        $user->dob = '1988-02-02';
        $user->idType = $request->idType;
        $user->idNumber = $request->idNumber;
        if ($request->id == -1 || $request->password != '')
            $user->password = bcrypt($request->password);

        $user->role = $request->role;
        $user->registerNumber = $request->registerNumber;
        $user->speciality = $request->speciality;

        $user->save();
        
        return $this->getAllProfessionals();
    }

}