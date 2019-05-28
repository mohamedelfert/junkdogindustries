<?php
include 'session.php';
include '../include/connect.php';
include 'include/header.php';

if (isset($_POST['edit_product'])){
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
        $pro_image   = $_FILES['pro_image'];
        $image_name  = $pro_image['name'];
        $image_temp  = $pro_image['tmp_name'];
        $image_size  = $pro_image['size'];
        $image_error = $pro_image['error'];
        if ($image_name != ''){
            $image_exe   = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));
            $valid_exe   = array('png','jpeg','jpg','gif'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
            if (in_array($image_exe,$valid_exe)){
                if ($image_error === 0){
                    if ($image_size <= 3000000 ){
                        $new_name  = uniqid('product','false') . "." . $image_exe;
                        $image_dir = "../images/products/" . $new_name;
                        $image_db  = "images/products/" . $new_name;
                        if (move_uploaded_file($image_temp,$image_dir)){
                            $update = "update products set 
                                                          product_name = '$pro_name',
                                                          price        = '$pro_price',
                                                          image        = '$image_db',
                                                          status       = '$pro_status',
                                                          notes        = '$pro_nots',
                                                          custom       = 0,
                                                          pro_date     = '$pro_date'
                                                    where 
                                                          product_id = '$_GET[pro_id]'";
                            $stmnt = $conn->exec($update);
                            $success = "تم تحديث بيانات المنتج بنجاح";
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
            $update = "update products set 
                                          product_name = '$pro_name',
                                          price        = '$pro_price',
                                          status       = '$pro_status',
                                          notes        = '$pro_nots',
                                          custom       = 0,
                                          pro_date     = '$pro_date'
                                    where 
                                          product_id = '$_GET[pro_id]'";
            $stmnt = $conn->exec($update);
            $success = "تم تحديث بيانات المنتج بنجاح";
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
            <?php

            $sql_pro_info = $conn->query("SELECT * FROM products WHERE product_id = '$_GET[pro_id]'");
            $row_sql_pro  = $sql_pro_info->fetch(PDO::FETCH_OBJ);

            if (isset($success)){
                echo " <div class='aler alert-success role=alert' style='text-align:center;color:#2054ff;font-size:30px;padding:10px;margin-bottom:10px'>
                                        <b> $success </b>
                                      </div>";
                echo '<meta http-equiv="refresh" content="1; \'products.php\'">';
            }
            if (isset($errors)){
                foreach ($errors as $value){
                    echo" <div class='aler alert-danger role=alert' style='text-align:center;color:red;font-size:30px;padding:10px;margin-bottom:10px'>
                                            <b> $value </b>
                                          </div>";
                }
            }
            ?>
            <div class="col-lg-3" style="float:right;background-color: #fff;margin-top:2%">
                <img src="../<?php echo $row_sql_pro->image; ?>" width="100%" max-width="150px" class="img-thumbnail">
            </div>

            <div class="col-lg-9" style="float:left">

                <!-- Container -->
                <div class="limiter col-lg-12">
                    <div class="container-login100" style="background-color: #fff">
                        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width: 600px">
                            <form action="" method="post" class="login100-form validate-form" id="edit_product" enctype="multipart/form-data">
                                        <span class="login100-form-title p-b-33" style="text-decoration: underline">
                                            Update / Edit Product
                                        </span>

                                <label><span style="color: red">* </span>Name :</label>
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" name="name" value="<?php echo $row_sql_pro->product_name; ?>" placeholder="Product Name">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label><span style="color: red">* </span>Price :</label>
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" name="price" value="<?php echo $row_sql_pro->price; ?>" placeholder="Product Price">
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
                                        <option value="Published" <?php if ($row_sql_pro->status == 'Published'){echo 'selected';} ?> >Published</option>
                                        <option value="Disable" <?php if ($row_sql_pro->status == 'Disable'){echo 'selected';} ?> >Disable</option>
                                    </select>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label> Nots :</label>
                                <div class="wrap-input100 validate-input">
                                    <textarea name="nots" class="form-control" id="nots" rows="3"> <?php echo $row_sql_pro->notes; ?> </textarea>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <div class="container-login100-form-btn m-t-20">
                                    <input type="submit" name="edit_product" value="Edit Product" class="login100-form-btn">
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
