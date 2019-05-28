<?php
include 'session.php';
include 'include/header.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/

/*استعلام عشان اجيب عدد المنتجات اللي عندي في الداتا بيس*/
$product        = $conn->query("select * from products where custom = 0");
$product_count  = $product->rowCount();
/*End*/

/*استعلام عشان اجيب عدد التعليقات اللي عندي في الداتا بيس*/
$comments       = $conn->query("SELECT * FROM comments");
$comment_count  = $comments->rowCount();
/*End*/

/*استعلام عشان اجيب عدد الأعضاء اللي عندي في الداتا بيس*/
$clint       = $conn->query("SELECT * FROM clints");
$clint_count = $clint->rowCount();
/*End*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
    header("refresh:1; index.php");
}
/*END*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*خاص بتغير حالة العضو من user الي admin أو العكس*/
if (@$_GET['box'] === 'admin'){
    $clint_id = intval($_GET['clint_id']) ; /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $sql  = $conn->query("UPDATE clints SET role = '$_GET[box]' WHERE clint_id = '$clint_id'");
}elseif(@$_GET['box'] === 'user'){
    $clint_id = intval($_GET['clint_id']) ; /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $sql  = $conn->query("UPDATE clints SET role = '$_GET[box]' WHERE clint_id = '$clint_id'");
}
/*END*/

