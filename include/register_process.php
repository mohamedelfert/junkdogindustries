<?php
session_start();
require_once "connect.php";

if (isset($_POST['send'])){
    $userName     = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
    $fullname     = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $email        = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $password     = $_POST['password'];
    $con_password = $_POST['con_password'];
    $mobail1      = filter_var($_POST['mobail1'],FILTER_SANITIZE_NUMBER_INT);
    $mobail2      = filter_var($_POST['mobail2'],FILTER_SANITIZE_NUMBER_INT);
    $address      = filter_var($_POST['about'],FILTER_SANITIZE_STRING);
    $gender       = $_POST['gender'];
    $Governorate  = $_POST['Gov'];

    if (empty($userName)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال اليوزر </b></div>';
    }elseif(empty($fullname)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال الإسم الكامل لعمليات الشراء والتواصل </b></div>';
    }elseif (empty($email)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال البريد الالكتروني </b></div>';
    }elseif (empty($password)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال كلمة مرور </b></div>';
    }elseif (empty($con_password)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إعادة إدخال كلمة المرور </b></div>';
    }elseif ($password !== $con_password){
        echo '<div class="alert alert-danger" role="alert"><b>كلمتا المرور غير مطابقتين </b></div>';
    }elseif (empty($mobail1)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم موبايل </b></div>';
    }elseif (strlen($mobail1) !== 11){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم الموبايل الأول صحيح </b></div>';
    }elseif (!empty($mobail2) and strlen($mobail2) !== 11){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال رقم الموبايل الثاني صحيح </b></div>';
    }elseif (empty($address)){
        echo '<div class="alert alert-danger" role="alert"><b>يجب إدخال عنوانك لعمليات الشراء والتواصل </b></div>';
    }else{
        $sql_username  = $conn->query("select username from clints where username = '$userName'");
        $count_user    = $sql_username->rowCount();
        $sql_email     = $conn->query("select email from clints where email = '$email'");
        $count_email   = $sql_email->rowCount();
        $sql_mobail1   = $conn->query("select mobail_1 from clints where mobail_1 = '$mobail1'");
        $count_mobail1 = $sql_mobail1->rowCount();
        $sql_mobail2   = $conn->query("select mobail_2 from clints where mobail_2 = '$mobail2'");
        $count_mobail2 = $sql_mobail2->rowCount();
        if ($count_user > 0) {
            echo '<div class="alert alert-danger" role="alert"><b>عفوا اسم المستخدم مسجل مسبقا </b></div>';
        }elseif ($count_email > 0){
            echo '<div class="alert alert-danger" role="alert"><b>عفوا البريد هذا مسجل مسبقا </b></div>';
        }elseif ($count_mobail1 > 0){
            echo '<div class="alert alert-danger" role="alert"><b>عفوا رقم الموبايل 1 مسجل مسبقا </b></div>';
        }elseif (!empty($mobail2) and $count_mobail2 > 0){
            echo '<div class="alert alert-danger" role="alert"><b>عفوا رقم الموبايل 2 مسجل مسبقا </b></div>';
        }else{
            if (isset($_FILES['image'])){
                $image       = $_FILES['image'];
                $image_name  = $image['name'];
                $image_temp  = $image['tmp_name'];
                $image_size  = $image['size'];
                $image_error = $image['error'];
                $image_exe   = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));
                $valid_exe   = array('png','jpeg','jpg','gif'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
                if (in_array($image_exe,$valid_exe)){
                    if ($image_error === 0){
                        if ($image_size <= 3000000 ){
                            $new_name  = uniqid('user','false') . "." . $image_exe;
                            $image_dir = "../images/avatar/" . $new_name;
                            $image_db  = "images/avatar/" . $new_name;
                            if (move_uploaded_file($image_temp,$image_dir)){
                                $query = "insert into clints (username,
                                                                fullname,
                                                                email,
                                                                password,
                                                                mobail_1,
                                                                mobail_2,
                                                                address,
                                                                photo,
                                                                gender,
                                                                city_id,
                                                                role) 
                                                      values ('$userName',
                                                              '$fullname',
                                                              '$email',
                                                              '$password',
                                                              '$mobail1',
                                                              '$mobail2',
                                                              '$address',
                                                              '$image_db',
                                                              '$gender',
                                                              '$Governorate',
                                                              'user')";
                                $stmnt = $conn->prepare($query);
                                $stmnt->execute();
                                if (isset($stmnt)){
                                    $user_info = $conn->prepare("SELECT * FROM clints WHERE username = :username");
                                    $user_info->bindParam(':username',$userName,PDO::PARAM_STR);
                                    $user_info->execute();
                                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
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
                                    echo'<div class="alert alert-success" role="alert"><b> تم التسجيل بنجاح جاري تحويلك للصفحه الرئيسيه :) </b></div>';
                                    echo '<meta http-equiv="refresh" content="3; \'index.php\'">';
                                }
                            }else{
                                echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                            }
                        }else{
                            echo '<div class="alert alert-danger" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                        }
                    }else{
                        echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                    }
                }else{
                    echo '<div class="alert alert-danger" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                }
            }else{
                $query = "insert into clints (username,
                                            fullname,
                                            email,
                                            password,
                                            mobail_1,
                                            mobail_2,
                                            address,
                                            photo,
                                            gender,
                                            city_id,
                                            role) 
                                  values ('$userName',
                                          '$fullname',
                                          '$email',
                                          '$password',
                                          '$mobail1',
                                          '$mobail2',
                                          '$address',
                                          'images/non-avatar.png',
                                          '$gender',
                                          '$Governorate',
                                          'user')";
                $stmnt = $conn->prepare($query);
                $stmnt->execute();
                if (isset($stmnt)){
                    $user_info = $conn->prepare("SELECT * FROM clints WHERE username = :username");
                    $user_info->bindParam(':username',$userName,PDO::PARAM_STR);
                    $user_info->execute();
                    $row_user = $user_info->fetch(PDO::FETCH_OBJ);
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
                    echo'<div class="alert alert-success" role="alert"><b> تم التسجيل بنجاح جاري تحويلك للصفحه الرئيسيه :) </b></div>';
                    echo '<meta http-equiv="refresh" content="3; \'index.php\'">';
                }
            }
        }
    }
}

?>
