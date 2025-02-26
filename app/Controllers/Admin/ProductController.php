<?php

namespace App\Controllers\Admin;

use App\Controller;
use App\Models\Category;
use App\Models\Product;
use Rakit\Validation\Validator;

class ProductController extends Controller
{
    private Product $product;
    private Category $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index()
    {
        $data = $this->product->paginate($_GET['page'] ?? 1, $_GET['limit'] ?? 5);
        $data['title'] = 'Danh Sach product';
        return view('admin.products.index', $data);
    }
    public function create()
    {
        $title = 'Them Moi San pham';

        $categories = $this->category->getCategoryOnlyActive();

        return view('admin.products.create', compact('title', 'categories'));
    }
    public function store()
    {
        try {
            $data = $_POST + $_FILES;

            $validator = new Validator;

            $errors = $this->validate(
                $validator,
                $data,
                [
                    'name' =>           ['required', 'max:50'],
                    'category_id' =>    ['required'],
                    'overview' =>       ['required', 'max:255'],
                    'content' =>        ['required', 'max:60000'],
                    'price' =>          ['required', 'numeric'],
                    'price_sale' =>     ['required', 'numeric'],
                    'img_thumbnail' => 'nullable|uploaded_file:0,2048K,png,jpg,jpeg,webp',
                    'is_active' =>      [$validator('in', [0, 1])],
                    'is_sale' =>        [$validator('in', [0, 1])],
                    'is_show_home' =>   [$validator('in', [0, 1])],
                ]
            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'Them Moi that bai1';
                $_SESSION['data'] = $_POST;
                $_SESSION['errors'] = $errors;

                redirect('/admin/products/create');
            } else {
                $_SESSION['data'] = null;
            }

            if (is_upload('img_thumbnail')) {
                $data['img_thumbnail'] = $this->uploadFile($data['img_thumbnail'], 'products');
            } else {
                $data['img_thumbnail'] = null;
            }

            $data['is_active'] = $data['is_active'] ?? 0;
            $data['is_sale'] = $data['is_sale'] ?? 0;
            $data['is_show_home'] = $data['is_show_home'] ?? 0;
            $data['slug'] = slug($data['name']);


            $this->product->insert($data);

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Them Moi thanh Cong';

            redirect('/admin/products');
        } catch (\Throwable $th) {
            $this->logError($th->__tostring());

            $SESSION['status'] = false;
            $SESSION['msg'] = 'Them Moi That Bai2';
            $SESSION['data'] = $_POST;

            redirect('/admin/products/create');
        }
    }
    public function show($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        $title = 'Chi tiet san pham';

        return view('admin.products.show', compact('product', 'title'));
    }
    public function edit($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        $title = 'Cap nhat san pham';

        $categories = $this->category->getCategoryOnlyActive();

        return view('admin.products.edit', compact('product', 'title', 'categories'));
    }
    public function update($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        try {
            $data = $_POST + $_FILES;

            $validator = new Validator;

            $errors = $this->validate(
                $validator,
                $data,
                [
                    'name' =>           ['required', 'max:50'],
                    'category_id' =>    ['required'],
                    'overview' =>       ['required', 'max:255'],
                    'content' =>        ['required', 'max:60000'],
                    'price' =>          ['required', 'numeric'],
                    'price_sale' =>     ['required', 'numeric'],
                    'img_thumbnail' => 'nullable|uploaded_file:0,2048K,png,jpg,jpeg,webp',
                    'is_active' =>      [$validator('in', [0, 1])],
                    'is_sale' =>        [$validator('in', [0, 1])],
                    'is_show_home' =>   [$validator('in', [0, 1])],
                ]
            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'Cap nhat that bai1';
                $_SESSION['data'] = $_POST;
                $_SESSION['errors'] = $errors;

                redirect('/admin/products/edit/' . $id);
            } else {
                $_SESSION['data'] = null;
            }

            if (is_upload('img_thumbnail')) {
                $data['img_thumbnail'] = $this->uploadFile($data['img_thumbnail'], 'products');
            } else {
                $data['img_thumbnail'] = $product['p_img_thumbnail'];
            }

            $data['is_active'] = $data['is_active'] ?? 0;
            $data['is_sale'] = $data['is_sale'] ?? 0;
            $data['is_show_home'] = $data['is_show_home'] ?? 0;
            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->product->update($id, $data);

            if (
                $data['img_thumbnail'] != $product['p_img_thumbnail']
                && $product['p_img_thumbnail']
                && file_exists($product['p_img_thumbnail'])
            ) {
                unlink($product['p_img_thumbnail']);
            }

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Cap nhat Thanh Cong';

            redirect('/admin/products');
        } catch (\Throwable $th) {
            $this->logError($th->__tostring());

            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Cap nhat that bai2';
            $_SESSION['data'] = $_POST;

            redirect('/admin/products/edit/' . $id);
        }
    }
    public function delete($id)
    {
        $product = $this->product->find($id);

        if (empty($product)) {
            redirect404();
        }

        $this->product->delete($id);

        if ($product['p_img_thumbnail'] && file_exists($product['p_img_thumbnail'])) {
            unlink($product['p_img_thumbnail']);
        }

        $_SESSION['status'] = true;
        $_SESSION['msg'] = 'Xoa Thanh Cong';

        redirect('/admin/products');
    }
}
