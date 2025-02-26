<?php

namespace App\Controllers\Admin;

use App\Controller;
use App\Models\Category;
use Rakit\Validation\Validator;

class CategoryController extends Controller
{
    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        $title = 'Category';

        $data = $this->category->findAll();

        return view(
            'admin.categories.index',
            compact('title', 'data')
        );
    }

    public function create()
    {
        $title = 'Them moi danh muc';

        return view('admin.categories.create', compact('title'));
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
                    'name' => [
                        'required',
                        'max:30',
                        function ($value) {
                            $flag = (new Category)->checkExistsNameForCreate($value);

                            if ($flag) {
                                return ": Danh muc nay da ton tai";
                            }
                        }

                    ],
                    'img' => 'nullable|uploaded_file:0,2048k,png,jpeg,jpg,webp',
                    'is_active' => [$validator('in', [0, 1])]
                ],

            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'Tao moi Danh muc THAT BAI1';
                $_SESSION['data'] = $_POST;
                $_SESSION['errors'] = $errors;

                redirect('/admin/categories/create');
            } else {

                $_SESSION['data'] = null;
            }

            if (is_upload('img')) {
                $data['img'] = $this->uploadFile($data['img'], 'categories');
            } else {
                $data['img'] = null;
            }

            $data['is_active'] = $data['is_active'] ?? 0;

            $this->category->insert($data);

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Tao moi Danh Muc Thanh Cong!';

            redirect('/admin/categories');
        } catch (\Throwable $th) {
            $this->logError($th->__tostring());

            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Tao moi Danh muc THAT BAI2';
            $_SESSION['data'] = $_POST;

            redirect('/admin/categories/create');
        }
    }

    public function show($id)
    {
        $category = $this->category->find($id);

        if (empty($category)) {
            redirect404();
        }

        $title = 'Chi tiet danh muc';

        return view('admin.categories.show', compact('category', 'title'));
    }

    public function edit($id)
    {
        $category = $this->category->find($id);
        if (empty($category)) {
            redirect404();
        }

        $title = 'cap nhat danh muc';
        return view('admin.categories.edit', compact('category', 'title'));
    }

    public function update($id)
    {

        $category = $this->category->find($id);

        if (empty($category)) {
            redirect404();
        }

        try {
            $data = $_POST + $_FILES;

            $validator = new Validator;

            $errors = $this->validate(
                $validator,
                $data,
                [
                    'name' => [
                        'required',
                        'max:50',
                        function ($value) use ($id) {
                            $flag = (new Category)->checkExistsNameForUpdate($id, $value);

                            if ($flag) {
                                return "Category nay da ton tai";
                            }
                        }

                    ],
                    'img' => 'nullable|uploaded_file:0,2048K,png,jpeg,jpg',
                    'is_active' => [$validator('in', [0, 1])]
                ]
            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'update Khong thanh cong1';
                $_SESSION['errors'] = $errors;

                redirect('/admin/categories/edit/' . $id);
            }

            if (is_upload('img')) {
                $data['img'] = $this->uploadFile($data['img'], 'categories');
            } else {
                $data['img'] = $category['img'];
            }

            $data['is_active'] = $data['is_active'] ?? 0;
            $data['updated_at'] = date('Y-m-d');

            $this->category->update($id, $data);

            if (
                $data['img'] != $category['img']
                && $category['img']
                && file_exists($category['img'])
            ) {
                unlink($category['img']);
            }

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Cap nhat thanh cong!';

            redirect('/admin/categories');
        } catch (\Throwable $th) {
            $this->logError($th->__tostring());

            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Cap nhat that bai';
        }
    }

    public function delete($id)
    {

        $category = $this->category->find($id);

        if (empty($category)) {
            redirect404();
        }

        $this->category->delete($id);

        if ($category['img'] && file_exists($category['img'])) {
            unlink($category['img']);
        }

        $_SESSION['status'] = true;
        $_SESSION['msg'] = 'Xoa Thanh Cong!!';

        redirect('/admin/categories');
    }
}
