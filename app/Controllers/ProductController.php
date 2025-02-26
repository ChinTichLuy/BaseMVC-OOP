<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Category;
use App\Models\Product;
use Rakit\Validation\Validator;

class ProductController extends Controller
{
    private $product;
    private $category;
    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }
    public function index()
    {

        $data = $this->product->all();

        return view('index', compact('data'));
    }
    public function show($id)
    {
        $product = $this->product->find($id);
        return view('show', compact('product'));
    }
    public function create()
    {
        $categories = $this->category->all();

        return view('create', compact('categories'));
    }
    public function store()
    {
        try {
            $data = $_POST + $_FILES;

            $validator = new Validator();

            $rules = [
                'name' => 'required|max:255',
                'category_id' => 'required',
                'description' => 'nullable|max:255',
                'img_thumbnail' => 'nullable|uploaded_file:0,2048K,png,jpg,jpeg,webp',

            ];

            $errors = $this->validate($validator, $data, $rules);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                return redirect('/products/create');
            }

            if (is_upload('img_thumbnail')) {
                $data['img_thumbnail'] = $this->uploadFile($data['img_thumbnail'], 'products');
            }

            $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');

            $this->product->add($data);

            return redirect('/products');
        } catch (\Throwable $th) {
            debug($th);
        }
    }
    public function edit($id)
    {
        $categories = $this->category->all();

        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        return view('edit', compact('product', 'categories'));
    }
    public function update($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        try {
            $data = $_POST + $_FILES;

            $validator = new Validator();

            $rules = [
                'name' => 'required|max:255',
                'category_id' => 'required',
                'description' => 'nullable|max:255',
                'img_thumbnail' => 'nullable|uploaded_file:0,2048K,png,jpg,jpeg,webp',

            ];

            $errors = $this->validate($validator, $data, $rules);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                return redirect("/products/$id/edit");
            }


            if (is_upload('img_thumbnail')) {
                $data['img_thumbnail'] = $this->uploadFile($data['img_thumbnail'], 'products');
            } else {
                $data['img_thumbnail'] = $product['img_thumbnail'];
            }

            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $this->product->update($id, $data);

            if (
                is_upload('img_thumbnail')
                && $product['img_thumbnail']
                && file_exists($product['img_thumbnail'])
            ) {
                unlink($product['img_thumbnail']);
            }

            return redirect('/products');
        } catch (\Throwable $th) {
            debug($th);
        }
    }
    public function delete($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        try {

            $this->product->delete($id);

            if (
                $product['img_thumbnail']
                && file_exists($product['img_thumbnail'])
            ) {
                unlink($product['img_thumbnail']);
            }

            return redirect('/products');
        } catch (\Throwable $th) {
            debug($th);
        }
    }
}
