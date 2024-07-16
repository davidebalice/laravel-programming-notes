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
        $categories = Category::orderBy('name', 'asc')->get();
        return view('backend.notes.add',compact('categories'));
    }

    public function Store(Request $request){

        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->route('notes')->with($notification); 
        }

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
        $this->Autocomplete();
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

        $categories = Category::orderBy('name', 'asc')->get();
        
        return view('backend.notes.detail',compact('note','texts','categories'));
    }

    public function StoreText(Request $request){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->to('/view/note/' . $request->id)->with($notification);
        }

        $text = Text::where('note_id', $request->id)->orderBy('order', 'desc')->first();
        if($text !== null){
            $new_order=$text->order + 1;
        }
        else{
            $new_order=1;
        }
        
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
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification); 
        }

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
        return redirect()->back()->with('success', 'Order updated');
    }

    public function Down($note_id, $text_id)
    {
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification); 
        }
        
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
        return redirect()->back()->with('success', 'Ordine updated');
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
        $notes = Note::findOrFail($id);
        return view('backend.notes.edit',compact('notes'));
    }

    public function UpdateNote(Request $request){

            if (auth()->user()->isDemo()) {
                $notification = array(
                    'message' => 'Demo mode - crud operations are not allowed',
                    'alert-type' => 'error'
                );
                return redirect()->to('view/note/'.$request->id)->with($notification); 
            }
        
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

            $note_id = $request->id;
            Note::findOrFail($note_id)->update([
            'name' => $request->name,
            'categories' => $categoriesJson,
            ]);

            $notification = array(
            'message' => 'Note updated',
            'alert-type' => 'success'
        );

        return redirect()->to('view/note/'.$note_id)->with($notification); 
    }

    public function UpdateText(Request $request){
            if (auth()->user()->isDemo()) {
                $notification = array(
                    'message' => 'Demo mode - crud operations are not allowed',
                    'alert-type' => 'error'
                );
                return redirect()->to('view/note/'.$request->note_id)->with($notification); 
            }
            
            $note_id = $request->note_id;
            $text_id = $request->id;
            $text = $request->text;
            Text::findOrFail($text_id)->update([
            'text' => $text
            ]);
            $notification = array(
            'message' => 'Text updated',
            'alert-type' => 'success'
        );

        return redirect()->to('view/note/'.$note_id)->with($notification); 
    }

    public function Delete($id){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $notes = Note::findOrFail($id);
        if(($notes->image)&&(Storage::exists($notes->image))) {
            unlink($notes->image);
        }
        
        Note::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Note deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function DeleteText($note_id, $text_id){
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $text = Text::findOrFail($text_id);

        try {
            $text = Text::where('id', $text_id)
                        ->where('note_id', $note_id)
                        ->firstOrFail();
    
            $text->delete();
    
            $notification = array(
                'message' => 'Row deleted',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete text. ' . $e->getMessage()], 500);
        }
       
        return redirect()->back()->with($notification);
    }

    private function Autocomplete(){
        $names = Note::pluck('name')->toArray();
        $names = array_unique($names);
        $jsArray = 'let autocomplete = ' . json_encode($names) . ';';
       
        $filePath = 'autocomplete.js';
        $disk = Storage::disk('public');
    
        if ($disk->exists($filePath)) {
            $disk->delete($filePath);
        }
       
        $disk->put($filePath, $jsArray);
        Storage::disk('public')->put('autocomplete.js', $jsArray);

        $disk->put($filePath, $jsArray);
    }
}