<?php

namespace App\Controllers\Admin;

use App\Controller;
use App\Models\User;
use Rakit\Validation\Validator;

class UserController extends Controller
{
   private User $user;
   public function __construct()
   {
      $this->user = new User();
   }

   public function index()
   {
      $title = 'User List';

      $data = $this->user->findAll();
      // debug($data);

      return view(
         'admin.users.index',
         compact('title', 'data')
      );
   }
   public function show($id)
   {
      $user = $this->user->find($id);
      if (empty($user)) {
         redirect404();
      }

      $title = 'Chi tiet nguoi dung';
      // debug($user);

      return view('admin.users.show', compact('user', 'title'));
   }
   public function create()
   {
      $title = 'Them Moi Nguoi dung';

      return view('admin.users.create', compact('title'));
   }

   public function store()
   {
      try {
         $data = $_POST + $_FILES;
         // debug($_POST, $_FILES, $data);

         // validate du lieu them vao
         $validator = new Validator;

         $errors = $this->validate(
            $validator,
            $data,
            [
               'name'   => 'required|max:50',
               'email'  => [
                  'required',
                  'email',
                  function ($value) {
                     $flag = (new User)->checkExistsEmailForCreate($value);

                     if ($flag) {
                        return ": Email này đã tồn tại!";
                     }
                  }
               ],
               'password' => 'required|min:6|max:30',
               'confirm_password' => 'required|same:password',
               'avatar' => 'nullable|uploaded_file:0,2048K,png,jpeg,jpg,webp',
               'type' => [$validator('in', ['admin', 'client'])]
            ]
         );

         if (!empty($errors)) {
            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Them Moi that bai!';
            $_SESSION['data'] = $_POST;
            $_SESSION['errors'] = $errors;

            redirect('/admin/users/create');
         } else {
            $_SESSION['data'] = null;
         }

         // phan xu ly du lieu them vao

         if (is_upload('avatar')) {
            $data['avatar'] = $this->uploadFile($data['avatar'], 'users');
         } else {
            $data['avatar'] = null;
         }

         // xu ly va ma hoa password
         unset($data['confirm_password']);
         $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

         $this->user->insert($data);

         $_SESSION['status'] = true;
         $_SESSION['msg'] = 'Them Moi Thanh Cong!';
         $_SESSION['data'] = null;

         redirect('/admin/users');
      } catch (\Throwable $th) {
         $this->logError($th->__tostring());

         $_SESSION['status'] = false;
         $_SESSION['msg'] = 'Them Moi that bai!';
         $_SESSION['data'] = $_POST;

         redirect('/admin/users/create');
      }
   }

   public function edit($id)
   {
      $user = $this->user->find($id);

      if (empty($user)) {
         redirect404();
      }

      $title = 'Cap nhat nguoi dung';
      return view('admin.users.edit', compact('user', 'title'));
   }
   public function update($id)
   {

      $user = $this->user->find($id);

      if (empty($user)) {
         redirect404();
      }

      try {
         $data = $_POST + $_FILES;

         // validate du lieu them vao
         $validator = new Validator;

         $errors = $this->validate(
            $validator,
            $data,
            [
               'name'   => 'required|max:50',
               'email'  => [
                  'required',
                  'email',
                  function ($value) use ($id) {
                     $flag = (new User)->checkExistsEmailForUpdate($id, $value);

                     if ($flag) {
                        return ": Email này đã tồn tại!";
                     }
                  }
               ],
               'avatar' => 'nullable|uploaded_file:0,2048K,png,jpeg,jpg',
               'type' => [$validator('in', ['admin', 'client'])]
            ]
         );

         if (!empty($errors)) {
            $_SESSION['status'] = false;
            $_SESSION['msg'] = 'Cap nhat that bai!';
            $_SESSION['errors'] = $errors;

            redirect('/admin/users/edit/' . $id);
         } else {
            $_SESSION['data'] = null;
         }

         // phan xu ly du lieu them vao

         if (is_upload('avatar')) {
            $data['avatar'] = $this->uploadFile($data['avatar'], 'users');
         } else {
            $data['avatar'] =  $user['avatar'];
         }

         // dieu chinh du lieu
         $data['updated_at'] = date('Y-m-d H:i:s');

         $this->user->update($id, $data);

         if (
            $data['avatar'] != $user['avatar']
            && file_exists($user['avatar'])
         ) {
            unlink($user['avatar']);
         }

         $_SESSION['status'] = true;
         $_SESSION['msg'] = 'Cap nhat  Thanh Cong!';

         redirect('/admin/users');
      } catch (\Throwable $th) {
         $this->logError($th->__tostring());

         $_SESSION['status'] = false;
         $_SESSION['msg'] = 'Cap nhat that bai!';

         redirect('/admin/users/edit/' . $id);
      }
   }


   public function delete($id)
   {

      $user = $this->user->find($id);

      if (empty($user)) {
         redirect404();
      }

      $this->user->delete($id);
      if ($user['avatar'] && file_exists($user['avatar'])) {
         unlink($user['avatar']);
      }

      $_SESSION['status'] = true;
      $_SESSION['msg'] = 'Xoa Thanh Cong';

      redirect('/admin/users');
   }
   public function testUploadFile()
   {

      try {
         $pathFile = $this->uploadFile($_FILES['avatar'], 'users');

         $_SESSION['msg'] = 'Upload thanh cong';
      } catch (\Throwable $th) {
         $this->logError($th->getMessage());

         $_SESSION['msg'] = 'Upload that bai';
      }

      header('Location: /admin/users');
      exit;
   }
}
