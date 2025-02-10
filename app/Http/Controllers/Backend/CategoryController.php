<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Intervention\Image\Facades\Image As Image;

class CategoryController extends Controller
{
    // Metodo per ottenere tutte le categorie ordinate per nome
    public function Categories(){
        $categories = Category::orderBy('name')->get();
        return view('backend.category.categories',compact('categories'));
    }

    // Metodo per visualizzare la pagina di aggiunta di una nuova categoria
    public function Add(){
        return view('backend.category.add');
    }

    // Metodo per salvare una nuova categoria
    public function Store(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->route('categories')->with($notification); 
        }

        $image = $request->file('image');
        $save_url = null;
        if($image)
        {
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(600,null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('upload/category/'.$name_gen);

            $save_url = 'upload/category/'.$name_gen;
        }
        Category::insert([
            'name' => $request->name,
            'slug' => strtolower(str_replace(' ', '-',$request->name)),
            'image' => $save_url,
        ]);

       $notification = array(
            'message' => 'Category created',
            'alert-type' => 'success'
        );

        return redirect()->route('categories')->with($notification); 
    }

    // Metodo per visualizzare la pagina di modifica di una categoria
    public function Edit($id){
        $category = Category::findOrFail($id);
        return view('backend.category.edit',compact('category'));
    }

    // Metodo per aggiornare una categoria esistente
    public function Update(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->route('categories')->with($notification); 
        }

        $cat_id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('image')) {

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            Image::make($image)->resize(300,null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('upload/category/'.$name_gen);

            $save_url = 'upload/category/'.$name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }
        
            Category::findOrFail($cat_id)->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-',$request->name)),
                'image' => $save_url, 
            ]);

            $notification = array(
                'message' => 'Category updated',
                'alert-type' => 'success'
            );

            return redirect()->route('categories')->with($notification); 

        } else {

            Category::findOrFail($cat_id)->update([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-',$request->name)), 
            ]);

            $notification = array(
                'message' => 'Category updated',
                'alert-type' => 'success'
            );

            return redirect()->route('categories')->with($notification); 
        }
    }

    // Metodo per eliminare una categoria
    public function Delete($id){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification); 
        }

        $category = Category::findOrFail($id);
        $img = $category->image;
        if($img){
            unlink($img); 
        }

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category deleted',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    // Metodo per attivare/disattivare una categoria
    public function Active(Request $request, $id){
        try {
            $category = Category::findOrFail($id);
            $category->update([
                'active' => $request->active,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Metodo per ordinare le categorie
    public function Sort($action, $id) {
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            $categories = Category::orderby('position','ASC')->paginate(15);
            return view('backend.category.categories',compact('categories'));
        }
        
        $category = Category::findOrFail($id);
        $order = $category->position;
        
        if($action == "up")
        {
            $order--;
            $newOrder = $order + 1;
        }
        elseif($action == "down")
        {
            $order++;
            $newOrder = $order - 1;
        }
        if($order <= 1)
        {
            $order = 1;
        }
        if($newOrder <= 1)
        {
            $newOrder = 1;
        }

        Category::where('position', $order)
        ->chunkById(100, function ($update) use ($newOrder) {
            foreach ($update as $category) {
                Category::where('id', $category->id)
                    ->update(['position' => $newOrder]);
            }
        });
        
        Category::where('id', $id)
        ->update(['position' => $order]);

        $i = 0;
        Category::orderby('position','ASC')
        ->chunkById(100, function ($update) use ($i) {
            foreach ($update as $category) {
                $i++;
                Category::where('id', $category->id)->update(['position' => $i]);
            }
        });	

        $categories = Category::orderby('position','ASC')->paginate(15);
        return view('backend.category.categories',compact('categories'));
    }
}