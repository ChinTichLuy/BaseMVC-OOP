<?php 

namespace App\Controllers\Client;

use App\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $heading1 = 'Trang About';
        $subHeading1 = '---------------------------------------';
        return view('client.about', compact('heading1','subHeading1'));
    }
}