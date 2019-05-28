<?php

session_start(); /*هنا بدات session*/
include_once '../include/connect.php';

if (isset($_SESSION['id'])){ /*هنا بشوف ان كان فيه session id مفتوح ولا لا*/

    /*هنا بجيب من الداتا بيس clint_id اللي بيساوي $_SESSION[id] ده وكمان بشوف role بتاعته بتساوي admin  ولا لا*/
    $stmnt = $conn->query("SELECT * FROM clints WHERE clint_id = '$_SESSION[id]' AND role ='admin'");
    $count = $stmnt->rowCount();

    if ($count != 1){ /*هنا بعد ما نفذ الاستعلام اللي فوق ده بيشوف ان كان فيه عناصر ولا لا حسب الاستعلام ده فلو ملقاش عناصر يقوم يحوله لصفحه index الاساسيه بتاعت الموقع كله*/

        header('Location: ../index.php');

    }
}else{

    header('Location: ../index.php'); /*هنا لو ملقاش session اصلا مفتوح يوديه علي صفحه index الرئيسيه بتاعت الموقع*/

}

?>