/*خاص بحذف الأعضاء*/
if (@$_GET['box'] === 'delete_user'){
    $user_id     = intval($_GET['user_id']) ; /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $delete_user = $conn->query("DELETE FROM clints WHERE clint_id = '$user_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف العضو بنجاح </b></div>';
    header("refresh:1; index.php");
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

            <div class="col-lg-12">
                <div class="row">
                    <!-- الديف دا خاص بعرض بيانات المستخدم -->
                    <div class="col-md-3">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Welcome </b> { <?php echo $_SESSION['username']; ?> }</div>
                            <div class="panel-body">
                                <div style="text-align:center;margin-top:10px;">
                                    <img src="../<?php echo $_SESSION['photo']; ?>" width="40%" max-width="150px" class="img-thumbnail">
                                    <hr>
                                </div>
                                <div style="padding: 0 5px;">
                                    <p><b> Email : <?php echo $_SESSION['email']; ?> </b></p>
                                    <p><b> Role : <?php echo $_SESSION['role']; ?> </b></p>
                                    <hr>
                                    <p class="text-center"><a href="<?php echo'edite_user.php?id=';echo $_SESSION["id"];?>" class="btn btn-danger btn-sm"><b>Edite User</b></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End-->

                    <!-- الديف دا خاص بعرض عدد المنتجات -->
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center"><b>Products</b></div>
                            <div class="panel-body" style="padding: 10px 0;">
                                <div class="col-md-8" style="float:left;font-size:10px;">
                                    <i class="fa fa-list-alt fa-5x" style="color: #31708F"></i>
                                </div>
                                <div class="col-md-4" style="float:right">
                                    <p><b><?php echo $product_count; ?></b></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="products.php"><b>View</b></a></div>
                        </div>
                    </div>
                    <!--End-->

                    <!-- الديف دا خاص بعرض عدد التعليقات -->
                    <div class="col-md-3">
                        <div class="panel panel-danger">
                            <div class="panel-heading text-center"><b>Comments</b></div>
                            <div class="panel-body" style="padding: 10px 0;">
                                <div class="col-md-8" style="float:left;font-size:10px;">
                                    <i class="fa fa-comments-o fa-5x" style="color: #A94442"></i>
                                </div>
                                <div class="col-md-4" style="float:right">
                                    <p><b><?php echo $comment_count; ?></b></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="comments.php"><b>View</b></a></div>
                        </div>
                    </div>
                    <!--End-->

                    <!-- الديف دا خاص بعرض عدد الأعضاء -->
                    <div class="col-md-3">
                        <div class="panel panel-success">
                            <div class="panel-heading text-center"><b>Users</b></div>
                            <div class="panel-body" style="padding: 10px 0;">
                                <div class="col-md-8" style="float:left;font-size:10px;">
                                    <i class="fa fa-users fa-5x" style="color: #3C763D"></i>
                                </div>
                                <div class="col-md-4" style="float:right">
                                    <p><b><?php echo $clint_count; ?></b></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="users.php"><b>View</b></a></div>
                        </div>
                    </div>
                    <!--End-->

                    <!-- الديف دا خاص بالجزء بتاع عرض المنتجات -->
                    <div class="col-md-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading"><b>Latest Products Added</b></div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Product Name</th>
                                            <th>Date Added</th>
                                            <th>View Product</th>
                                            <th>Statue</th>
                                            <th>Edite</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_product   = $conn->query("SELECT * FROM products where custom = 0 ORDER BY product_id DESC LIMIT 5 ");
                                        $count_select_pro = $select_product->rowCount();
                                        if ($count_select_pro > 0){
                                            $num = 1;
                                            while ($row_select_pro = $select_product->fetch(PDO::FETCH_OBJ)){
                                                echo'
                                                    <tr>
                                                        <td>'.$num.'</td>
                                                        <td><img src="../'.$row_select_pro->image.'" class="img-rounded" width="80px" height="60px"></td>
                                                        <td>'.$row_select_pro->product_name.'</td>
                                                        <td></td>
                                                        <td><a href="../item.php?pro_id='.$row_select_pro->product_id.'" class="btn btn-primary btn-xs">View</a></td>
                                                        <td>'.($row_select_pro->status == "Published" ? '<a href="index.php?box=Disable&pro_id='.$row_select_pro->product_id.'" class="btn btn-info btn-xs">Disable</a>' : '<a href="index.php?box=Published&pro_id='.$row_select_pro->product_id.'" class="btn btn-success btn-xs">Published</a>' ).'</td>
                                                        <td><a href="edite_product.php?box=edite_pro&pro_id='.$row_select_pro->product_id.'" class="btn btn-warning btn-xs">Edite</a></td>
                                                        <td><a href="index.php?box=delete_pro&pro_id='.$row_select_pro->product_id.'" class="btn btn-danger btn-xs">Delete</a></td>
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
                            </div>
                        </div>
                    </div>
                    <!--End-->

                    <!-- الديف دا خاص بالجزء بتاع عرض الأعضاء -->
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><b>Latest Users</b></div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>UserName</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Profile</th>
                                            <th>Role</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_clint       = $conn->query("SELECT * FROM clints ORDER BY clint_id DESC LIMIT 5 ");
                                        $count_select_clint = $select_clint->rowCount();
                                        if ($count_select_clint > 0){
                                            $num = 1;
                                            while ($row_select_clint = $select_clint->fetch(PDO::FETCH_OBJ)){
                                                echo'
                                                    <tr>
                                                        <td>'.$num.'</td>
                                                        <td><img src="../'.$row_select_clint->photo.'" class="img-rounded" width="80px" height="60px"></td>
                                                        <td>'.$row_select_clint->username.'</td>
                                                        <td>'.$row_select_clint->email.'</td>
                                                        <td>'.($row_select_clint->gender == "male" ? '<img src="../images/male.png" width="40px">' : '<img src="../images/female.png" width="40px">').'</td>
                                                        <td><a href="../profile.php?user='.$row_select_clint->clint_id.'" class="btn btn-primary btn-xs">profile</a></td>
                                                        <td>'.($row_select_clint->role == "admin" ? '<a href="index.php?box=user&clint_id='.$row_select_clint->clint_id.'" class="btn btn-info btn-xs">user</a>' : '<a href="index.php?box=admin&clint_id='.$row_select_clint->clint_id.'" class="btn btn-success btn-xs">admin</a>' ).'</td>
                                                        <td><a href="index.php?box=delete_user&user_id='.$row_select_clint->clint_id.'" class="btn btn-danger btn-xs">Delete</a></td>
                                                    </tr>
                                                    ';
                                                $num++;
                                            }
                                        }else{
                                            echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لا يوجد أي أعضاء في الموقع حاليا :(</b></div>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End-->

                </div>
            </div>

        </div>
        <!-- start body -->

    </div>
</div>
<!-- End container -->

<?php include 'include/footer.php'; ?>