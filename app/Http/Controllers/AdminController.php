<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 
use App\Notifications\VendorApproveNotification;
use Illuminate\Support\Facades\Notification;
         
class AdminController extends Controller
{
    public function Dashboard(){
        return view('admin.index');
    }

    public function Login(){
        return view('admin.login');
    }

    public function Destroy(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function Profile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.profile',compact('adminData'));
    } 

    public function Store(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->surname = $request->surname;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Profile updated',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ChangePassword(){
        return view('admin.change_password');
    }

    public function UpdatePassword(Request $request){
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");
    }

    public function InactiveVendor(){
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('vendor.inactive_vendor',compact('inActiveVendor'));
    }

    public function ActiveVendor(){
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('vendor.active_vendor',compact('ActiveVendor'));
    }

    public function InactiveVendorDetails($id){
        $inactiveVendorDetails = User::findOrFail($id);
        return view('vendor.inactive_vendor_details',compact('inactiveVendorDetails'));
    }

    public function ActiveVendorApprove(Request $request){
        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'active',
        ]);

        $notification = array(
            'message' => 'Vendor active',
            'alert-type' => 'success'
        );

        $user = User::where('role','vendor')->get();
        Notification::send($user, new VendorApproveNotification($request));
        return redirect()->route('vendor.active')->with($notification);
    }

    public function ActiveVendorDetails($id){
        $activeVendorDetails = User::findOrFail($id);
        return view('vendor.active_vendor_details',compact('activeVendorDetails'));
    }

    public function InActiveVendorApprove(Request $request){
        $verdor_id = $request->id;
        $user = User::findOrFail($verdor_id)->update([
            'status' => 'inactive',
        ]);
        $notification = array(
            'message' => 'Vendor inactive ',
            'alert-type' => 'success'
        );
        return redirect()->route('vendor.inactive')->with($notification);
    }

    public function Admin(){
        $alladminuser = User::where('role','admin')->latest()->get();
        return view('backend.admin.admin',compact('alladminuser'));
    }

    public function AddAdmin(){
        $roles = Role::all();
        return view('backend.admin.add',compact('roles'));
    }

    public function AdminUserStore(Request $request){
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin created',
            'alert-type' => 'success'
        );
        return redirect()->route('admins')->with($notification);
    } 

    public function EditAdminRole($id){
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('backend.admin.edit',compact('user','roles'));
    }

    public function AdminUserUpdate(Request $request,$id){
        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->surname = $request->surname;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address; 
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

         $notification = array(
            'message' => 'Admin updated',
            'alert-type' => 'success'
        );
        return redirect()->route('admins')->with($notification);
    }

    public function DeleteAdminRole($id){

        $user = User::findOrFail($id);
        if (!is_null($user)) {
            $user->delete();
        }
 
         $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}