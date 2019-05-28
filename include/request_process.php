<?php
session_start();
require_once "connect.php";

if (isset($_POST['send'])){
    $file          = @$_FILES['file'];
    $clint_id      = intval($_SESSION['id']);
    $layer         = $_POST['layers'];
    $dime_h        = filter_var($_POST['dimension_h'],FILTER_SANITIZE_NUMBER_INT);
    $dime_w        = filter_var($_POST['dimension_w'],FILTER_SANITIZE_NUMBER_INT);
    $quantity      = filter_var($_POST['quantity'],FILTER_SANITIZE_NUMBER_INT);
    $color         = $_POST['color'];
    $notes          = filter_var($_POST['notes'],FILTER_SANITIZE_STRING);
    $pro_date      = date('Y-m-d : h-i-sa');
    $errors        = array();

    if (empty($file)){
        echo '<div class="alert alert-danger" role="alert"><b> من فضلك أدخل الملف</b></div>';
    }elseif (empty($dime_h)){
        echo '<div class="alert alert-danger" role="alert"><b>من فضلك أدخل طول الشريحة </b></div>';
    }elseif (!empty($dime_h) and ( $dime_h <= 0 or $dime_h > 200 )){
        echo '<div class="alert alert-danger" role="alert"><b>يجب أن يكون طول الشريحة ما بين 1مم إلي 200مم </b></div>';
    }elseif (empty($dime_w)){
        echo '<div class="alert alert-danger" role="alert"><b>من فضلك أدخل عرض الشريحة </b></div>';
    }elseif (!empty($dime_w) and ( $dime_w <= 0 or $dime_w > 150 )){
        echo '<div class="alert alert-danger" role="alert"><b>يجب أن يكون عرض الشريحة ما بين 1مم إلي 150مم </b></div>';
    }elseif (empty($quantity)){
        echo '<div class="alert alert-danger" role="alert"><b>من فضلك أدخل الكمية المطلوبة </b></div>';
    }else{
        if (isset($_FILES['file'])){
            $file       = $_FILES['file'];
            $file_name  = $file['name'];
            $file_temp  = $file['tmp_name'];
            $file_size  = $file['size'];
            $file_error = $file['error'];
            $file_exe   = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
            $valid_exe   = array('rar'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
            if (in_array($file_exe,$valid_exe)){
                if ($file_error === 0){
                    if ($file_size <= 3000000 ){
                        $new_name  = uniqid('file','false') . "." . $file_exe;
                        $file_dir  = "../request_file/" . $new_name;
                        $file_db   = "request_file/" . $new_name;
                        if (move_uploaded_file($file_temp,$file_dir)){
                            $sql = $conn->exec("insert into products (file,
                                                                                layers,
                                                                                dimension_h,
                                                                                dimension_w,
                                                                                quantity,
                                                                                color,
                                                                                notes,
                                                                                custom,
                                                                                pro_date,
                                                                                clint_id) 
                                                                       values ('$file_db',
                                                                               '$layer',
                                                                               '$dime_h',
                                                                               '$dime_w',
                                                                               '$quantity',
                                                                               '$color',
                                                                               '$notes',
                                                                               1,
                                                                               '$pro_date',
                                                                               '$clint_id')");
                            echo '<div class="alert alert-success" role="alert"><b>تم إرسال طلبك سيتم العمل عليه :) </b></div>';
                            echo '<meta http-equiv="refresh" content="3; \'request.php\'">';
                        }else{
                            echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الملف :( </b></div>';
                        }
                    }else{
                        echo '<div class="alert alert-danger" role="alert"><b>3MB يجب أن يكون الملف أقل من أو يساوي :( </b></div>';
                    }
                }else{
                    echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الملف :( </b></div>';
                }
            }else{
                echo '<div class="alert alert-danger" role="alert"><b>rar :( يجب أن يكون الملف بصيغه </b></div>';
            }
        }
    }
}
?>