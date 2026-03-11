<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'google_id', 'facebook_id', 'remember_token'];

    public static function validate(array $data) {
        $errors = [];
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Tên đăng nhập phải có từ 2 ký tự trở lên';   
        } 
        if (! $data['email']) {
            $errors['email'] = 'Vui lòng nhập email';
        } else if (!preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])) {
            $errors['email'] = 'Định dạng email không đúng';
            // !preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])
        } else if (static::where('email', $data['email'])->count() > 0) {
            $errors['email'] = 'Email này đã được sử dụng';
        }
        if (!preg_match("/^[0-9]{10,11}$/", $data['phone'])) {
            $errors['phone'] = 'Định dạng số điện thoại không đúng';
        } 
        if (!strlen($data['address'])) {
            $errors['address'] = 'Vui lòng nhập địa chỉ';   
        }   
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($data['password'] != $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu nhập lại không trùng khớp';
        }
        
        return $errors;
    }   

    public static function validateUpdate(array $data) {
        $errors = [];
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Tên đăng nhập phải có từ 2 ký tự trở lên';   
        } 
        if (! $data['email']) {
            $errors['email'] = 'Vui lòng nhập email';
        } else if (!preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])) {
            $errors['email'] = 'Định dạng email không đúng';
            // !preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])
        } else if (static::where('email', $data['email'])->count() > 0) {
            $errors['email'] = 'Email này đã được sử dụng';
        }
        if (!preg_match("/^[0-9]{10,11}$/", $data['phone'])) {
            $errors['phone'] = 'Định dạng số điện thoại không đúng';
        } 
        if (!strlen($data['address'])) {
            $errors['address'] = 'Vui lòng nhập địa chỉ';   
        }   
        
        return $errors;
    }   

    public static function validatePass(array $data) {
        $errors = [];
        
        if (strlen($data['new_password']) < 6) {
            $errors['new_password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($data['new_password'] != $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu nhập lại không trùng khớp';
        }
        
        return $errors;
    }   
}
