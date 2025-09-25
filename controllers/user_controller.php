<?php
session_start(); 

require_once '../classes/user_class.php';

function register_user_ctr($name, $email, $password, $phone_number, $country, $city)
{
    $user = new User();
    $user_id = $user->createUser($name, $email, $password, $phone_number, $country, $city);
    if ($user_id) {
        return $user_id;
    }
    return false;
}

function get_user_by_email_ctr($email)
{
    $user = new User();
    return $user->getUserByEmail($email);
}

function login_user_ctr($email, $password)
{
    $user = new User();
   
    return $user->loginUser($email, $password);
}
?>
