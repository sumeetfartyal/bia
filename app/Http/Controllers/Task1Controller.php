<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Task1Controller extends Controller
{
    public function index(){
        $data = "Hello Laravel";
        return view('dashboard', compact('data'));
    }
}
