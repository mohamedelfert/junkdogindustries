<?php
require_once 'connect.php'; /* ملف الاتصال بقاعدة البيانات */
session_start(); /* هنا بدأت session */

if (isset($_POST['send'])){
    $user     = filter_var(stripslashes(strip_tags($_POST['email'])),FILTER_SANITIZE_EMAIL);
    $password = $_POST['pass'];

    if (empty($user)){
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال اسم المستخدم او البريد الاكتروني </b></div>';
    }elseif (empty($password)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب ادخال كلمه المرور </b></div>';
    }else{
        $select_user = "SELECT * FROM clints WHERE ( username = '$user' OR email = '$user' ) AND password = '$password'";
        $select_user = $conn->query($select_user);
        $cont        = $select_user -> rowCount();
        if ($cont > 0){
            $row_user = $select_user->fetch(PDO::FETCH_OBJ);
            $_SESSION['id']       = $row_user->clint_id;
            $_SESSION['username'] = $row_user->username;
            $_SESSION['fullname'] = $row_user->fullname;
            $_SESSION['gender']   = $row_user->gender;
            $_SESSION['photo']    = $row_user->photo;
            $_SESSION['email']    = $row_user->email;
            $_SESSION['mobail_1'] = $row_user->mobail_1;
            $_SESSION['mobail_2'] = $row_user->mobail_2;
            $_SESSION['address']  = $row_user->address;
            $_SESSION['role']     = $row_user->role;
            $_SESSION['city']     = $row_user->city_id;
            echo'<div class="alert alert-success" role="alert"><b> تم تسجيل دخولك بنجاح جاري تحديث الصفحه :) </b></div>';
            echo '<meta http-equiv="refresh" content="3; index.php">';
        }else{
            echo '<div class="alert alert-danger" role="alert"><b>عفوا اسم المستخدم او كلمه المرور غير صحيح </b></div>';
        }
    }
}
?>