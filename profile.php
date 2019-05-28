<?php
include_once 'include/header.php';

$Msg = '';

//////////////////////////////////////////بيانات العضو/////////////////////////////////////////////////////
$id    = $_GET['user'];
/*هنا باعمل استعلام بسيط عشان اجيب بيانات العضو من الداتا بيس */
$sql   = $conn->query("SELECT * FROM clints join cities on clints.city_id = cities.id WHERE clint_id = '$id'");
$count = $sql->rowCount();
if ($count != 1){
    header('Location: index.php');
}
$row   = $sql->fetch(PDO::FETCH_OBJ);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*خاص بحذف التعليقات*/
if (@$_GET['box'] == 'delete_comm'){

    $comm_id     = intval($_GET['comm_id']);
    $delete_comm = $conn->exec("DELETE FROM comments WHERE comm_id = '$comm_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف التعليق بنجاح </b></div>';
    header("refresh:2; profile.php?user=$id");

}
/*END*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*خاص بحذف الطلبات*/
if (@$_GET['box'] == 'delete_order'){

    $order_id     = intval($_GET['order_id']);
    $delete_order = $conn->exec("DELETE FROM orders WHERE order_id = '$order_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف الطلب بنجاح </b></div>';
    header("refresh:2; profile.php?user=$id");

}
/*END*/

?>
<div class="container">
    <div class="row">

        <div class="col-lg-3">

            <!-- Asid Bar ( login Panel ) -->
            <?php include "include/sidbar.php"; ?>
            <!-- Asid Bar ( login Panel ) End -->

        </div>

        <!-- Start article -->
        <article class="col-lg-9">
            <div class="col-md-12">

                <?php echo $Msg; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->

                <!-- خاص بعرض بيانات البروفايل -->
                <div class="panel">
                    <div class="panel-heading text-center"><b>المعلومات الشخصيه</b></div>
                    <div class="panel-body">
                        <div class="col-md-3" style="float:right;margin:15px 0;">
                            <img src="<?php echo $row->photo; ?>" class="img-thumbnail" width="50%">
                        </div>

                        <div class="col-md-9" style="margin:15px 0;padding:0 40px">
                            <p><b>UserName : </b> <?php echo $row->username; ?> </p>
                            <p><b>Email : </b> <?php echo $row->email; ?> </p>
                            <p><b>Phone 1 : </b> <?php echo $row->mobail_1; ?> </p>
                            <p><b>Address : </b> <?php echo $row->address; ?> </p>
                            <p><b>City : </b> <?php echo $row->name; ?> </p>
                            <p><b>Role : </b> <?php if ($row->role == 'admin'){echo 'المدير العام';}else{echo 'عضو';} ?> </p>
                            <p><b>Gender : </b> <?php if ($row->gender == 'male'){echo '<img src="images/male.png" width="45px">';}else{echo '<img src="images/female.png" width="45px">';} ?> </p>
                        </div>

                    </div>

                    <?php if (@$_SESSION['id'] != $id) {

                    }else {
                         echo '
                            <hr>
                            <div class="panel-footer" style="padding-bottom:15px;margin-bottom:10px">
                                <div class="col-md-12">
                         ';
                         ?>
                         <?php
                         if (@$_SESSION['id'] == $row->clint_id) {
                             echo '<a href="edite_profile.php?id=' . $row->clint_id . '" class="btn btn-danger pull-left btn-sm">تعديل البيانات</a>';
                         }
                         ?>
                         <?php
                         echo '
                                </div>
                            </div>
                        ';
                    }
                    ?>

                </div>
                <!-- END خاص بعرض بيانات البروفايل -->

                <!-- خاص بعرض التعليقات التي علقها العضو -->
                <?php if (@$_SESSION['id'] != $id) {

                }else{
                    echo '
                        <div class="panel">
                            <div class="panel-heading text-center"><b>التعليقات التي قمت بكتابتها</b></div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>View Comment</th>
                                            <th>Status</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    ';
                    ?>
                                    <?php

                                    /*الجزء دا خاص لعمل تعدد للصفحات التعليقات*/
                                    $per_comm = 3; /*عدد التعليقات اللي عايزها تظهر في الصفحه*/
                                    /*هنا باتاكد ان مفيش متغير اسمه page جاي في الرابط ولا لا*/
                                    if (!isset($_GET['page'])){
                                        $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
                                    }else{
                                        $page = (int)$_GET['page']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
                                    }
                                    $start_from = ($page - 1) * $per_comm;
                                    /***********************************************/

                                    $select_comm   = $conn->query("SELECT * FROM comments JOIN clints ON comments.clint_id = clints.clint_id WHERE username = '$_SESSION[username]' ORDER BY comm_id DESC LIMIT $start_from , $per_comm");
                                    $comment_count = $select_comm->rowCount();
                                    if ($comment_count > 0){
                                        $num = 1;
                                        while ($row_select_comm = $select_comm->fetch(PDO::FETCH_OBJ)){
                                            echo'
                                                <tr>
                                                    <td>'.$num.'</td>
                                                    <td>'.substr($row_select_comm->title,0,70).' ....</td>
                                                    <td>'.$row_select_comm->comm_date.'</td>
                                                    <td><a href="item.php?pro_id='.$row_select_comm->pro_id.'" class="btn btn-primary btn-xs">View</a></td>
                                                    <td>'.$row_select_comm->status.'</td>
                                                    <td><a href="profile.php?user='.$_SESSION['id'].'&box=delete_comm&comm_id='.$row_select_comm->comm_id.'" class="btn btn-danger btn-xs">Delete</a></td>
                                                </tr>
                                            ';
                                            $num++;
                                        }
                                    }else{
                                        echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لا يوجد أي تعليقات لديك :(</b></div>';
                                    }
                                    ?>
                                    </tbody>
                                </table>

                                <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات التعليقات -->
                                <?php
                                $comments       = $conn->query("SELECT * FROM comments WHERE clint_id = '$id'"); /*ال comments اللي عندي كلها*/
                                $count_comments = $comments->rowCount();
                                $total_pages    = ceil($count_comments / $per_comm); /*عدد الصفحات اللي هتتقسم بناء علي عدد comments اللي عندي*/
                                ?>
                                <nav class="text-center">
                                    <ul style="margin:20px 0;">
                                        <?php
                                        for ($i = 1 ; $i <= $total_pages ; $i++){
                                            echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                                                    <a href="profile.php?user='.$_SESSION['id'].'&page='.$i.'">'.$i.'</a>
                                                  </li>
                                            ';
                                        }
                                        ?>
                                    </ul>
                                </nav>
                                <!-- النهايه -->
                    <?php
                        echo '  
                                </div>
                            </div>
                        ';
                }
                ?>
                <!--END خاص بعرض التعليقات التي علقها العضو -->

                <!-- خاص بعرض المنتجات التي طلبها العضو -->
                <?php if (@$_SESSION['id'] != $id) {

                }else{
                    echo '
                        <div class="panel">
                            <div class="panel-heading text-center"><b>المنتجات التي قمت بطلبها</b></div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Date Ordered</th>
                                            <th>View Product</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    ';
                    ?>
                                    <?php

                                    /*الجزء دا خاص لعمل تعدد للصفحات الطلبات*/
                                    $per_order = 3; /*عدد التعليقات اللي عايزها تظهر في الصفحه*/
                                    /*هنا باتاكد ان مفيش متغير اسمه page2 جاي في الرابط ولا لا*/
                                    if (!isset($_GET['page2'])){
                                        $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
                                    }else{
                                        $page = (int)$_GET['page2']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
                                    }
                                    $start_from = ($page - 1) * $per_order;
                                    /***********************************************/
                                    $select_order = $conn->query("SELECT * FROM order_pro_clint where username = '$_SESSION[username]' ORDER BY order_id DESC LIMIT $start_from , $per_order");
                                    $order_count  = $select_order->rowCount();
                                    if ($order_count > 0){
                                        $num = 1;
                                        while ($row_select_order = $select_order->fetch(PDO::FETCH_OBJ)){
                                            echo'
                                                <tr>
                                                    <td>'.$num.'</td>
                                                    <td>'.substr($row_select_order->product_name,0,70).'</td>
                                                    <td>'.$row_select_order->price.'</td>
                                                    <td>'.$row_select_order->order_date.'</td>
                                                    <td><a href="item.php?pro_id='.$row_select_order->pro_id.'" class="btn btn-primary btn-xs">View</a></td>
                                                    <td><a href="profile.php?user='.$_SESSION['id'].'&box=delete_order&order_id='.$row_select_order->order_id.'" class="btn btn-danger btn-xs">Delete</a></td>
                                                </tr>
                                            ';
                                            $num++;
                                        }
                                    }else{
                                        echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لا يوجد أي منتجات مطلوبة :(</b></div>';
                                    }
                                    ?>
                                    </tbody>
                                </table>

                    <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات الطلبات -->
                    <?php
                    $orders       = $conn->query("SELECT * FROM order_pro_clint where username = '$_SESSION[username]'"); /*ال orsers اللي عندي كلها*/
                    $count_orders = $orders->rowCount();
                    $total_pages    = ceil($count_orders / $per_order); /*عدد الصفحات اللي هتتقسم بناء علي عدد orsers اللي عندي*/
                    ?>
                    <nav class="text-center">
                        <ul style="margin:20px 0;">
                            <?php
                            for ($i = 1 ; $i <= $total_pages ; $i++){
                                echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                                        <a href="profile.php?user='.$_SESSION['id'].'&page2='.$i.'">'.$i.'</a>
                                      </li>
                                ';
                            }
                            ?>
                        </ul>
                    </nav>
                    <!-- النهايه -->
                    <?php
                    echo '  
                            </div>
                        </div>
                    ';
                }
                ?>
                <!--END خاص بعرض المنتجات التي طلبها العضو -->

            </div>
        </article>
        <!-- End article -->

    </div>
</div>

<?php include_once 'include/footer.php'; ?>