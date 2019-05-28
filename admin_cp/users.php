<?php
include 'session.php';
include 'include/header.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/

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
    header("refresh:1; users.php");

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

                <!-- الديف دا خاص بالجزء بتاع عرض الأعضاء -->
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>All Users</b></div>
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

                                $per_users = 3;
                                if (!isset($_GET['page'])){
                                    $page = 1;
                                }else{
                                    $page = intval($_GET['page']);
                                }
                                $start_from = ($page - 1) * $per_users;

                                $select_clint       = $conn->query("SELECT * FROM clints ORDER BY clint_id DESC LIMIT $start_from,$per_users");
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
                                                            <td>'.($row_select_clint->role == "admin" ? '<a href="users.php?box=user&clint_id='.$row_select_clint->clint_id.'" class="btn btn-info btn-xs">user</a>' : '<a href="users.php?box=admin&clint_id='.$row_select_clint->clint_id.'" class="btn btn-success btn-xs">admin</a>' ).'</td>
                                                            <td><a href="users.php?box=delete_user&user_id='.$row_select_clint->clint_id.'" class="btn btn-danger btn-xs">Delete</a></td>
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

                                <?php
                                $users       = $conn->query("SELECT * FROM clints");
                                $count_users = $users->rowCount();
                                $total_pages = ceil($count_users / $per_users);
                                ?>
                                <nav class="text-center">
                                    <ul style="margin:20px 0;">
                                        <?php
                                        for ($i = 1 ; $i <= $total_pages ; $i++){
                                            echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                                                    <a href="users.php?page='.$i.'">'.$i.'</a>
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