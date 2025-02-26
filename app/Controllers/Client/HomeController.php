<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    private Product $product;
    public function __construct()
    {
       $this->product = new Product;
    }
    public function index(){
        $heading1 = 'Trang Home';
        $subHeading1 = '---------------------------------------';
        $data = $this->product->findAll();

        return view('client.home', compact('heading1','subHeading1', 'data'));

    }
}
