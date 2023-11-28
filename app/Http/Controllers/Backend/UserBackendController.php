<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Intervention\Image\Facades\Image As Image;
use Illuminate\Support\Facades\Storage;
class UserBackendController extends Controller
{
    public function Users(){
        $users = User::where('role','user')->paginate(20);
        return view('backend.user.users',compact('users'));
    }
    
    public function Edit($id){
        $user = User::findOrFail($id);
        return view('backend.user.edit',compact('user'));
    }

    public function Update(Request $request){

        $id = $request->id;
        $image = $request->old_image;

        if ($request->file('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(300,null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('upload/user/'.$name_gen);

            if (file_exists($image)) {
                unlink($image);
                $image = $name_gen;
            }
        }

        User::findOrFail($id)->update([
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'photo' => $image, 
        ]);

        $notification = array(
                'message' => 'User updated',
                'alert-type' => 'success'
            );

            return redirect()->route('users')->with($notification); 


       $notification = array(
            'message' => 'User updated',
            'alert-type' => 'success'
        );

        return redirect()->route('users')->with($notification); 

    }
    

    public function Delete($id){

        $user = User::findOrFail($id);
        $img = $user->image;
        if($img){
            unlink($img); 
        }

        User::findOrFail($id)->delete();

        $notification = array(
            'message' => 'User deleted',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    public function Active(Request $request, $id){
        try {
            $user = User::findOrFail($id);
            $user->update([
                'active' => $request->active,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }  
}