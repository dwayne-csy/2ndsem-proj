<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Show the application homepage with products.
     */
    public function index()
    {
        $product = Product::with(['supplier', 'images'])
            ->latest()
            ->paginate(10);

        return view('home', compact('product'));
    }
}