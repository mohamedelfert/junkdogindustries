<?php

/* هنا الاستعلام ده عشان اجيب بيانات المحافظة من الداتا بيس واجيبها في select box في register*/
include 'connect.php';
// To catch cities from database for show in select group
$select_cities = "select * from cities order by id asc";
$select_cities = $conn->query($select_cities);

///////////////////////////////////////////////////////////////////////////////////////////////////

@$sql = $conn->query("SELECT * FROM clints join cities on clints.city_id = cities.id WHERE clint_id = '$_SESSION[id]'");
$row = $sql->fetch(PDO::FETCH_OBJ);

//////////////////////////////////////////////////////////////////////////////////////////////////////

/* هنا بدأت function خاصة ب login */
function login(){

    global $row;

    if (isset($_SESSION['id'])){
        echo'
            <aside>
                <div class="row">
                    <div class="panel">

                        <div class="panel-heading text-center"><b>Welcome : '.$_SESSION['fullname'].'</b></div>
                        <div class="panel-body">
                            <div class="text-center img">
                                <img src="'.$_SESSION['photo'].'" " width="85px" style="border-radius:20%;">
                            </div>
        
                            <hr>
        
                            <div class="col-md-12">
                                <p><b>Email : </b>'.$_SESSION['email'].'</p>
                                <p><b>Mobail 1 : </b>'.$_SESSION['mobail_1'].'</p>
                                <p><b>Address : </b>'.$_SESSION['address'].'</p>
                                <p><b>City : </b>'.$row->name.'</p>
                                <p><b>Gender : </b>'.$_SESSION['gender'].'</p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="panel-footer">
                            <div class="col-md-12">
                            ';
                            if ($_SESSION['role'] === 'admin'){ /*هنا لو العضو دا ليه صلاحيه admin هيظهرله الجزء دا */
                                echo '<a href="admin_cp/index.php" class="btn btn-danger pull-center btn-sm">Control</a>';
                            }
                            echo'
                                <a href="profile.php?user='.$_SESSION['id'].'" class="btn btn-info pull-lefy btn-sm">Profile</a>
                                <a href="logout.php?logout" class="btn btn-danger pull-right btn-sm">LogOut</a>
                            </div>
                           
                        </div>
                     
                    </div>
                </div>
            </aside>
        ';
    }else{
        echo'
            <aside>
                <div class="row">
                    <div class="panel">
            
                        <div class="panel-heading text-center">
                            <b>Sign In</b>
                        </div>
            
                        <div class="panel-body">
                            <div class="text-center img">
                                <img src="images/non-avatar.png" width="85px">
                            </div>
            
                            <form action="include/login_process.php" method="post" class="login100-form validate-form form" id="login">
                                <div class="wrap-input100 validate-input input" data-validate = "Valid email is required: ex@abc.xyz">
                                    <input class="input100" type="text" name="email" placeholder="UserName Or Email" required>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>
            
                                <div class="wrap-input100 rs1 validate-input input" data-validate="Password is required">
                                    <input class="input100" type="password" name="pass" placeholder="Password" required>
                                    <span class="focus-input100-1"></span>
                                    <span class="focus-input100-2"></span>
                                </div>
            
        <!-- لعرض loading عندما يتم الضغط علي زرار الارسال وهو خاص ب ajax -->                        
                                <div id="login_result" style="text-align: center;margin: 10px 0;"></div>
                                <!-- End -->
                                
                                <div class="container-login100-form-btn m-t-20">
                                    <input type="submit" name="send" value="Sign in" class="login100-form-btn">
                                </div>
            
                                <div class="text-center p-t-45 p-b-4">
                                        <span class="txt1">
                                            Forgot :
                                        </span>
            
                                    <a href="#" class="txt2 hov1">
                                        Username / Password?
                                    </a>
                                </div>
            
                                <div class="text-center p-t-45 p-b-4" style="background-color:#4272d766;border-radius:50%;padding:10px 0;margin:10px 10%;font-weight:bold;">
                                        <span class="txt1" style="color:black">
                                            New User :
                                        </span>
            
                                    <a href="register.php" class="txt2 hov1">
                                        Create an account ?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
        ';
    }
}
/* End function login */

//////////////////////////////////////////////////////////////////////////////////////////////

