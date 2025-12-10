<?php


namespace App\Http\Controllers;

use App\Models\Product;

class MenuController extends Controller
{
    public function index() {
        $products = Product::all(); // o con filtros
        return view('menu.index', compact('products'));
    }
}

