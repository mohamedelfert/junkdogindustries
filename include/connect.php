<?php

// connect to database with ( mysqli ) library
/*
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'junkdogindustries';

$conn = mysqli_connect ($host,$username,$password) or die(mysqli_error());
$db  = mysqli_select_db ($conn,$dbname) or die(mysqli_error());
if (!$db){
    echo "Not Connected";
}else{
    echo "Connected";
}
*/

// connect to database with ( PDO ) class
$dsn    = 'mysql:host=localhost;dbname=junkdogindustries';
$user   = 'root';
$pass   = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

try{
    $conn = new PDO($dsn,$user,$pass,$option);
}catch (exception $e){
    echo '<div class="aler alert-danger role="alert" style="text-align:center;color:red;font-size:30px;padding:10px"><b>هناك خطأ في الاتصال بقاعده البيانات</b><br>'. $e->getMessage() .'</div>';
}