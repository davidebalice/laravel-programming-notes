<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class UserController extends Controller
{
    public function Dashboard(){

        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('user',compact('userData'));
    }

    public function Store(Request $request){

        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function Logout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'User logout successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    }

    public function UpdatePassword(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old password doesn't match!!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password changed successfully");
    }
    
    public function UserAccount(){
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('frontend.user.account',compact('userData'));
    }

    public function UserChangePassword(){
         return view('frontend.user.change_password' );
    }
}