<?php
    require "include/connect.php";
    require "include/header.php";
?>


<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Wibsite Name And Logo -->
    <div class="col-lg-3">
      <h1 class="my-4 h">JDIndustries</h1>
      <div class="list-group">
        <img src="<?php echo $row_select_setting->site_logo; ?>" height="250" alt="Logo">
      </div>
      <!-- Wibsite Name And Logo End -->

      <hr>

      <!-- Asid Bar ( login Panel ) -->
        <?php include "include/sidbar.php"; ?>
      <!-- Asid Bar ( login Panel ) End -->

    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

      <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
            <?php
            $sql_pro   = $conn->query("SELECT * FROM products WHERE status = 'Published' AND custom = 0 order by product_id desc LIMIT 3");
            $sql_count = $sql_pro->rowCount();
            if ($sql_count > 0) {
                while ($row_sql_info = $sql_pro->fetch(PDO::FETCH_OBJ)) {
            ?>
                <div class="carousel-item active">
                    <img class="d-block img-fluid" src="<?php echo $row_sql_info->image; ?>" width="900" height="350">
                </div>
            <?php
                }
            }else{
                echo '
                    <div class="carousel-item active">
                        <img class="d-block img-fluid" src="images/HTML5.png" width="900" height="350" alt="First slide">
                    </div>
                ';
            }
            ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

      <hr>

      <div class="row">

          <?php
          /*الجزء دا خاص لعمل تعدد للصفحات للمنتجات*/
          $per_pro = 5; /*عدد المنتجات اللي عايزها تظهر في الصفحه*/
          /*هنا باتاكد ان مفيش متغير اسمه page جاي في الرابط ولا لا*/
          if (!isset($_GET['page'])){
          $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
          }else{
          $page = (int)$_GET['page']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
          }
          $start_from = ($page - 1) * $per_pro;
          /***********************************************/

          $select_pro_info = $conn->query("SELECT * FROM products WHERE status = 'Published' AND custom = 0 ORDER BY product_id LIMIT $start_from,$per_pro");
          $pro_count = $select_pro_info->rowCount();
          if ($pro_count > 0) {
              while ($row_pro_info = $select_pro_info->fetch(PDO::FETCH_OBJ)) {
                  ?>
                  <div class="col-lg-4 col-md-6 mb-4">
                      <div class="card h-100">
                          <a href="item.php?pro_id=<?php echo $row_pro_info->product_id; ?>"><img
                                      class="card-img-top" src="<?php echo $row_pro_info->image; ?>"
                                      alt="product image" width="100%" height="200"></a>
                          <hr>
                          <div class="card-body" style="padding-top:0">
                              <h5 class="card-title" style="float:left;">
                                  <?php echo $row_pro_info->product_name; ?>
                              </h5>
                              <h5 class="card-title" style="float:right;">
                                  $ <?php echo $row_pro_info->price; ?></h5>
                              <div class="clearfix"></div>
                              <hr>
                              <p class="card-text"><?php echo substr($row_pro_info->notes, 0, 40); ?></p>
                              <hr/>
                              <div class="text-left">
                                  <a href="item.php?pro_id=<?php echo $row_pro_info->product_id; ?>"
                                     class="btn btn-warning btn-sm">&rarr; Read More</a>
                              </div>
                          </div>
                      </div>
                  </div>
                  <?php
              }
          }else{
              echo '<div class="alert alert-danger text-center col-lg-12" role="alert" style="font-size: 20px;"><b>لا يوجد منتجات معروضة للبيع حاليا :(</b></div>';
          }
          ?>

      </div>
      <!-- /.row -->

        <?php
        /* باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات التعليقات*/
        $products       = $conn->query("SELECT * FROM products where custom = 0"); /*ال products اللي عندي كلها*/
        $count_products = $products->rowCount();
        $total_pages    = ceil($count_products / $per_pro); /*عدد الصفحات اللي هتتقسم بناء علي عدد products اللي عندي*/
        ?>
        <nav>
            <ul style="margin:20px 0;">
                <?php
                for ($i = 1 ; $i <= $total_pages ; $i++){
                    echo' <li '.($page == $i ? 'class="active"' : 'style="display:inline;border:1px solid #000;background-color:#fff;padding:15px;border-radius:20%;margin-left:5px"').' style="display:inline;border:1px solid #0089ff9c;background-color:#0089ff9c;margin-left:5px;padding:15px;border-radius:20%">
                              <a href="index.php?page='.$i.'">'.$i.'</a>
                            </li>';
                }
                ?>
            </ul>
        </nav>
        <!-- النهايه -->

    </div>
    <!-- /.col-lg-9 -->

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

<?php require "include/footer.php" ?>
