<?php
    require "include/connect.php";
    require "include/header.php";
?>

<div class="container">
    <div class="row">

        <!-- Asid Bar ( login Panel ) -->
        <div class="col-lg-3">

            <!-- Asid Bar ( login Panel ) -->
            <?php include "include/sidbar.php"; ?>
            <!-- Asid Bar ( login Panel ) End -->

        </div>
        <!-- Asid Bar ( login Panel ) End -->

        <!-- Container -->
        <div class="limiter col-lg-9">
            <div class="container-login100" style="background-color: #fff">
                <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width: 600px">

                    <!-- Function Request -->
                    <?php request(); ?>
                    <!-- Function Request End -->

                </div>
            </div>
        </div>
        <!-- Container  End-->

    </div>
</div>

<?php
    require "include/footer.php";
?>