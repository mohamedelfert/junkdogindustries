<?php
include 'session.php';
include 'include/header.php';

$select_setting     = $conn->query("SELECT * FROM settings");
$row_select_setting = $select_setting->fetch(PDO::FETCH_OBJ);
?>

<!-- Start container -->
<div class="container" style="margin-top: 30px;">
    <div class="row">

        <!-- start sidbar -->
        <?php include 'include/sidbar.php'; ?>
        <!-- end sidbar -->

        <!-- start body -->
        <div class="col-lg-9">

            <div class="col-lg-3" style="float:right;background-color: #fff;margin-top:2%">
                <img src="../<?php echo $row_select_setting->site_logo; ?>" width="100%" max-width="150px" class="img-thumbnail">
            </div>

            <div class="col-lg-9" style="float:left">

                <!-- Container -->
                <div class="limiter col-lg-12">
                    <div class="container-login100" style="background-color: #fff">
                        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width: 600px">
                            <form action="" method="post" class="login100-form validate-form">
                                <span class="login100-form-title p-b-33" style="text-decoration: underline">
                                    Site Settings
                                </span>

                                <label><span style="color: red">* </span>Site Name :</label>
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" name="name" value="<?php echo $row_select_setting->site_name; ?>" id="site_name" placeholder="Website Name Here">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label>Site Logo :</label>
                                <div class="wrap-input100 validate-input">
                                    <input type="file" name="logo" class="form-control" id="site_logo" style="height: 100%;">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label> Slide Show :</label>
                                <div class="wrap-input100 validate-input">
                                    <select name="slide" class="form-control">
                                        <option value="0">Select Catogary</option>
                                    </select>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label><span style="color: red">* </span>Product Number :</label>
                                <div class="wrap-input100 validate-input">
                                    <input type="number" class="form-control" id="slide_num" name="slide_num" value="<?php echo ($row_select_setting->slide_value == '' ? '3' : $row_select_setting->slide_value); ?>" min="3" max="10">
                                </div>

                                <label>Facebook :</label>
                                <div class="wrap-input100 rs1 validate-input">
                                    <input type="text" class="form-control" name="facebook" value="<?php echo $row_select_setting->facebook; ?>" id="facebook" placeholder="أدخل رابط الفيس بوك هنا">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label>Twitter :</label>
                                <div class="wrap-input100 rs1 validate-input">
                                    <input type="text" class="form-control" name="twitter" value="<?php echo $row_select_setting->twitter; ?>" id="twitter" placeholder="أدخل رابط تويتر هنا">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <label>Instegram :</label>
                                <div class="wrap-input100 rs1 validate-input">
                                    <input type="text" class="form-control" name="instegram" value="<?php echo $row_select_setting->instegram; ?>" id="instegram" placeholder="أدخل رابط انستجرام هنا">
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>

                                <div class="container-login100-form-btn m-t-20">
                                    <input type="submit" name="send" value="Update" class="login100-form-btn">
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