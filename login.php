<?php
session_start();
$error_message = '';
if (isset($_SESSION['user'])) {
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        header('Location: http://localhost/acsc');
    else
        header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
}
if (!empty($_POST)) {
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        $link = mysqli_connect("localhost", "root", "", "acsc");
    else
        $link = mysqli_connect("68.178.145.9", "c656u1gpdlj9", "Duryodhan@1", "acsc");

    if ($link === false) {
        die("DB ERROR: Could not connect. " . mysqli_connect_error());
    }
    if ($_POST['form'] == 'login') {
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $sql = "SELECT * FROM user where email='$email' AND password='$password'";

        if ($result = mysqli_query($link, $sql)) {
            $row = mysqli_fetch_array($result);
            if (!empty($row)) {
                $_SESSION["user"] = $row['first_name'] . " " . $row['last_name'];
                $_SESSION["id"] = $row['id'];

                $url = $_SERVER['SERVER_NAME'];
                
                if ($_SERVER['SERVER_NAME'] == 'localhost')
                    header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
                else
                    header('Location: http://' . $_SERVER['SERVER_NAME']);
            } else {
                $error_message = 'No Record Found, Please try again';
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
        mysqli_close($link);
    } else if ($_POST['form'] == 'registration') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $role_id = $_POST['role'];
        $contact_number = $_POST['contact_number'];
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO user (first_name,last_name,email,password,role_id,contact_number,created_date,updated_date) VALUES ("
                . "'$first_name',"
                . "'$last_name',"
                . "'$email',"
                . "'$password',"
                . "'$role_id',"
                . "'$contact_number',"
                . "'$date',"
                . "'$date');";
        if (mysqli_query($link, $sql)) {
//            echo "Records inserted successfully.";
            $url = $_SERVER['SERVER_NAME'];
            if ($_SERVER['SERVER_NAME'] == 'localhost')
                header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
            else
                header('Location: http://' . $_SERVER['SERVER_NAME']);
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
        mysqli_close($link);
    } else if ($_POST['form'] == 'reset-password') {
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
        <?php include 'headTag.html'; ?>
    <body class="login-layout light-login">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <div>
<!--                                    <i class="ace-icon fa fa-leaf green"></i>-->
                                        <div class="col"><img class="nav-user-photo" src="assets/images/gallery/acsc_logo.jpeg" width="80" height="80" alt="College Logo" />
                                        </div>
                                        <div>
                                            <span class="red">ACSC Application</span>
                                            <!--<span class="" id="id-text2"></span>-->
                                        </div>
                                    </div>
                                </h1>
                                <h6 class="blue" id="id-company-text">&copy; ACSC Jamner</h6>
                            </div>

                            <div class="space-6"></div>
                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <?php if ($error_message) { ?>
                                                <h4 class="header red lighter">
                                                    <?php echo $error_message; ?>
                                                </h4>
                                            <?php } ?>
                                            <h4 class="header blue lighter bigger">
                                                Please Enter Your Credentials
                                            </h4>
                                            <div class="space-6"></div>
                                            <form action="" method="post">
                                                <input name="form" type="hidden" class="form-control" value="login" />
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="Email" name="email" required=""/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="Password" name="password" required=""/>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>
                                            <!--
                                                <div class="social-or-login center">
                                                    <span class="bigger-110">Or Login to Library System</span>
                                                </div>
                                            
                                                <div class="space-6"></div>
                                            
                                                <div class="center">
                                                    <a class="btn btn-primary" href="library/adminlogin.php" target="_blank">
                                                        <i class="ace-icon fa fa-book"></i>
                                                        <span class="bigger-110">Login to Library Module</span>
                                                    </a>
                                                </div>
                                            -->
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar clearfix">
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->
                                <div id="forgot-box" class="forgot-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header red lighter bigger">
                                                <i class="ace-icon fa fa-key"></i>
                                                Retrieve Password
                                            </h4>

                                            <div class="space-6"></div>
                                            <p>
                                                Enter your email and to receive instructions
                                            </p>

                                            <form>
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" class="form-control" placeholder="Email" />
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <div class="clearfix">
                                                        <button type="button" class="width-35 pull-right btn btn-sm btn-danger">
                                                            <i class="ace-icon fa fa-lightbulb-o"></i>
                                                            <span class="bigger-110">Send Me!</span>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                Back to login
                                                <i class="ace-icon fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.forgot-box -->

                                <div id="signup-box" class="signup-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                New User Registration
                                            </h4>

                                            <div class="space-6"></div>
                                            <p> Please add below details: </p>
                                            <form action="" method="post">
                                                <input name="form" type="hidden" class="form-control" value="registration" />
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="email" type="email" class="form-control" placeholder="Email" required/>
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="first_name" type="text" class="form-control" placeholder="First Name" required/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="last_name" type="text" class="form-control" placeholder="Last Name" required/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="password"type="password" class="form-control" placeholder="Password" required/>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="confirm_password"type="password" class="form-control" placeholder="Confirm password" required/>
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input name="contact_number"type="text" class="form-control" placeholder="Contact Number" required/>
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <!--<input name="role" type="text" class="form-control" placeholder="Contact Number" />-->
                                                            <select name="role" id="role" required>
                                                                <option value="1">Principle</option>
                                                                <option value="2">Clerk</option>
                                                            </select>
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block">
                                                        <input type="checkbox" class="ace" required/>
                                                        <span class="lbl">
                                                            I accept the
                                                            <a href="#">User Agreement</a>
                                                        </span>
                                                    </label>

                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="reset" class="width-30 pull-left btn btn-sm">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Reset</span>
                                                        </button>

                                                        <button type="submit" class="width-65 pull-right btn btn-sm btn-success">
                                                            <span class="bigger-110">Register</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                <i class="ace-icon fa fa-arrow-left"></i>
                                                Back to login
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.signup-box -->
                            </div><!-- /.position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->
        <!--[if !IE]> -->
        <script src="assets/js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function ($) {
                $(document).on('click', '.toolbar a[data-target]', function (e) {
                    e.preventDefault();
                    var target = $(this).data('target');
                    $('.widget-box.visible').removeClass('visible');//hide others
                    $(target).addClass('visible');//show target
                });
            });



            //you don't need this, just used for changing background
            jQuery(function ($) {
                $('#btn-login-light').on('click', function (e) {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'grey');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-dark').on('click', function (e) {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-blur').on('click', function (e) {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');

                    e.preventDefault();
                });

            });
        </script>
    </body>
</html>