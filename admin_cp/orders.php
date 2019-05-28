<?php
include 'session.php';
include 'include/header.php';

$Msg = '';

/*خاص بحذف الطلبات*/
if (@$_GET['box'] == 'delete_order'){

    $order_id     = intval($_GET['order_id']);
    $delete_order = $conn->exec("DELETE FROM orders WHERE order_id = '$order_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف الطلب بنجاح </b></div>';
    header("refresh:1; orders.php");

}
/*END*/

?>

    <!-- Start container -->
    <div class="container" style="margin-top: 30px;">
        <div class="row">

            <!-- start sidbar -->
            <?php include 'include/sidbar.php'; ?>
            <!-- end sidbar -->

            <!-- start body -->
            <div class="col-lg-9">

                <?php echo $Msg; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->

                <!-- الديف دا خاص بالجزء بتاع عرض الطلبات -->
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>All Orders</b></div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>User Ordered</th>
                                        <th>Date Ordered</th>
                                        <th>View Product</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
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

                                $select_order  = $conn->query("SELECT * FROM order_pro_clint ORDER BY order_id DESC LIMIT $start_from , $per_order");
                                $order_count = $select_order->rowCount();
                                if ($order_count > 0){
                                    $num = 1;
                                    while ($row_select_order = $select_order->fetch(PDO::FETCH_OBJ)){
                                        echo'
                                                <tr>
                                                    <td>'.$num.'</td>
                                                    <td>'.substr($row_select_order->product_name,0,70).'</td>
                                                    <td>'.$row_select_order->price.'</td>
                                                    <td>'.$row_select_order->username.'</td>
                                                    <td>'.$row_select_order->order_date.'</td>
                                                    <td><a href="../item.php?pro_id='.$row_select_order->pro_id.'" class="btn btn-primary btn-xs">View</a></td>
                                                    <td><a href="orders.php?box=delete_order&order_id='.$row_select_order->order_id.'" class="btn btn-danger btn-xs">Delete</a></td>
                                                </tr>
                                            ';
                                        $num++;
                                    }
                                }else{
                                    echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لا يوجد أي طلبات لمنتجات في الموقع حاليا :(</b></div>';
                                }
                                ?>
                                </tbody>
                            </table>

                            <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات التعليقات -->
                            <?php
                            $orders       = $conn->query("SELECT * FROM orders"); /*ال comments اللي عندي كلها*/
                            $count_orders = $orders->rowCount();
                            $total_pages    = ceil($count_orders / $per_order); /*عدد الصفحات اللي هتتقسم بناء علي عدد comments اللي عندي*/
                            ?>
                            <nav class="text-center">
                                <ul style="margin:20px 0;">
                                    <?php
                                    for ($i = 1 ; $i <= $total_pages ; $i++){
                                        echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                                                <a href="orders.php?page='.$i.'">'.$i.'</a>
                                              </li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <!-- النهايه -->

                        </div>
                    </div>
                </div>
                <!--End-->

            </div>
            <!-- start body -->

        </div>
    </div>
    <!-- End container -->

<?php include 'include/footer.php'; ?>