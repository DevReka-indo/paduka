<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DurabilityController extends Controller
{
    public function index()
    {
        return view('durability.index');
    }
}
