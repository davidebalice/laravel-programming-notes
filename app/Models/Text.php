<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function textnote(){
        return $this->belongsTo(Note::class,'note_id','id');
    }
}
