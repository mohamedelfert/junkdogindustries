<?php
    require "include/connect.php";
    require "include/header.php";

////////////////////////////////////////////////////////////////////

$select_setting     = $conn->query("SELECT * FROM settings");
$row_select_setting = $select_setting->fetch(PDO::FETCH_OBJ);

////////////////////////////////////////////////////////////////////////////

$sql_info_admin     = $conn->query("SELECT * FROM clints where role = 'admin' and clint_id = 1");
$row_sql_info_admin = $sql_info_admin->fetch(PDO::FETCH_OBJ);

/////////////////////////////////////////////////////////////////////////////

?>

        <!-- Section Start -->
        <section class="page-section spad contact-page">
            <div class="site-section">
                <div class="container">
                    <div class="row mb-5">

                        <div class="col-md-5 ml-auto mb-5 order-md-2 text-right" data-aos="fade">
                            <img src="<?php echo $row_select_setting->site_logo; ?>" alt="Site Logo" width="70%" class="img-fluid rounded">
                        </div>
                        <div class="col-md-6 order-md-1" data-aos="fade">
                            <div class="text-left pb-1 border-primary mb-4">
                                <h2 class="text-primary" style="text-decoration: underline">About Us</h2>
                            </div>

                            <p><?php echo $row_select_setting->about_us; ?></p>

                            <hr>

                            <div class="row">
                                <div class="col-md-12 mb-md-5 mb-0 col-lg-6">
                                    <div class="unit-4">
                                        <div>
                                            <h3>Email</h3>
                                            <p><?php echo $row_sql_info_admin->email; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-md-5 mb-0 col-lg-6">
                                    <div class="unit-4">
                                        <div>
                                            <h3>Phones</h3>
                                            <p><?php echo $row_sql_info_admin->mobail_1; ?></p>
                                            <p><?php echo $row_sql_info_admin->mobail_2; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="site-section bg-image overlay">
                        <div class="container">
                            <div class="row justify-content-center mb-5">
                                <div class="col-md-7 text-center border-primary">
                                    <h2 class="font-weight-light text-primary" data-aos="fade" style="text-decoration: underline">Some Of Our Business</h2>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                $select_pro_info = $conn->query("SELECT * FROM products where custom = 0 limit 3");
                                $pro_count = $select_pro_info->rowCount();
                                if ($pro_count > 0) {
                                    while ($row_pro_info = $select_pro_info->fetch(PDO::FETCH_OBJ)) {
                                ?>
                                        <div class="col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
                                            <div class="how-it-work-item">
                                                <div class="how-it-work-body">
                                                    <div class="col-md-13 ml-auto mb-5 order-md-2" data-aos="fade">
                                                        <img src="<?php echo $row_pro_info->image; ?>" alt="Image" style="border:1px solid #000000" class="img-fluid rounded">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- section end -->


        <div class="clearfix"></div>


<?php
    require "include/footer.php";
?>