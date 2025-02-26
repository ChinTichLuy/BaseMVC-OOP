<?php

namespace App\Controllers\Admin;

use App\Controller;
use App\Models\Banner;
use Rakit\Validation\Validator;

class BannerController extends Controller
{
    private Banner $banner;

    public function __construct()
    {
        $this->banner = new Banner();
    }

    public function index()
    {
        $title = 'Danh sach Banner';

        $data = $this->banner->findAll();

        return view('admin.banners.index', compact('data', 'title'));
    }
    public function create()
    {
        $title = 'Tao moi Banner';

        return view('admin.banners.create', compact('title'));
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
                    'name' => ['required', 'max:50'],
                    'img' => 'nullable|uploaded_file:0,2048K,png,jpeg,jpg,webp',
                    'is_active' => [$validator('in', [0, 1])]
                ]
            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'Them moi That bai1!';
                $_SESSION['data'] = $_POST;
                $_SESSION['erors'] = $errors;

                redirect('/admin/banners/create');
            } else {
                $_SESSION['data'] = null;
            }

            if (is_upload('img')) {
                $data['img'] = $this->uploadFile($data['img'], 'banners');
            } else {
                $data['img'] = null;
            }

            $data['is_active'] = $data['is_active'] ?? 0;

            $this->banner->insert($data);

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Them moi thanh cong!';

            redirect('/admin/banners');
        } catch (\Throwable $th) {
            $this->logError($th->__tostring());

            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Them moi That bai2!';
            $_SESSION['data'] = $_POST;

            redirect('/admin/banners/create');
        }
    }
    public function show($id)
    {
        $banner = $this->banner->find($id);

        if (empty($banner)) {
            redirect404();
        }

        $title = 'Chi tiet banner';

        return view('admin.banners.show', compact('banner', 'title'));
    }
    public function edit($id)
    {
        $banner = $this->banner->find($id);

        if (empty($banner)) {
            redirect404();
        }

        $title = "Cap nhat Banner";

        return view('admin.banners.edit', compact('banner', 'title'));
    }
    public function update($id)
    {
        $banner = $this->banner->find($id);

        if (empty($banner)) {
            redirect404();
        }

        try {
            $data = $_POST + $_FILES;

            $validator = new Validator;

            $errors = $this->validate(
                $validator,
                $data,
                [
                    'name' => ['required', 'max:50'],
                    'img' => 'nullable|uploaded_file:0,2048K,png,jpeg,jpg,webp',
                    'is_active' => [$validator('in', [0, 1])]
                ]
            );

            if (!empty($errors)) {
                $_SESSION['status'] = false;
                $_SESSION['msg'] = 'Cap nhat That Bai';
                $_SESSION['errors'] = $errors;

                redirect('/admin/banners/edit/' . $id);
            }

            if(is_upload('img')){
                $data['img'] = $this->uploadFile($data['img'], 'banners');
            }else{
                $data['img'] = $banner['img'];
            }

            $data['is_active'] = $data['is_active'] ?? 0;

            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->banner->update($id,$data);

            if(
                $data['img'] != $banner['img']
                && $banner['img']
                && file_exists($banner['img'])
            ){
                unlink($banner['img']);
            }

            $_SESSION['status'] = true;
            $_SESSION['msg'] = 'Cap nhat Thanh Cong';

            redirect('/admin/banners');

        } catch (\Throwable $th) {
           $this->logError($th->__tostring());

           $_SESSION['status'] = false;
           $_SESSION['msg'] = 'Cap nhat that bai';
         
           redirect('/admin/banners/edit/' .$id);
        }
    }
    public function delete($id) {
        $banner = $this->banner->find($id);

        if(empty($banner)){
            redirect404();
        }

       $this->banner->delete($id);

       if($banner['img'] && file_exists($banner['img'])){
        unlink($banner['img']);
       }

       $_SESSION['status'] = true;
       $_SESSION['msg'] = 'Xoa Thanh Cong';

       redirect('/admin/banners');
    }
}
