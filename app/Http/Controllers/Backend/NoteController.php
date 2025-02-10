<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Note;
use App\Models\Text;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    // Metodo per ottenere le note filtrate per categoria, sottocategoria e testo di ricerca
    public function Notes(Request $request){
        $category = $request->input('id');
        $subcategory = $request->input('subcategory_id');
        $searchText = $request->input('search');
        $notes = Note::latest();
       
        $subcategories = null;
        
        $subcategories = Subcategory::where('category_id', $category)->get();
        
        if ($category) {
            $notes->where('categories', 'LIKE', '%"' . $category . '"%');
        }
        if ($subcategory) {
            $notes->where('subcategory_id',$subcategory);
        }

        if ($searchText) {
            $notes->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            })->orWhereHas('texts', function ($query) use ($searchText) {
                $query->where('text', 'LIKE', '%' . $searchText . '%');
            });
        }

        //imposta paginazione a 20 risultati per pagina, ed appende la categoria se presente
        $notes = $notes->paginate(20)->appends([
            'id' => $category,
        ]);

        // Assegna le categorie alle note
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
        $allCategories = Category::orderBy('name', 'asc')->get();
        return view('backend.notes.notes',compact('notes','allCategories','subcategories'));
    }

    // Metodo per visualizzare la pagina di aggiunta di una nuova nota
    public function Add(){
        $categories = Category::orderBy('name', 'asc')->get();
        $firstCategory = Category::orderBy('name')->first();
        $subcategories = Subcategory::where('category_id', $firstCategory['id'])->orderBy('name', 'asc')->get();

        return view('backend.notes.add',compact('categories','subcategories'));
    }

    // Metodo per salvare una nuova nota
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
            'subcategory_id' => $request->subcategory_id,
            'created_at' => now(),
        ]);

        $notification = array(
            'message' => 'Note created',
            'alert-type' => 'success'
        );
        $this->Autocomplete();
        return redirect()->route('notes')->with($notification); 
    }

    // Metodo per visualizzare i dettagli di una nota
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
                            $subcategories = Subcategory::where('category_id', $categoryData['id'])->get();
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
        
        return view('backend.notes.detail',compact('note','texts','categories','subcategories'));
    }

    // Metodo per salvare un nuovo testo associato a una nota
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

        $formattedText = $request->text;
        
        if($request->type=="text"){
            $formattedText = nl2br(e($request->text));
        }
        
        $note_id = Text::insert([
            'note_id' => $request->id,
            'text' => $formattedText,
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

    // Metodo per spostare un testo verso l'alto nell'ordine
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

    // Metodo per spostare un testo verso il basso nell'ordine
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

    // Metodo per riordinare i testi di una nota
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

    // Metodo per visualizzare la pagina di modifica di una nota
    public function Edit($id){
        $notes = Note::findOrFail($id);
        return view('backend.notes.edit',compact('notes'));
    }

    // Metodo per aggiornare una nota esistente
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
            'subcategory_id' => $request->subcategory_id,
            ]);

            $notification = array(
            'message' => 'Note updated',
            'alert-type' => 'success'
        );

        return redirect()->to('view/note/'.$note_id)->with($notification); 
    }

    // Metodo per aggiornare un testo esistente
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

    // Metodo per salvare il codice di un testo
    public function SaveCode(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'id_note' => 'required|integer',
            'code' => 'required|string',
        ]);
       
        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
           return response()->json(['success' => true, 'message' => 'Demo mode - crud operations are not allowed']);
        }

        $request->validate([
            'id' => 'required|integer',
            'code' => 'required|string',
        ]);

        $id = $request->input('id');
        $code = $request->input('code');

        $note = Text::find($id);

        if ($note) {
            $note->text = $code;
            $note->save();

            return response()->json(['success' => true, 'message' => 'Code saved!']);
        }

        return response()->json(['success' => false, 'message' => 'Note not found.'], 404);
    }

    // Metodo per eliminare una nota
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

    // Metodo per eliminare un testo associato a una nota
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

    // Metodo per aggiornare l'autocompletamento dei nomi delle note
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

    // Metodo per salvare la lingua dell'editor di testo
    public function SaveLanguage(Request $request){
        $id = $request->input('id');
        $language = $request->input('language');

        if (auth()->user()->isDemo()) {
            $notification = array(
                'message' => 'Demo mode - crud operations are not allowed',
                'alert-type' => 'error'
            );
            return response()->json(['message' => $notification]);
        }

        Text::findOrFail($id)->update([
            'editor' => $language
        ]);

        return response()->json(['message' => 'Language saved!']);
    }
}