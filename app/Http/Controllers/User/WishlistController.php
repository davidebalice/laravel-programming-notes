<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WishlistController extends Controller
{
    public function Add(Request $request, $product_id){
        if (Auth::check()) {
        $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();
            if (!$exists) {
               Wishlist::insert([
                'user_id' => Auth::id(),
                'product_id' => $product_id,
                'created_at' => Carbon::now(),
               ]);
               return response()->json(['success' => 'Added on your wishlist' ]);
            } else{
                return response()->json(['error' => 'This product has already on your wishlist' ]);
            } 
        }else{
            return response()->json(['error' => 'Please login your account' ]);
        }
    }

    public function Wishlist(){
        return view('frontend.wishlist.wishlist');
    }

    public function GetWishlist(){
        $wishlist = Wishlist::with('product')->where('user_id',Auth::id())->latest()->get();
        $wishQty = wishlist::count(); 
        return response()->json(['wishlist'=> $wishlist, 'wishQty' => $wishQty]);
    }

    public function Remove($id){
        Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success' => 'Product removed' ]);
    }

}
