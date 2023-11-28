<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeOption;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Gallery;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User; 

class IndexController extends Controller
{
    public function Index(){
        $options = HomeOption::findOrFail(1);
        $hot_deals = Product::where('hot_deals',1)->where('discount_price','!=',NULL)->where('active','1')->orderBy('id','DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer',1)->where('active','1')->orderBy('id','DESC')->limit(3)->get();
        $new = Product::where('status',1)->where('active','1')->orderBy('id','DESC')->limit(3)->get();
        $special_deals = Product::where('special_deals',1)->where('active','1')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.index',compact('options','hot_deals','special_offer','new','special_deals'));
    }
    
    public function Details($id,$slug){
        $product = Product::where('active','1')->findOrFail($id);

        $color = $product->color;
        $color = explode(',', $color);

        $size = $product->size;
        $size = explode(',', $size);

        $multiImage = Gallery::where('product_id',$id)->get();

        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('active','1')->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.details',compact('product','color','size','multiImage','relatedProduct'));
    }

    public function VendorDetails($id){
        $vendor = User::findOrFail($id);
        $vproduct = Product::where('vendor_id',$id)->where('active','1')->get();
        return view('frontend.vendor.details',compact('vendor','vproduct'));
    }

    public function Vendors(){
        $vendors = User::where('status','active')->where('role','vendor')->orderBy('id','DESC')->get();
        return view('frontend.vendor.vendors',compact('vendors'));
    }

    public function Category(Request $request,$id,$slug){
        $products = Product::where('status',1)->where('active','1')->where('category_id',$id)->orderBy('id','DESC')->paginate(20);
        $categories = Category::where('active','1')->orderBy('name','ASC')->get();
        $breadcat = Category::where('active','1')->where('id',$id)->first();
        $newProduct = Product::where('active','1')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.category',compact('products','categories','breadcat','newProduct'));
    }

    public function Subcategory(Request $request,$id,$slug){
        $products = Product::where('active','1')->where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->paginate(20);
        $categories = Category::where('active','1')->orderBy('name','ASC')->get();
        $breadsubcat = SubCategory::where('active','1')->where('id',$id)->first();
        $newProduct = Product::where('active','1')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.subcategory',compact('products','categories','breadsubcat','newProduct'));
    }

    public function Ajax($id){
        $product = Product::with('category','brand')->findOrFail($id);
        $color = $product->color;
        $color = explode(',', $color);

        $size = $product->size;
        $size = explode(',', $size);

        return response()->json(array(
         'product' => $product,
         'color' => $color,
         'size' => $size,
        ));
    } 

    public function Search(Request $request){
        $request->validate(['search' => "required"]);
        $item = $request->search;
        $categories = Category::where('active','1')->orderBy('name','ASC')->get();
        $products = Product::where('active','1')->where('name','LIKE',"%$item%")->paginate(20);
        $newProduct = Product::where('active','1')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.search',compact('products','item','categories','newProduct'));
    }

    public function SearchProduct(Request $request){
        $request->validate(['search' => "required"]);
        $item = $request->search;
        $products = Product::where('active','1')->where('name','LIKE',"%$item%")->select('name','slug','image','price','id')->limit(6)->get();
        return view('frontend.product.search_product',compact('products'));
      }
}