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

    public function UserOrderPage(){
        $id = Auth::user()->id;
        $orders = Order::where('user_id',$id)->orderBy('id','DESC')->get();
        return view('frontend.user.orders',compact('orders'));
    }
 
    public function UserOrderDetails($order_id){
        $order = Order::with('division','district','state','user')->where('id',$order_id)->where('user_id',Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('frontend.order.order_details',compact('order','orderItem'));
    }

    public function UserOrderInvoice($order_id){
        $order = Order::with('division','district','state','user')->where('id',$order_id)->where('user_id',Auth::id())->first();
        $orderItem = OrderItem::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

        $pdf = Pdf::loadView('frontend.order.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
                'tempDir' => public_path(),
                'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');
    }

    public function ReturnOrder(Request $request,$order_id){

        Order::findOrFail($order_id)->update([
            'return_date' => Carbon::now()->format('d F Y'),
            'return_reason' => $request->return_reason,
            'return_order' => 1, 
        ]);

        $notification = array(
            'message' => 'Return Request Send Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.order.page')->with($notification); 
    }

    public function ReturnOrderPage(){
        $orders = Order::where('user_id',Auth::id())->where('return_reason','!=',NULL)->orderBy('id','DESC')->get();
        return view('frontend.order.return_order_view',compact('orders'));
    }

    public function UserTrackOrder(){
        return view('frontend.user.track_orders');
    } 

    public function OrderTracking(Request $request){
        $invoice = $request->code;
        $track = Order::where('invoice_no',$invoice)->first();

        if ($track) {
           return view('frontend.tracking.track_order',compact('track'));

        } else{

                $notification = array(
                'message' => 'Invoice Code Is Invalid',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification); 
        }
    }
}