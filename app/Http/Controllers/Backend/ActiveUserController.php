<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ActiveUserController extends Controller
{
    // Metodo per ottenere tutti gli utenti con ruolo 'user'
    public function AllUser(){
        $users = User::where('role','user')->latest()->get();
        return view('backend.user.user_all_data',compact('users'));
    }

    // Metodo per ottenere tutti i venditori con ruolo 'vendor'
    public function AllVendor(){
        $vendors = User::where('role','vendor')->latest()->get();
        return view('backend.user.vendor_all_data',compact('vendors'));
    }
}