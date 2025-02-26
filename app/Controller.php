<?php

namespace App;

class Controller
{

    public function validate($validator, $data, $rules)
    {
        $validation = $validator->make($data, $rules);

        $validation->validate();

        if ($validation->fails()) {
            return $validation->errors()->firstOfAll();
        }

        return [];
    }
    public function logError($message)
    {
        $date = date('d-m-Y');

        error_log($message, 3, "storage/logs/$date.log");
    }

    public function uploadFile(array $file, $folder = null)
    {
        $fileTmpPath = $file['tmp_name'];
        $fileName = time() . '-' . $file['name'];

        $uploadDir = 'storage/uploads/' . $folder . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $desPath = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $desPath)) {

            return  $desPath;
        }
        throw new \Exception('Loi up load FILE');
    }
}
