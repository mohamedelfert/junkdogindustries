<?php
require "include/connect.php";
require "include/header.php";

$Msg = '';

$pro_id          = intval($_GET['pro_id']);
$select_pro_info = $conn->query("SELECT * FROM products WHERE product_id = '$pro_id'");
$row_pro_info    = $select_pro_info->fetch(PDO::FETCH_OBJ);

/******************************************************************/
if (isset($_POST['order'])) {
    $clint_id = $_SESSION['id'];
    $quantity = $_POST['quantity'];
    $date = date('y-m-d : h-i-s');

    $order_product = $conn->query("insert into orders (pro_id,clint_id,quantity,order_date) 
                                                         values ('$pro_id','$clint_id','$quantity','$date')");
    if (isset($order_product)){
        echo '<div class="alert alert-success text-center" role="alert"><b>تم طلب المنتج سيتم التواصل معك</b></div>';
    }
    header("refresh:2;");
}
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

      <div class="card mt-4">
        <img class="card-img-top img-fluid" src="<?php echo $row_pro_info->image; ?>" style="max-height:400px" alt="product image">
        <hr>
        <div class="card-body">
          <h3 class="card-title"><?php echo $row_pro_info->product_name; ?></h3>
          <h4> $ <?php echo $row_pro_info->price; ?></h4>
          <p class="card-text"><?php echo $row_pro_info->notes; ?></p>
          <div>
            <span class="text-warning" style="float:left;">&#9733; &#9733; &#9733; &#9733; &#9734; <span style="color:#000">4.0 stars</span></span>

            <!-- Popup Order item -->
            <?php
            if (!isset($_SESSION['id'])) {
                echo '
                    <div>
                      <div id="abc">
                          <!-- Popup Div Starts Here -->
                          <div id="popupOrder">
                              <img id="close_order" src="images/close1.png" width="15" onclick="div_hide()">
                              <div class="alert alert-danger text-center" role="alert">
                                <b>لطلب المنتج سجل دخولك أولا :( </b><small>لعمل حساب جديد <a href="register.php">اضغط هنا</a></small>
                              </div>
                          </div>
                          <!-- Popup Div Ends Here -->
                      </div>
                      <!-- Display Popup Button -->
                      <button id="order_popup" onclick="div_show()">&rarr; Order</button>
                    </div>
                ';
            }else{
                echo '
                    <div>
                      <div id="abc">
                          <!-- Popup Div Starts Here -->
                          <div id="popupOrder">
                              <form action="" method="post" class="form_order">
                                  <img id="close_order" src="images/close1.png" width="15" onclick="div_hide()">
        
                                  <label class="label">Product Name : '.$row_pro_info->product_name.'</label>
        
                                  <label class="label">Product Price : '.$row_pro_info->price.'</label>
        
                                  <hr class="order_hr">
        
                                  <div style="float:left">
                                      <label>Quantity :</label>
                                      <input type="number" name="quantity" value="1" min="1" max="10" style="padding-left:5px" required> 
                                  </div> 
                                  
                                  <div style="float:right">
                                      <input type="submit" name="order" value="Order" class="submit">
                                  </div>
                              </form>
                          </div>
                          <!-- Popup Div Ends Here -->
                      </div>
                      <!-- Display Popup Button -->
                      <button id="order_popup" onclick="div_show()">&rarr; Order</button>
                    </div>
                ';
            }
            ?>
            <!-- END Popup Order item -->

          </div>
        </div>
      </div>
      <!-- /.card -->

      <!-- Product Reviews / comments -->
      <div class="card card-outline-secondary my-4">
        <div class="card-header">
          Product Reviews
        </div>

        <div class="card-body">

            <!-- comment area show -->
            <div class="col-md-12">
                <?php
                $select_comm   = $conn->query("SELECT * FROM comments JOIN clints ON comments.clint_id = clints.clint_id WHERE status = 'Published' AND pro_id = '$pro_id' ORDER BY comm_id DESC LIMIT 5");
                $comment_count = $select_comm->rowCount();
                if ($comment_count > 0){
                    while ($row_comm = $select_comm->fetch(PDO::FETCH_OBJ)) {
                ?>
                        <div class="cat_post">
                            <div class="col-md-2" style="float:left;">
                                <img src="<?php echo $row_comm->photo; ?>" width="100%">
                            </div>
                            <div class="col-md-10" style="float:right;">
                                <h2 class="cat_h2"><i class="fa fa-comment" aria-hidden="true"></i> <?php echo $row_comm->title; ?> </h2>
                                <p><?php echo $row_comm->content; ?></p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <hr style="margin-bottom: 10px;margin-top: 0;">
                                <p class="pull-right" style="margin-bottom: 0;"><i class="fa fa-user" aria-hidden="true"></i> Reviewed By :
                                    <a href="profile.php?user=<?php echo $row_comm->clint_id; ?>"><?php echo $row_comm->username; ?></a></p>
                                <p class="pull-left" style="margin-bottom: 0;"> <?php echo $row_comm->comm_date; ?> <i class="fa fa-clock-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                <?php
                    }
                }else{
                    echo'<div class="alert alert-danger text-center" role="alert"><b>:( لا يوجد أي تعليقات حاليا علي المنتج </b></div>';
                }
               ?>
            </div>
            <!-- comment area show -->

            <hr>

            <!-- form comment -->
            <div class="col-lg-12 art_bg" style="margin-top: 25px;padding-top: 15px">
                <h2><i class="fa fa-comments" aria-hidden="true"></i> Add A Product Comment </h2>
                <hr>
                <?php comment_area(); ?>
            </div>
            <!-- form comment -->

        </div>
      </div>
        <!-- END Product Reviews / comments -->

    </div>
    <!-- /.col-lg-9 -->

  </div>

</div>
<!-- /.container -->

<?php require "include/footer.php"; ?>
