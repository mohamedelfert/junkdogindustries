<?php
include 'session.php';
include 'include/header.php';

$Msg = '';

/*خاص بتغير حالة المنتج من Published الي Disable*/
if (@$_GET['box'] === 'Published'){

    $pro_id = intval($_GET['pro_id']) ; /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query  = $conn->query("UPDATE products SET status = '$_GET[box]' WHERE product_id = '$pro_id'");

}elseif(@$_GET['box'] === 'Disable'){

    $pro_id = intval($_GET['pro_id']) ; /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query  = $conn->query("UPDATE products SET status = '$_GET[box]' WHERE product_id = '$pro_id'");

}
/*END*/

/*خاص بحذف المنتجات*/
if (@$_GET['box'] === 'delete_pro'){

    $pro_id     = intval($_GET['pro_id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $delete_pro = $conn->query("DELETE FROM products WHERE product_id = '$pro_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف المنتج بنجاح </b></div>';
    header("refresh:1; products.php");

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

                <!-- الديف دا خاص بالجزء بتاع عرض المنتجات -->
                <div class="col-md-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading"><b>All Products</b></div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Custom</th>
                                    <th>Date</th>
                                    <th>View Product</th>
                                    <th>Statue</th>
                                    <th>Edite</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $per_pro = 3;
                                if (!isset($_GET['page'])){
                                    $page = 1;
                                }else{
                                    $page = (int)$_GET['page'];
                                }
                                $start_from = ($page - 1) * $per_pro;

                                $select_product   = $conn->query("SELECT * FROM products where custom = 0 ORDER BY product_id DESC LIMIT $start_from,$per_pro");
                                $count_select_pro = $select_product->rowCount();
                                if ($count_select_pro > 0){
                                    $num = 1;
                                    while ($row_select_pro = $select_product->fetch(PDO::FETCH_OBJ)){
                                        echo'
                                                        <tr>
                                                            <td>'.$num.'</td>
                                                            <td><img src="../'.$row_select_pro->image.'" class="img-rounded" width="80px" height="60px"></td>
                                                            <td>'.$row_select_pro->product_name.'</td>
                                                            <td>'.$row_select_pro->custom.'</td>
                                                            <td>'.$row_select_pro->pro_date.'</td>
                                                            <td><a href="../item.php?pro_id='.$row_select_pro->product_id.'" class="btn btn-primary btn-xs">View</a></td>
                                                            <td>'.($row_select_pro->status == "Published" ? '<a href="products.php?box=Disable&pro_id='.$row_select_pro->product_id.'" class="btn btn-info btn-xs">Disable</a>' : '<a href="products.php?box=Published&pro_id='.$row_select_pro->product_id.'" class="btn btn-success btn-xs">Published</a>' ).'</td>
                                                            <td><a href="edite_product.php?box=edite_pro&pro_id='.$row_select_pro->product_id.'" class="btn btn-warning btn-xs">Edite</a></td>
                                                            <td><a href="products.php?box=delete_pro&pro_id='.$row_select_pro->product_id.'" class="btn btn-danger btn-xs">Delete</a></td>
                                                        </tr>
                                                    ';
                                        $num++;
                                    }
                                }else{
                                    echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لا يوجد أي منتجات في الموقع حاليا :(</b></div>';
                                }
                                ?>
                                </tbody>
                            </table>

                                <?php
                                $products    = $conn->query("SELECT * FROM products where custom = 0");
                                $count_pro   = $products->rowCount();
                                $total_pages = ceil($count_pro / $per_pro);
                                ?>
                                <nav class="text-center">
                                    <ul style="margin:20px 0;">
                                        <?php
                                        for ($i = 1 ; $i <= $total_pages ; $i++){
                                            echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                                                        <a href="products.php?page='.$i.'">'.$i.'</a>
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