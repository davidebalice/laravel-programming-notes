<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Notifications\VendorRegNotification;
use Illuminate\Support\Facades\Notification;

class VendorController extends Controller
{
    public function Dashboard(){
        return  view('vendor.index');
    }

    public function Login(){
        return view('vendor.login');
    }

    public function Destroy(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/vendor/login');
    }

    public function Profile(){
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.profile',compact('vendorData'));
    }

    public function Store(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 
        $data->vendor_join = $request->vendor_join; 
        $data->vendor_short_info = $request->vendor_short_info; 

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Vendor profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function ChangePassword(){
        return view('vendor.change_password');
    }

    public function UpdatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old password doesn't match!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password changed successfully");
    }

    public function Become(){
        return view('auth.become_vendor');
    }

    public function Register(Request $request) {
        $vuser = User::where('role','admin')->get();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::insert([ 
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'vendor_join' => Carbon::now(),
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

          $notification = array(
            'message' => 'Vendor registered ',
            'alert-type' => 'success'
        );
        Notification::send($vuser, new VendorRegNotification($request));
        return redirect()->route('vendor.login')->with($notification);

    }
}