/* هنا بدأت function خاصة ب register */
function register(){

    global $select_cities;

    if (@$_SESSION['id']){ /*هنا لو فيه session id مفتوح يعمل الجزء دا وحطيت @ هنا عشان امنع ظهور خطأ*/
        echo '<div class="alert alert-danger text-center" role="alert" style="font-size: large;"><b>عفوا يا <sapn style="color: #2e6da4;">' .$_SESSION['username'].'</sapn> لا يمكنك/ى الدخول الي هذه الصفحه حاليا فأنت/ى مسجل/ه بالفعل لدينا<b></div> ';
    }else{ /*طبيعي لو مفيش session مفتوح هينفذ الجزء دا ويظهر فورم التسجيل*/
        echo'
            <form action="include/register_process.php" method="post" class="login100-form validate-form" id="register">
                <span class="login100-form-title p-b-33" style="text-decoration: underline">
                    Account Register
                </span>
        
                <label><span style="color: red">* </span>User Name :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" placeholder="User Name">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Full Name :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="name" placeholder="Your Name">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Your Email :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="email" placeholder="Email" required>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Your Password :</label>
                <div class="wrap-input100 rs1 validate-input">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Confairm Password :</label>
                <div class="wrap-input100 rs1 validate-input">
                    <input class="input100" type="password" name="con_password" placeholder="Confairm Password">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Mobail :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="mobail1" placeholder="Your Mobail Number1">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label>Second Mobail  :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="mobail2" placeholder="Your Mobail Number2">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label><span style="color: red">* </span>Your Address :</label>
                <div class="wrap-input100 validate-input">
                    <textarea name="about" class="form-control" id="about_you" rows="4" placeholder="Your Address"></textarea>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label> Photo :</label>
                <div class="wrap-input100 validate-input">
                    <input type="file" name="image" class="form-control" id="avatar" style="height: 100%;">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label> Gender :</label>
                <div class="wrap-input100 validate-input">
                    <select name="gender" class="form-control" id="gender">
                        <option value="male">ذكر</option>
                        <option value="female">أنثي</option>
                    </select>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
        
                <label> Governorate :</label>
                <div class="wrap-input100 validate-input">
                    <select name="Gov" class="form-control" id="Gov">
        ';
                        while ($row_cities=$select_cities->fetch(PDO::FETCH_OBJ)){
                            echo '<option value="'.$row_cities->id.'">'.$row_cities->name.'</option>';
                        }
        echo '
                    </select>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
                
    <!-- لعرض loading عندما يتم الضغط علي زرار الارسال وهو خاص ب ajax -->            
                <div class="col-md-12 text-center" style="width: 500px;margin-right: 250px;">
                    <div id="register_result" style="margin: 20px 0;"></div>
                </div>
                <!-- End -->
                
                <div class="container-login100-form-btn m-t-20">
                    <input type="submit" name="send" value="Register" class="login100-form-btn">
                </div>
            </form>
        ';
    }
}
/* End function register */

/////////////////////////////////////////////////////////////////////////////////////////////

/* هنا بدأت function خاصة ب comment */
function comment_area(){

    global $pro_id;

    if (!isset($_SESSION['id'])){ /*هنا لو مفيش session id مفتوح يعمل الجزء دا */
        echo'<div class="alert alert-danger text-center" role="alert"><b>سجل دخول حتي تستطيع التعليق :( </b><small>لعمل حساب جديد <a href="register.php">اضغط هنا</a></small></div>';
    }else { /*طبيعي لو مفيش session مفتوح هينفذ الجزء دا ويظهر فورم التسجيل*/
        echo '
            <form action="include/comment_process.php" method="post" class="form-horizontal" id="comments">
                <div class="form-group">
                    <label for="comment" class="col-sm-2 control-label">Title :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Comment Title">
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="col-sm-2 control-label">Content :</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="content" id="content" rows="5" placeholder="Comment Content"></textarea>
                    </div>
                </div>
                <input type="hidden" name="pro_id" value="'.$pro_id.'">
                <div class="form-group">
                    <div class="col-sm-6">
                        <div id="com_result" class="text-center"></div> <!-- دا خاص بعرض الجزء بتاع ajax -->
                        <button type="submit" name="send" class="btn btn-info"><b>Add Comment</b></button>
                    </div>
                </div>
            </form>
        ';
    }
}
/* End function comment */

//////////////////////////////////////////////////////////////////////////////////////////////

/* هنا بدأت function خاصة ب request */
function request(){
    if (!isset($_SESSION['id'])){ /*هنا لو مفيش session id مفتوح يعمل الجزء دا */
        echo'<div class="alert alert-danger text-center" role="alert"><b>سجل دخول حتي تستطيع طلب منتج خاص :( </b><small>لعمل حساب جديد <a href="register.php">اضغط هنا</a></small></div>';
    }else { /*طبيعي لو مفيش session مفتوح هينفذ الجزء دا ويظهر فورم التسجيل*/
        echo '
            <form action="include/request_process.php" method="post" class="login100-form validate-form" id="request">
                <span class="login100-form-title p-b-33" style="text-decoration: underline">
                    Special Request
                </span>
    
                <label><span style="color: red">* </span> File  :</label>
                <div class="wrap-input100 validate-input">
                    <input type="file" name="file" class="form-control" id="file" style="height: 100%;">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label> Layers :</label>
                <div class="wrap-input100 validate-input">
                    <select name="layers" class="form-control">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label><span style="color: red">* </span>Dimension_h :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="dimension_h" placeholder="Length Up To  >> 200mm" maxlength="3">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label><span style="color: red">* </span>Dimension_w :</label>
                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="dimension_w" placeholder="Width Up To  >> 150mm" maxlength="3">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label><span style="color: red">* </span>Quantity :</label>
                <div class="wrap-input100 rs1 validate-input">
                    <input class="input100" type="text" name="quantity" placeholder="quantity" maxlength="1">
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label> Color Mask  :</label>
                <div class="wrap-input100 validate-input">
                    <select name="color" class="form-control" id="color">
                        <option value="green">Green</option>
                        <option value="blue">Blue</option>
                        <option value="red">Red</option>
                    </select>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
    
                <label> Notes :</label>
                <div class="wrap-input100 validate-input">
                    <textarea name="notes" class="form-control" id="notes" rows="4" placeholder="Your Notes"></textarea>
                    <span class="focus-input100-1"></span>
                    <span class="focus-input100-2"></span>
                </div>
                
                <br>
                <div id="request_result" class="text-center"></div> <!-- دا خاص بعرض الجزء بتاع ajax -->
                  
                <div class="container-login100-form-btn m-t-20">
                    <input type="submit" name="send" value="Order" class="login100-form-btn">
                </div>
            </form>
        ';
    }
}
/* End function request */