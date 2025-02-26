<?php

namespace App\Controllers\Client;

use App\Controller;
use App\Models\Product;

class DetailController extends Controller
{

    private Product $product;
    public function __construct()
    {
        $this->product = new Product();
    }   

    public function detail($id){
        $product = $this->product->find($id);

        if(empty($product)){
            redirect404();
        }

        $heading1 = 'Chi tiet San Pham';
        $subHeading1 = '---------------------------------------';
        return view('client.detail', compact('product','heading1','subHeading1' ));
    }
}
