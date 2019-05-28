<?php
    require "include/connect.php";
    require "include/header.php";

////////////////////////////////////////////////////////////////////

$select_setting     = $conn->query("SELECT * FROM settings");
$row_select_setting = $select_setting->fetch(PDO::FETCH_OBJ);

////////////////////////////////////////////////////////////////////////////

$sql_info_admin     = $conn->query("SELECT * FROM clints where role = 'admin' and clint_id = 1");
$row_sql_info_admin = $sql_info_admin->fetch(PDO::FETCH_OBJ);

///////////////////////////////////////////////
if (isset($_POST['send'])){
    $name      = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $email     = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $subject   = filter_var($_POST['subject'],FILTER_SANITIZE_STRING);
    $message   = filter_var($_POST['message'],FILTER_SANITIZE_STRING);
    @$clint_id = (int)$_SESSION['id'];
    $m_date    = date('Y-m-d : h-i-sa');
    $errors    = array();

    if (empty($name)){
        $errors[] = "من فضلك أدخل إسمك";
    }elseif (empty($email)){
        $errors[] = "من فضلك أدخل البريد الالكتروني";
    }elseif (empty($subject)){
        $errors[] = "من فضلك أدخل عنوان الرساله";
    }elseif (empty($message)){
        $errors[] = "من فضلك أدخل رسالتك !!!!";
    }else{
        if (isset($_SESSION['id'])){
            $sql   = "INSERT INTO enquiries (name,email,subject,message,clint_id,m_date) VALUES ('$name','$email','$subject','$message','$clint_id','$m_date')";
            $stmnt = $conn->query($sql);
            $success = "تم إرسال رسالتك بنجاح";
        }else{
            $sql   = "INSERT INTO enquiries (name,email,subject,message,m_date) VALUES ('$name','$email','$subject','$message','$m_date')";
            $stmnt = $conn->query($sql);
            $success = "تم إرسال رسالتك بنجاح";
        }
    }
}
?>

<section class="page-section spad contact-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5 mb-lg-0">
                <h4 class="comment-title">Contact us</h4>
                <p><?php echo $row_select_setting->about_us; ?></p>
                <div class="row">
                    <div class="col-md-9">
                        <ul class="contact-info-list">
                            <li><div class="cf-left">Address</div><div class="cf-right"><?php echo $row_sql_info_admin->address; ?></div></li>
                            <li><div class="cf-left">Phone 1</div><div class="cf-right"><?php echo $row_sql_info_admin->mobail_1; ?></div></li>
                            <li><div class="cf-left">Phone 2</div><div class="cf-right"><?php echo $row_sql_info_admin->mobail_2; ?></div></li>
                            <li><div class="cf-left">E-mail</div><div class="cf-right"><?php echo $row_sql_info_admin->email; ?></div></li>
                        </ul>
                    </div>
                </div>
                <div class="social-links">
                    <a href="<?php echo $row_select_setting->facebook; ?>"><i class="fa fa-facebook"></i></a>
                    <a href="<?php echo $row_select_setting->twitter; ?>"><i class="fa fa-twitter"></i></a>
                    <a href="<?php echo $row_select_setting->instegram; ?>"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="contact-form-warp">
                    <?php
                    if (isset($success)){
                        echo " <div class='aler alert-success role=alert' style='text-align:center;color:#2054ff;font-size:30px;padding:10px;margin-bottom:10px'>
                            <b> $success </b>
                         </div>";
                        echo '<meta http-equiv="refresh" content="2; \'contact.php\'">';
                    }

                    if (isset($errors)){
                        foreach ($errors as $error){
                            echo" <div class='aler alert-danger role=alert' style='text-align:center;color:red;font-size:30px;padding:10px;margin-bottom:10px'>
                            <b> $error </b>
                         </div>";
                        }
                    }
                    ?>
                    <h4 class="comment-title">Leave a Message :</h4>
                    <form action="" method="post" class="comment-form">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Your Name" value="<?php if (isset($_SESSION['id'])){echo $_SESSION['fullname'];} ?>">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" placeholder="Your Email" value="<?php if (isset($_SESSION['id'])){echo $_SESSION['email'];} ?>">
                            </div>
                            <div class="col-lg-12">
                                <input type="text" name="subject" placeholder="Subject">
                                <textarea name="message" placeholder="Message"></textarea>
                                <input type="submit" name="send" value="Send" class="site-btn btn-sm">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Page section end -->


<div class="clearfix"></div>

<?php
    require "include/footer.php";
?>


