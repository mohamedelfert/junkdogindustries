<?php
include_once 'include/header.php';

$clint_id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
/*هنا عملت كويري بسيط عشان اجيب بيانات من جدول clints*/
$sql_info_clint     = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
$row_sql_info_clint = $sql_info_clint->fetch(PDO::FETCH_OBJ);

if ($_SESSION['id'] != $clint_id){
    header('Location: index.php');
}

//////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_POST['edit'])){

    $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
    $fullname = filter_var($_POST['fullname'],FILTER_SANITIZE_STRING);
    $email    = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $mobail_1 = filter_var($_POST['mobail1'],FILTER_SANITIZE_NUMBER_INT);
    $mobail_2 = filter_var($_POST['mobail2'],FILTER_SANITIZE_NUMBER_INT);
    $errors = array();

    if (empty($username)){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال اليوزر </b></div>';
    }elseif(empty($fullname)){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال الإسم الكامل لعمليات الشراء والتواصل </b></div>';
    }elseif (empty($email)){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال البريد الالكتروني </b></div>';
    }elseif (empty($mobail_1)){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم موبايل </b></div>';
    }elseif (strlen($mobail_1) !== 11){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم الموبايل الأول صحيح </b></div>';
    }elseif (!empty($mobail_2) and strlen($mobail_2) !== 11){
        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم الموبايل الثاني صحيح </b></div>';
    }else {
        $sql = $conn->query("select * from clints  
                                                           where 
                                                                 username = '$username'
                                                           or    fullname = '$fullname'
                                                           or    email    = '$email'
                                                           or    mobail_1 = '$mobail_1'
                                                           or    mobail_2 = '$mobail_2'");
        $row_sql = $sql->fetch(PDO::FETCH_OBJ);
        $count =$sql->rowCount();
        if ($count > 0){

            if ($username     == $row_sql->username  /* هنا الجملة دي عشان لو مش هيغير اي حاجه غير الباسوورد والصوره بس */
                and $fullname == $row_sql->fullname
                and $email    == $row_sql->email
                and $mobail_1 == $row_sql->mobail_1
                and $mobail_2 == $row_sql->mobail_2){

                if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                    if ($_POST['password'] != $_POST['con_password']){
                        $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                    }else{
                        $password    = $_POST['password'];
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']       = $row_user->clint_id;
                                                $_SESSION['photo']    = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $password = $_POST['password'];
                            $update = $conn->query("update clints set password = '$password' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']       = $row_user->clint_id;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }else{
                    $image       = $_FILES['image'];
                    $image_name  = $image['name'];
                    $image_temp  = $image['tmp_name'];
                    $image_size  = $image['size'];
                    $image_error = $image['error'];
                    if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                        $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                        $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                        if (in_array($image_exe,$valid_exe)){
                            if ($image_error === 0){
                                if ($image_size <= 3000000 ){
                                    $new_name  = uniqid('user','false') . "." . $image_exe;
                                    $image_dir = "images/avatar/" . $new_name;
                                    $image_db  = "images/avatar/" . $new_name;
                                    if (move_uploaded_file($image_temp,$image_dir)){
                                        $update = $conn->query("update clints set photo = '$image_db' where clint_id = '$clint_id'");
                                        if (isset($update)){
                                            $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                            $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                            $_SESSION['id']       = $row_user->clint_id;
                                            $_SESSION['photo']    = $row_user->photo;
                                            $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                            }
                        }else{
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                        }
                    }
                }

            }elseif ($username     != $row_sql->username
                     and $fullname == $row_sql->fullname
                     and $email    == $row_sql->email
                     and $mobail_1 == $row_sql->mobail_1
                     and $mobail_2 == $row_sql->mobail_2){

                $sql   = $conn->query("select * from clints where username = '$username'");
                $count = $sql->rowCount();
                if ($count > 0){
                    $errors[] = '<div class="alert alert-danger" role="alert"><b>إسم المستخدم موجود مسبقا :( </b></div>';
                }else{
                    if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                        }else{
                            $password    = $_POST['password'];
                            $image       = $_FILES['image'];
                            $image_name  = $image['name'];
                            $image_temp  = $image['tmp_name'];
                            $image_size  = $image['size'];
                            $image_error = $image['error'];
                            if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                                $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                                $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                                if (in_array($image_exe,$valid_exe)){
                                    if ($image_error === 0){
                                        if ($image_size <= 3000000 ){
                                            $new_name  = uniqid('user','false') . "." . $image_exe;
                                            $image_dir = "images/avatar/" . $new_name;
                                            $image_db  = "images/avatar/" . $new_name;
                                            if (move_uploaded_file($image_temp,$image_dir)){
                                                $update = $conn->query("update clints set username = '$username' , password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                                if (isset($update)){
                                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']       = $row_user->clint_id;
                                                    $_SESSION['username'] = $row_user->username;
                                                    $_SESSION['photo']    = $row_user->photo;
                                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                                }
                                            }else{
                                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = $_POST['password'];
                                $update = $conn->query("update clints set username = '$username' , password = '$password' where clint_id = '$clint_id'");
                                if (isset($update)){
                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']       = $row_user->clint_id;
                                    $_SESSION['username'] = $row_user->username;
                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                }
                            }
                        }
                    }else{
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set username = '$username' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']       = $row_user->clint_id;
                                                $_SESSION['username'] = $row_user->username;
                                                $_SESSION['photo']    = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = $conn->query("update clints set username = '$username' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']       = $row_user->clint_id;
                                $_SESSION['username'] = $row_user->username;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }
            }elseif ($username     == $row_sql->username
                     and $fullname != $row_sql->fullname
                     and $email    == $row_sql->email
                     and $mobail_1 == $row_sql->mobail_1
                     and $mobail_2 == $row_sql->mobail_2){

                $sql   = $conn->query("select * from clints where fullname = '$fullname'");
                $count = $sql->rowCount();
                if ($count > 0){
                    $errors[] = '<div class="alert alert-danger" role="alert"><b>هذا الإسم موجود بالفعل :( </b></div>';
                }else{
                    if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                        }else{
                            $password    = $_POST['password'];
                            $image       = $_FILES['image'];
                            $image_name  = $image['name'];
                            $image_temp  = $image['tmp_name'];
                            $image_size  = $image['size'];
                            $image_error = $image['error'];
                            if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                                $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                                $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                                if (in_array($image_exe,$valid_exe)){
                                    if ($image_error === 0){
                                        if ($image_size <= 3000000 ){
                                            $new_name  = uniqid('user','false') . "." . $image_exe;
                                            $image_dir = "images/avatar/" . $new_name;
                                            $image_db  = "images/avatar/" . $new_name;
                                            if (move_uploaded_file($image_temp,$image_dir)){
                                                $update = $conn->query("update clints set fullname = '$fullname' , password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                                if (isset($update)){
                                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']       = $row_user->clint_id;
                                                    $_SESSION['fullname'] = $row_user->fullname;
                                                    $_SESSION['photo']    = $row_user->photo;
                                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                                }
                                            }else{
                                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = $_POST['password'];
                                $update = $conn->query("update clints set fullname = '$fullname' , password = '$password' where clint_id = '$clint_id'");
                                if (isset($update)){
                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']       = $row_user->clint_id;
                                    $_SESSION['fullname'] = $row_user->fullname;
                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                }
                            }
                        }
                    }else{
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set fullname = '$fullname' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']       = $row_user->clint_id;
                                                $_SESSION['fullname'] = $row_user->fullname;
                                                $_SESSION['photo']    = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = $conn->query("update clints set fullname = '$fullname' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']       = $row_user->clint_id;
                                $_SESSION['fullname'] = $row_user->fullname;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }
            }elseif ($username     == $row_sql->username
                     and $fullname == $row_sql->fullname
                     and $email    != $row_sql->email
                     and $mobail_1 == $row_sql->mobail_1
                     and $mobail_2 == $row_sql->mobail_2){

                $sql   = $conn->query("select * from clints where email = '$email'");
                $count = $sql->rowCount();
                if ($count > 0){
                    $errors[] = '<div class="alert alert-danger" role="alert"><b>البريد الالكتروني مسجل مسبقا :( </b></div>';
                }else{
                    if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                        }else{
                            $password    = $_POST['password'];
                            $image       = $_FILES['image'];
                            $image_name  = $image['name'];
                            $image_temp  = $image['tmp_name'];
                            $image_size  = $image['size'];
                            $image_error = $image['error'];
                            if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                                $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                                $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                                if (in_array($image_exe,$valid_exe)){
                                    if ($image_error === 0){
                                        if ($image_size <= 3000000 ){
                                            $new_name  = uniqid('user','false') . "." . $image_exe;
                                            $image_dir = "images/avatar/" . $new_name;
                                            $image_db  = "images/avatar/" . $new_name;
                                            if (move_uploaded_file($image_temp,$image_dir)){
                                                $update = $conn->query("update clints set email = '$email' , password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                                if (isset($update)){
                                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']    = $row_user->clint_id;
                                                    $_SESSION['email'] = $row_user->email;
                                                    $_SESSION['photo'] = $row_user->photo;
                                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                                }
                                            }else{
                                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = $_POST['password'];
                                $update = $conn->query("update clints set email = '$email' , password = '$password' where clint_id = '$clint_id'");
                                if (isset($update)){
                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']    = $row_user->clint_id;
                                    $_SESSION['email'] = $row_user->email;
                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                }
                            }
                        }
                    }else{
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set email = '$email' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']    = $row_user->clint_id;
                                                $_SESSION['email'] = $row_user->email;
                                                $_SESSION['photo'] = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = $conn->query("update clints set email = '$email' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']    = $row_user->clint_id;
                                $_SESSION['email'] = $row_user->email;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }
            }elseif ($username     == $row_sql->username
                     and $fullname == $row_sql->fullname
                     and $email    == $row_sql->email
                     and $mobail_1 != $row_sql->mobail_1
                     and $mobail_2 == $row_sql->mobail_2){

                $sql   = $conn->query("select * from clints where mobail_1 = '$mobail_1'");
                $count = $sql->rowCount();
                if ($count > 0){
                    $errors[] = '<div class="alert alert-danger" role="alert"><b>رقم الموبايل مسجل من قبل شخص أخر :( </b></div>';
                }else{
                    if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                        }else{
                            $password    = $_POST['password'];
                            $image       = $_FILES['image'];
                            $image_name  = $image['name'];
                            $image_temp  = $image['tmp_name'];
                            $image_size  = $image['size'];
                            $image_error = $image['error'];
                            if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                                $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                                $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                                if (in_array($image_exe,$valid_exe)){
                                    if ($image_error === 0){
                                        if ($image_size <= 3000000 ){
                                            $new_name  = uniqid('user','false') . "." . $image_exe;
                                            $image_dir = "images/avatar/" . $new_name;
                                            $image_db  = "images/avatar/" . $new_name;
                                            if (move_uploaded_file($image_temp,$image_dir)){
                                                $update = $conn->query("update clints set mobail_1 = '$mobail_1' , password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                                if (isset($update)){
                                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']       = $row_user->clint_id;
                                                    $_SESSION['mobail_1'] = $row_user->mobail_1;
                                                    $_SESSION['photo']    = $row_user->photo;
                                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                                }
                                            }else{
                                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = $_POST['password'];
                                $update = $conn->query("update clints set mobail_1 = '$mobail_1' , password = '$password' where clint_id = '$clint_id'");
                                if (isset($update)){
                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']       = $row_user->clint_id;
                                    $_SESSION['mobail_1'] = $row_user->mobail_1;
                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                }
                            }
                        }
                    }else{
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set mobail_1 = '$mobail_1' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']       = $row_user->clint_id;
                                                $_SESSION['mobail_1'] = $row_user->mobail_1;
                                                $_SESSION['photo']    = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = $conn->query("update clints set mobail_1 = '$mobail_1' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']       = $row_user->clint_id;
                                $_SESSION['mobail_1'] = $row_user->mobail_1;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }
            }elseif ($username     == $row_sql->username
                     and $fullname == $row_sql->fullname
                     and $email    == $row_sql->email
                     and $mobail_1 == $row_sql->mobail_1
                     and $mobail_2 != $row_sql->mobail_2){

                $sql   = $conn->query("select * from clints where mobail_2 = '$mobail_2'");
                $count = $sql->rowCount();
                if ($count > 0){
                    $errors[] = '<div class="alert alert-danger" role="alert"><b>رقم الهاتف مسجل من قبل شخص أخر :( </b></div>';
                }else{
                    if ($_POST['password'] != '' or $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            $errors[] = '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير متطابقتين</b></div>';
                        }else{
                            $password    = $_POST['password'];
                            $image       = $_FILES['image'];
                            $image_name  = $image['name'];
                            $image_temp  = $image['tmp_name'];
                            $image_size  = $image['size'];
                            $image_error = $image['error'];
                            if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                                $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                                $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                                if (in_array($image_exe,$valid_exe)){
                                    if ($image_error === 0){
                                        if ($image_size <= 3000000 ){
                                            $new_name  = uniqid('user','false') . "." . $image_exe;
                                            $image_dir = "images/avatar/" . $new_name;
                                            $image_db  = "images/avatar/" . $new_name;
                                            if (move_uploaded_file($image_temp,$image_dir)){
                                                $update = $conn->query("update clints set mobail_2 = '$mobail_2' , password = '$password' , photo = '$image_db' where clint_id = '$clint_id'");
                                                if (isset($update)){
                                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']       = $row_user->clint_id;
                                                    $_SESSION['mobail_2'] = $row_user->mobail_2;
                                                    $_SESSION['photo']    = $row_user->photo;
                                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                                }
                                            }else{
                                                $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = $_POST['password'];
                                $update = $conn->query("update clints set mobail_2 = '$mobail_2' , password = '$password' where clint_id = '$clint_id'");
                                if (isset($update)){
                                    $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']       = $row_user->clint_id;
                                    $_SESSION['mobail_2'] = $row_user->mobail_2;
                                    $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                }
                            }
                        }
                    }else{
                        $image       = $_FILES['image'];
                        $image_name  = $image['name'];
                        $image_temp  = $image['tmp_name'];
                        $image_size  = $image['size'];
                        $image_error = $image['error'];
                        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
                            $image_exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                            $valid_exe = array('png', 'jpeg', 'jpg', 'gif');
                            if (in_array($image_exe,$valid_exe)){
                                if ($image_error === 0){
                                    if ($image_size <= 3000000 ){
                                        $new_name  = uniqid('user','false') . "." . $image_exe;
                                        $image_dir = "images/avatar/" . $new_name;
                                        $image_db  = "images/avatar/" . $new_name;
                                        if (move_uploaded_file($image_temp,$image_dir)){
                                            $update = $conn->query("update clints set mobail_2 = '$mobail_2' , photo = '$image_db' where clint_id = '$clint_id'");
                                            if (isset($update)){
                                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']       = $row_user->clint_id;
                                                $_SESSION['mobail_2'] = $row_user->mobail_2;
                                                $_SESSION['photo']    = $row_user->photo;
                                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                                            }
                                        }else{
                                            $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else{
                                        $errors[] = '<div class="alert alert-danger" role="alert"><b>يجب أن تكون الصوره بحجم 3 ميجا أو أقل :( </b></div>';
                                    }
                                }else{
                                    $errors[] = '<div class="alert alert-danger" role="alert"><b>حصل خطأ أثناء رفع الصورة :( </b></div>';
                                }
                            }else{
                                $errors[] = '<div class="alert alert-danger" role="alert"><b>الصيغ المسموح بها (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = $conn->query("update clints set mobail_2 = '$mobail_2' where clint_id = '$clint_id'");
                            if (isset($update)){
                                $user_info = $conn->query("SELECT * FROM clints WHERE clint_id = '$clint_id'");
                                $row_user = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']       = $row_user->clint_id;
                                $_SESSION['mobail_2'] = $row_user->mobail_2;
                                $success ='<div class="alert alert-success" role="alert"><b> تم تحديث بياناتك بنجاح :) </b></div>';
                            }
                        }
                    }
                }
            }else{
                $errors[] = '<div class="alert alert-danger" role="alert"><b>البيانات التي قمت بإدخالها موجوده لدينا مسبقا</b></div>';
            }
        }else{

        }
    }

}
?>

<div class="container">
    <div class="row">

        <!-- Asid Bar ( login Panel ) -->
        <div class="col-lg-3">

            <!-- Asid Bar ( login Panel ) -->
            <?php include "include/sidbar.php"; ?>
            <!-- Asid Bar ( login Panel ) End -->

        </div>
        <!-- Asid Bar ( login Panel ) End -->

        <!--  -->
        <div class="col-lg-9">
            <div class="col-lg-10 wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width:550px;margin:2% 20%;">

                <?php
                if (isset($success)){
                    echo " <div class='aler alert-success role=alert' style='text-align:center;color:#2054ff'>
                            <b> $success </b>
                         </div>";
                    echo '<meta http-equiv="refresh" content="2; \'profile.php?user='.$clint_id.'\'">';
                }

                if (isset($errors)){
                    foreach ($errors as $error){
                        echo" <div class='aler alert-danger role=alert' style='text-align:center'>
                            <b> $error </b>
                         </div>";
                    }
                }
                ?>

                <form action="" method="post" class="login100-form validate-form" id="edit_profile" enctype="multipart/form-data">
                    <span class="login100-form-title p-b-33" style="text-decoration: underline">
                        Edit Profile Information
                    </span>

                    <label><span style="color: red">* </span>User Name :</label>
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="username" value="<?php echo $row_sql_info_clint->username; ?>" placeholder="User Name">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label><span style="color: red">* </span>Full Name :</label>
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="fullname" value="<?php echo $row_sql_info_clint->fullname; ?>" placeholder="Your Name">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label><span style="color: red">* </span>Your Email :</label>
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="email" value="<?php echo $row_sql_info_clint->email; ?>" placeholder="Email" required>
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label><span style="color: red">* </span>Your Password :</label>
                    <div class="wrap-input100 rs1 validate-input">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label><span style="color: red">* </span>Confairm Password :</label>
                    <div class="wrap-input100 rs1 validate-input">
                        <input class="input100" type="password" name="con_password" placeholder="Confairm Password">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label><span style="color: red">* </span>Mobail :</label>
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="mobail1" value="0<?php echo $row_sql_info_clint->mobail_1; ?>" placeholder="Your Mobail Number1">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label>Second Mobail  :</label>
                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="mobail2" value="0<?php echo $row_sql_info_clint->mobail_2; ?>" placeholder="Your Mobail Number2">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <label> Photo :</label>
                    <div class="wrap-input100 validate-input">
                        <input type="file" name="image" class="form-control" style="height: 100%;">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <div class="container-login100-form-btn m-t-20">
                        <input type="submit" name="edit" value="Edit Profile" class="login100-form-btn">
                    </div>
                </form>
            </div>
        </div>
        <!--  -->

    </div>
</div>

<?php include_once 'include/footer.php'; ?>