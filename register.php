<?php
    require "include/connect.php";
    require "include/header.php";
?>
    
    <!-- Container -->
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="width: 550px">
                <?php register(); ?>
            </div>
        </div>
    </div>
    <!-- Container  End-->

<?php
    require "include/footer.php";
?>