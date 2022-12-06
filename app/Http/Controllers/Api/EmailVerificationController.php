<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verify(Request $request){
        $userId = $request['id'];
        $user = User::findOrFail($userId);
        $date = date("Y-m-d g:i:s");
        $user->email_verified_at = $date; // Memberikan tanggal waktu verif kapan
        $user->save();
        return redirect()->to('http://localhost:8082/login?verified=success');
    }

    public function resend(Request $request){
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', 422);
            // return redirect($this->redirectPath());
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
        // return back()->with('resent', true);
    }
}
