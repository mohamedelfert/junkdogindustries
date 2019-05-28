<?php
include 'session.php';
include 'include/header.php';

/*خاص بحذف التعليقات*/
if (@$_GET['box'] == 'delete_comm'){

    $comm_id     = intval($_GET['comm_id']);
    $delete_comm = $conn->exec("DELETE FROM comments WHERE comm_id = '$comm_id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف التعليق بنجاح </b></div>';

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
                <div class="col-lg-12">

                    <!-- خاص بعرض بيانات البروفايل -->
                    <div class="panel">
                        <div class="panel-heading text-center"><b>المعلومات الشخصيه</b></div>
                        <div class="panel-body">
                            <div class="col-md-3" style="float:right;margin:15px 0;">
                                <img src="../<?php echo $_SESSION['photo']; ?>" class="img-thumbnail" width="50%">
                            </div>

                            <div class="col-md-9" style="margin:15px 0;padding:0 40px">
                                <p><b>UserName : </b> <?php echo $_SESSION['username']; ?> </p>
                                <p><b>Email : </b> <?php echo $_SESSION['email']; ?> </p>
                                <p><b>Phone 1 : </b> <?php echo $_SESSION['mobail_1']; ?> </p>
                                <p><b>Address : </b> <?php echo $_SESSION['address']; ?> </p>
                                <p><b>Role : </b> <?php if ($_SESSION['role'] == 'admin'){echo 'المدير العام';}else{echo 'عضو';} ?> </p>
                                <p><b>Gender : </b> <?php if ($_SESSION['gender'] == 'male'){echo '<img src="../images/male.png" width="45px">';}else{echo '<img src="images/female.png" width="45px">';} ?> </p>
                            </div>

                        </div>

                        <hr>

                        <div class="panel-footer" style="padding-bottom:15px;margin-bottom:10px">
                            <div class="col-md-12">
                                <a href="<?php echo'edite_user.php?id=';echo $_SESSION["id"];?>" class="btn btn-danger pull-left btn-sm">تعديل البيانات</a>
                            </div>
                        </div>

                    </div>
                    <!-- END خاص بعرض بيانات البروفايل -->

                    <!-- خاص بعرض التعليقات التي علقها العضو -->
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
                            $comments       = $conn->query("SELECT * FROM comments WHERE clint_id = '$_SESSION[id]'"); /*ال comments اللي عندي كلها*/
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

                        </div>
                    </div>
                    <!--END خاص بعرض التعليقات التي علقها العضو -->

                </div>
            </div>
            <!-- start body -->

        </div>
    </div>
    <!-- End container -->

<?php include 'include/footer.php'; ?>


