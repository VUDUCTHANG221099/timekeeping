<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecycleController extends Controller
{
    public function index(){
        return view('Recycle.index');
    }
}
