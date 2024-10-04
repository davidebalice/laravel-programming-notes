<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
 
class SubcategoryController extends Controller
{
    public function Subcategories($category_id = null){
        $categories = Category::orderBy('name', 'ASC')->get();

        if ($category_id) {
            $categoryId = $category_id;
            $category = Category::find($categoryId);

            if ($category) {
                $subcategories = Subcategory::where('category_id', $categoryId)->orderBy('name')->get();
            } else {
                $firstCategory = Category::orderBy('name')->first();
                if ($firstCategory) {
                    $subcategories = Subcategory::where('category_id', $firstCategory->id)->orderBy('name')->get();
                } else {
                    $subcategories = collect();
                }
            }
        } else {
            $firstCategory = Category::orderBy('name')->first();
            if ($firstCategory) {
                $subcategories = Subcategory::where('category_id', $firstCategory->id)->orderBy('name')->get();
            } else {
                $subcategories = collect();
            }
            $category=$firstCategory;
        }
    
        return view('backend.subcategory.subcategories', compact('subcategories','category','categories'));
    }
    

    public function Add(){
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('backend.subcategory.add', compact('categories'));
    }
    
    public function Store(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->route('subcategories', $request->category_id)->with($notification);
        }

        Subcategory::insert([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-',$request->name)),
            'category_id' => $request->category_id,
        ]);

       $notification = array(
            'message' => 'Subcategory created',
            'alert-type' => 'success'
        );

        return redirect()->route('subcategories', $request->category_id)->with($notification);
    }

    public function Edit($id){
        $subcategory = Subcategory::findOrFail($id);
        $categories = Category::all();
        return view('backend.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function Update(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->route('subcategories', $request->category_id)->with($notification);
        }

        $subcat_id = $request->id;
   
        Subcategory::findOrFail($subcat_id)->update([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-',$request->name)),
        ]);

        $notification = array(
            'message' => 'Subcategory updated',
            'alert-type' => 'success'
        );

        return redirect()->route('subcategories', $request->category_id)->with($notification);
    }

    public function Delete($id){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification); 
        }

        $subcategory = Subcategory::findOrFail($id);
        $img = $subcategory->image;
        if($img){
            unlink($img);
        }

        Subcategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Subcategory deleted',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    public function Active(Request $request, $id){
        try {
            $subcategory = Subcategory::findOrFail($id);
            $subcategory->update([
                'active' => $request->active,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function Sort($action,$id) {
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            $subcategories = Subcategory::orderby('position','ASC')->paginate(15);
            return view('backend.subcategory.subcategories',compact('subcategories'));
        }
        
        $subcategory = Subcategory::findOrFail($id);
        $order = $subcategory->position;
        
        if($action=="up")
		{
            $order--;
            $newOrder=$order+1;
        }
        elseif($action=="down")
		{
            $order++;
            $newOrder=$order-1;
        }
        if($order<=1)
        {
            $order=1;
        }
        if($newOrder<=1)
        {
            $newOrder=1;
        }
        
        //->whereNull('deleted_at')

        Subcategory::where('position', $order)
        ->chunkById(100, function ($update) use ($newOrder) {
            foreach ($update as $subcategory) {
                Subcategory::where('id', $subcategory->id)
                    ->update(['position' => $newOrder]);
            }
        });
        
        Subcategory::where('id', $id)
        ->update(['position' => $order]);

        $i=0;
        Subcategory::orderby('position','ASC')
        ->chunkById(100, function ($update) use ($i) {
            foreach ($update as $subcategory) {
                $i++;
                Subcategory::where('id', $subcategory->id)->update(['position' => $i]);
            }
        });

        $subcategories = Subcategory::orderby('position','ASC')->paginate(30);
        return view('backend.subcategory.categories',compact('categories'));
    }

    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }
}