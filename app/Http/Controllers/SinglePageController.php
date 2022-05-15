<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinglePageController extends Controller
{
    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("landing");
    }
}
