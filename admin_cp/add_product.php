<?php
include 'session.php';
include '../include/connect.php';
include 'include/header.php';

if (isset($_POST['add'])){
    $pro_name   = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $pro_price  = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
    $pro_status = $_POST['status'];
    $pro_nots   = filter_var($_POST['nots'],FILTER_SANITIZE_STRING);
    $pro_date   = date('Y-m-d : h-i-sa');
    $errors         = array();

    if (empty($pro_name)){
        $errors[] = "يجب إدخال إسم المنتج";
    }elseif (empty($pro_price)) {
        $errors[] = "يجب إدخال سعر المنتج";
    }else{
        $product_info = "SELECT * FROM products WHERE product_name = '$pro_name'";
        $product_info = $conn->query($product_info);
        $row_product  = $product_info->rowCount();
        if ($row_product > 0){
            $errors[] = "إسم هذا المنتج متواجد لدينا بالفعل يجب تغيير الإسم علي الأقل :)";
        }else{
            if (isset($_FILES['pro_image'])){
                $pro_image   = $_FILES['pro_image'];
                $image_name  = $pro_image['name'];
                $image_temp  = $pro_image['tmp_name'];
                $image_size  = $pro_image['size'];
                $image_error = $pro_image['error'];
                $image_exe   = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));
                $valid_exe   = array('png','jpeg','jpg','gif'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
                if (in_array($image_exe,$valid_exe)){
                    if ($image_error === 0){
                        if ($image_size <= 3000000 ){
                            $new_name  = uniqid('product','false') . "." . $image_exe;
                            $image_dir = "../images/products/" . $new_name;
                            $image_db  = "images/products/" . $new_name;
                            if (move_uploaded_file($image_temp,$image_dir)){
                                $insert = "insert into products (product_name,price,image,status,notes,custom,pro_date) 
                                                values ('$pro_name','$pro_price','$image_db','$pro_status','$pro_nots',0,'$pro_date')";
                                $stmnt = $conn->exec($insert);
                                $success = "تم إضافة المنتج بنجاح";
                            }else{
                                $errors[] = "نأسف حصل خطأ أثناء رفع الصوره :)";
                            }
                        }else{
                            $errors[] = "يجب أن يكون حجم الصورة 3 ميجا أو أقل :)";
                        }
                    }else{
                        $errors[] = "نأسف حصل خطأ أثناء رفع الصوره :)";
                    }
                }else{
                    $errors[] = "عذرا الصوره المرفقة يجب أن تكون بصيغة (png , jpeg , jpg , gif)";
                }
            }else{
                $insert = "insert into products (product_name,price,image,status,notes,custom,pro_date) 
                                values ('$pro_name','$pro_price','images/no-image-post.png','$pro_status','$pro_nots',0,'$pro_date')";
                $stmnt = $conn->exec($insert);
                $success = "تم إضافة المنتج بنجاح";
            }
        }
    }
}
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

                <!-- Container -->
                <div class="limiter col-lg-9">
                    <div class="container-login100" style="background-color: #fff">
                        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width: 600px">
                            <?php
                            if (isset($success)){
                                echo " <div class='aler alert-success role=alert' style='text-align:center;color:#2054ff;font-size:30px;padding:10px;margin-bottom:10px'>
                                        <b> $success </b>
                                     </div>";
                                echo '<meta http-equiv="refresh" content="1; \'index.php\'">';
                            }
                            if (isset($errors)){
                                foreach ($errors as $value){
                                    echo" <div class='aler alert-danger role=alert' style='text-align:center;color:red;font-size:30px;padding:10px;margin-bottom:10px'>
                                            <b> $value </b>
                                         </div>";
                                }
                            }
                            ?>
                            <form action="" method="post" class="login100-form validate-form" id="add_product" enctype="multipart/form-data">
                                        <span class="login100-form-title p-b-33" style="text-decoration: underline">
                                            Add New Product
                                        </span>

                                <label><span style="color: red">* </span>Name :</label>
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" name="name" placeholder="Product Name">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label><span style="color: red">* </span>Price :</label>
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" name="price" placeholder="Product Price">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label><span style="color: red">* </span> Product Photo :</label>
                                <div class="wrap-input100 validate-input">
                                    <input type="file" name="pro_image" class="form-control" id="pro_image" style="height: 100%;">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label>Product Status :</label>
                                <div class="wrap-input100 validate-input">
                                    <select name="status" class="form-control">
                                        <option value="Published">Published</option>
                                        <option value="Disable">Disable</option>
                                    </select>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label> Nots :</label>
                                <div class="wrap-input100 validate-input">
                                    <textarea name="nots" class="form-control" id="nots" rows="4" placeholder="Your Nots"></textarea>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <div class="container-login100-form-btn m-t-20">
                                    <input type="submit" name="add" value="Add Product" class="login100-form-btn">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Container  End-->

            </div>
        </div>
        <!-- start body -->

    </div>
</div>
<!-- End container -->

<?php include 'include/footer.php'; ?>
