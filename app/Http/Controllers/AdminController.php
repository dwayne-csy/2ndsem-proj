<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Ensure this method is returning a valid view or redirect
        return view('admin.dashboard'); // Make sure the dashboard view exists
    }
}

