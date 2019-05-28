<?php
session_start();
require "connect.php";

if (isset($_POST['send'])){
    $title     = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $content   = filter_var($_POST['content'],FILTER_SANITIZE_STRING);
    $pro_id    = intval($_POST['pro_id']);
    $clint_id  = $_SESSION['id'];
    $comm_date = date('Y-m-d : h-i-sa');

    if (empty($title)){
        echo'<div class="alert alert-danger text-center" role="alert"><b>يجب وضع عنوان للتعليق</b></div>';
    }elseif (empty($content)){
        echo'<div class="alert alert-danger text-center" role="alert"><b>يجب كتابه محتوي التعليق</b></div>';
    }else{
        $insert_comm = $conn->query("INSERT INTO comments (pro_id,clint_id,title,content,status,comm_date)
                                                           VALUES ('$pro_id','$clint_id','$title','$content','Disable','$comm_date')");
        if (isset($insert_comm)){
            echo'<div class="alert alert-success text-center" role="alert"><b>تم إضافة التعليق سيتم نشره بعد موافقة الإدارة :)</b></div>';
            echo '<meta http-equiv="refresh" content="3; item.php?pro_id='.$pro_id.'">';
        }
    }
}

