<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Note;
use App\Models\Text;
use App\Models\User;
use Carbon\Carbon;
use Intervention\Image\Facades\Image As Image;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function Notes(Request $request){
        $category = $request->input('id');
        $searchText = $request->input('search');
      
        $notes = Note::latest();

        if ($category) {
            $notes->where('categories', 'LIKE', '%"' . $category . '"%');
        }

        if ($searchText) {
            $notes->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            })->orWhereHas('texts', function ($query) use ($searchText) {
                $query->where('text', 'LIKE', '%' . $searchText . '%');
            });
        }

        $notes = $notes->paginate(20);

        foreach ($notes as $note) {
            $category1 = [];
            $category2 = [];
            $category3 = [];
    
            $categories = json_decode($note->categories, true);
    
            if ($categories) {
                $categoryKeys = array_keys($categories);
    
                foreach ($categoryKeys as $index => $key) {
                    $categoryData = Category::find($categories[$key]);
    
                    if ($categoryData) {
                        switch ($index) {
                            case 0:
                                $category1[] = $categoryData;
                                break;
                            case 1:
                                $category2[] = $categoryData;
                                break;
                            case 2:
                                $category3[] = $categoryData;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
    
            $note->category1 = $category1;
            $note->category2 = $category2;
            $note->category3 = $category3;
        }
        
        return view('backend.notes.notes',compact('notes'));
    }

    public function Add(){
        $categories = Category::latest()->get();
        return view('backend.notes.add',compact('categories'));
    }

    public function Store(Request $request){
        $categories = [];

        if ($request->has('category1')) {
            $categories['category1'] = $request->category1;
        }
        if ($request->has('category2')) {
            $categories['category2'] = $request->category2;
        }
        if ($request->has('category3')) {
            $categories['category3'] = $request->category3;
        }

        $categoriesJson = json_encode($categories);

        $note_id = Note::insertGetId([
            'categories' => $categoriesJson,
            'name' => $request->name,
            'created_at' => now(),
        ]);

        $notification = array(
            'message' => 'Note created',
            'alert-type' => 'success'
        );
        return redirect()->route('notes')->with($notification); 
    }

    public function Detail($id){
        $note = Note::findOrFail($id);
        $texts = Text::where('note_id', $id)->orderBy('order', 'asc')->get();
        
        $category1 = [];
        $category2 = [];
        $category3 = [];

        $categories = json_decode($note->categories, true);

        if ($categories) {
            $categoryKeys = array_keys($categories);

            foreach ($categoryKeys as $index => $key) {
                $categoryData = Category::find($categories[$key]);

                if ($categoryData) {
                    switch ($index) {
                        case 0:
                            $category1[] = $categoryData;
                            break;
                        case 1:
                            $category2[] = $categoryData;
                            break;
                        case 2:
                            $category3[] = $categoryData;
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        $note->category1 = $category1;
        $note->category2 = $category2;
        $note->category3 = $category3;
        
        return view('backend.notes.detail',compact('note','texts'));
    }

    public function StoreText(Request $request){
        $text = Text::where('note_id', $request->id)->orderBy('order', 'desc')->first();
        $new_order=$text->order + 1;
        
        $note_id = Text::insert([
            'note_id' => $request->id,
            'text' => $request->text,
            'type' => $request->type,
            'order' => $new_order,
            'created_at' => now(),
        ]);

        $notification = array(
            'message' => 'Text created',
            'alert-type' => 'success'
        );
        $this->reorderTexts($request->id);
        return redirect()->to('/view/note/' . $request->id)->with($notification);
    }


    public function Up($note_id, $text_id)
    {
        $contentToMoveUp = Text::where('note_id', $note_id)
            ->where('id', $text_id)
            ->first();
    
        if ($contentToMoveUp) {
            $currentOrder = $contentToMoveUp->order;
            $contentAbove = Text::where('note_id', $note_id)
                ->where('order', '<', $currentOrder)
                ->orderBy('order', 'desc')
                ->first();
    
            if ($contentAbove) {
                $contentToMoveUp->update(['order' => $contentAbove->order]);
                $contentAbove->update(['order' => $currentOrder]);
            }
        }
        $this->reorderTexts($note_id);
        return redirect()->back()->with('success', 'Ordine aggiornato correttamente');
    }

    public function Down($note_id, $text_id)
    {
        $contentToMoveDown = Text::where('note_id', $note_id)
            ->where('id', $text_id)
            ->first();

        if ($contentToMoveDown) {
            $currentOrder = $contentToMoveDown->order;
            $contentBelow = Text::where('note_id', $note_id)
                ->where('order', '>', $currentOrder)
                ->orderBy('order', 'asc')
                ->first();

            if ($contentBelow) {
                $contentToMoveDown->update(['order' => $contentBelow->order]);
                $contentBelow->update(['order' => $currentOrder]);
            }
        }
        $this->reorderTexts($note_id);
        return redirect()->back()->with('success', 'Ordine aggiornato correttamente');
    }

    private function reorderTexts($note_id)
    {
        $texts = Text::where('note_id', $note_id)
            ->orderBy('order')
            ->get();

        $order = 1;

        foreach ($texts as $text) {
            $text->update(['order' => $order]);
            $order++;
        }
    }

    public function Edit($id){
        $products = Product::findOrFail($id);
        $gallery = Gallery::where('product_id',$id)->get();
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::where('category_id',$products->category_id)->latest()->get(); 
        return view('backend.product.edit',compact('brands','categories','activeVendor','products','subcategory','gallery'));
    }

    public function Update(Request $request){
        $product_id = $request->id;
        Product::findOrFail($product_id)->update([
        'brand_id' => $request->brand_id,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'name' => $request->name,
        'slug' => strtolower(str_replace(' ','-',$request->name)),

        'code' => $request->code,
        'qty' => $request->qty,
        'tags' => $request->tags,
        'size' => $request->size,
        'color' => $request->color,

        'price' => $request->price,
        'discount_price' => $request->discount_price,
        'short_desc' => $request->short_desc,
        'long_desc' => $request->long_desc, 

        'hot_deals' => $request->hot_deals,
        'featured' => $request->featured,
        'special_offer' => $request->special_offer,
        'special_deals' => $request->special_deals, 

        'vendor_id' => $request->vendor_id,
        'status' => 1,
        'created_at' => Carbon::now(), 
        ]);

        $notification = array(
        'message' => 'Product updated',
        'alert-type' => 'success'
    );

    return redirect()->route('products')->with($notification); 

    }

    public function Gallery($id){
        $gallery = Gallery::where('product_id',$id)->get();
        $products = Product::findOrFail($id);
        return view('backend.product.gallery',compact('products','gallery'));
    }

    public function UpdateImage(Request $request){

        $pro_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

        Image::make($image)->resize(800,null, function ($constraint) {
            $constraint->aspectRatio();
        })->save('upload/product/'.$name_gen);    

        $save_url = 'upload/product/'.$name_gen;

         if (file_exists($oldImage)) {
           unlink($oldImage);
        }

        Product::findOrFail($pro_id)->update([

            'image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Product updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }

    public function UpdateGallery(Request $request){

        $imgs = $request->multi_img;

        foreach($imgs as $id => $img ){
            $imgDel = Gallery::findOrFail($id);
           
            if (Storage::exists($imgDel->title)) {
                unlink($imgDel->title);
            }
            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
           
            Image::make($img)->resize(800,null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('upload/product/gallery/'.$make_name);    

            $uploadPath = 'upload/product/gallery/'.$make_name;

            Gallery::where('id',$id)->update([
                'title' => $uploadPath,
                'updated_at' => Carbon::now(),

            ]); 
        }

         $notification = array(
            'message' => 'Product gallery updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    } 

    public function GalleryDelete($id){
        $oldImg = Gallery::findOrFail($id);

        if (Storage::exists($oldImg->title)) {
            unlink($oldImg->title);
        }

        Gallery::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Photo deleted',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } 

    public function StoreGallery(Request $request){
        $product_id = $request->id;
        $images = $request->file('multi_img');
        if($images){
            foreach($images as $img){
                $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();

                Image::make($img)->resize(800,null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save('upload/product/gallery/'.$make_name);

                $uploadPath = 'upload/product/gallery/'.$make_name;

                Gallery::insert([
                    'product_id' => $product_id,
                    'title' => $uploadPath,
                    'created_at' => Carbon::now(), 
                ]); 
            }
        }
        $notification = array(
            'message' => 'Photo inserted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }

    public function Delete($id){
        $product = Product::findOrFail($id);
        if(($product->image)&&(Storage::exists($product->image))) {
            unlink($product->image);
        }
        
        Product::findOrFail($id)->delete();

        $imgs = Gallery::where('product_id',$id)->get();
        foreach($imgs as $img){
            if(($img->title)&&(Storage::exists($img->title))) {
                unlink($img->title);
            }
            Gallery::where('product_id',$id)->delete();
        }
        $notification = array(
            'message' => 'Product deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function Active(Request $request, $id){
        try {
            $product = Product::findOrFail($id);
            $product->update([
                'status' => $request->active,
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}