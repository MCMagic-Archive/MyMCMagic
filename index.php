<?php
session_start();
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
if(isset($_SESSION['UUID']) && (trim($_SESSION['UUID']) != '')){
    header("Location: profile/");
    exit();
}
$redirect = "";
if(isset($_GET['redir']) && $_GET['redir'] != ''){
    $redirect = $_GET['redir'];
}
if(isset($_COOKIE['remember'])){
    $token = $_COOKIE['remember'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_select_db($conn, "mymcmagic");
    $res = mysqli_query($conn, "SELECT uuid,token FROM remember WHERE token='" . $token . "';");
    $validated = false;
    if (mysqli_num_rows($res) > 0) {
        while($row = mysqli_fetch_assoc($res)) {
            if($row['token'] == $token){
                $validated = true;
                break;
            }
        }
    }
    if($validated){
        session_regenerate_id();
        $uuid = $row['uuid'];
        $_SESSION['UUID'] = $uuid;
        $eres = mysqli_query($conn, "SELECT email,id FROM users WHERE uuid='" . $uuid . "';");
        if (mysqli_num_rows($eres) > 0) {
            while($row = mysqli_fetch_assoc($eres)) {
                $_SESSION['EMAIL'] = $row['email'];
                $_SESSION['ID'] = $row['id'];
                break;
            }
        }
        mysqli_select_db($conn, "MainServer");
        $getuser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username,rank FROM player_data WHERE uuid='" . $uuid . "';"));
        $_SESSION['USERNAME'] = $getuser['username'];
        $_SESSION['RANK'] = $getuser['rank'];
        session_write_close();
        if($redirect != '' && (0 === strpos($redirect, 'https://my.mcmagic.us/') || 0 === strpos($redirect, 'http://127.0.0.1/'))){
            header("Location: " . sanitize($_POST['redirect']));
            exit();
        }
        header("Location: profile/");
        exit();
    }
}
?>
    <!DOCTYPE html>
    <!--
Page: Login Page
Version: 0.0.1
Designer(s): Brant, Turner
Developer: #REDACTEDFAM#
<!-->
    <html lang="en">

    <head>
        <noscript>
            <meta http-equiv="refresh" content="0; url=/nojs/" />
        </noscript>
        <meta charset="utf-8" />
        <?php $page = "Login";
    include("header_login.php");
    ?>

            <!-- Global Mandatory Page Stuff, no touchy por favor -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- end that -->
            <!-- Page Specific stuff, you can touch if you really have to. -->
            <link href="assets/global/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
            <link href="assets/global/plugins/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css" />
            <!-- ending that too -->
            <!-- Global Styles, this is for later when the actual site is made fam, please dont touch. -->
            <link href="assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
            <link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
            <!-- ending that again -->
            <!-- Page specific styling, this is where the MCMAGIC HAPPENS. -->
            <link href="assets/pages/css/site.css" rel="stylesheet" type="text/css" />
            <!-- Finally, we've reached the end of the ends, for now lel -->
            <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- ending header magic, this is where the fun begins v v v v v v -->

    <body class=" login">
        <!-- beginning bulk content, what'd I get myself into again? -->
        <div class="user-login-5">
            <div class="row bs-reset">
                <div class="col-md-6 login-container bs-reset">
                    <img class="login-logo login-6" src="img/_logo.png" href="index.php" />
                    <div class="login-content">
                        <?php
                            $type = "info";
                            $msg = "";
                            switch($_GET['tag']){
                                case "successful":
                                    $msg = "A confirmation email has been sent! Check your inbox to confirm your email.";
                                    $type = "success";
                                    break;
                                case "account-created":
                                    $msg = "Your account has been created! Log in with your email and password below.";
                                    $type = "success";
                                    break;
                                case "no-confirm":
                                    $msg = 'Email not confirmed. Please check your inbox for confirmation. <a href="register/resend.php?uuid=' . $_GET['uuid'] . '">Resend email?</a>';
                                    $type = "warning";
                                    break;
                                case "inv-pin":
                                    $msg = "The PIN you entered is not valid. Try again.";
                                    $type = "danger";
                                    break;
                                case "inv-pass":
                                    $msg = "Incorrect email address/password combination";
                                    $type = "danger";
                                    break;
                                case "email-taken":
                                    $msg = "This email is already being used. Try again.";
                                    $type = "warning";
                                    break;
                                case "pass-changed":
                                    $msg = "Password changed successfully!";
                                    $type = "success";
                                    break;
                                case "reset-sent":
                                    $msg = "An email has been sent to reset your password.";
                                    $type = "info";
                                    break;
                                case "suspended":
                                    $msg = "Your account has been suspended!";
                                    $type = "danger";
                                    break;
                            }
                            if($msg != ''){
                                echo '<div class="alert alert-' . $type . '"><button class="close" data-close="alert"></button><span>' . $msg . ' </span></div>';
                            }
                        ?>
                            <h1>MyMCMagic Login</h1>
                            <p> View your transaction history, submit Applications, contact Cast Members and more using MyMCMagic! <a href="javascript:;" id="register-btn">New account? Register here!</a> </p>
                            <form action="validate.php" class="login-form" method="post">
                                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button>
                                    <span>Please enter a valid username and password. </span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="email" autocomplete="off" placeholder="Email Address" name="email" required/> </div>
                                    <div class="col-xs-6">
                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="pass" required/> </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="rememberme mt-checkbox mt-checkbox-outline">
                                            <input type="checkbox" name="remember_me" value="1" /> Remember me
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-8 text-right">
                                        <div class="forgot-password">
                                            <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
                                        </div>

                                        <button class="btn blue" type="submit">Sign In</button>
                                    </div>
                                </div>
                                <br/>
                            </form>
                            <!-- BEGIN FORGOT PASSWORD FORM -->
                            <form class="forget-form" action="forgotpassword.php" method="post">
                                <h3>Forgot Password?</h3>
                                <p> Enter your e-mail address below to reset your password. </p>
                                <div class="form-group">
                                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                                <div class="form-actions">
                                    <button type="button" id="back-btn" class="btn blue btn-outline">Back</button>
                                    <button type="submit" class="btn blue uppercase pull-right">Submit</button>
                                </div>
                            </form>
                            <!-- END FORGOT PASSWORD FORM -->
                            <!-- BEGIN REGISTRATION FORM -->
                            <form class="register-form" action="register/register.php" method="post">
                                <p>Register your account details below:</p>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Email</label>
                                    <div class="input-icon">
                                        <i class="fa fa-envelope"></i>
                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email" /> </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                                    <div class="input-icon">
                                        <i class="fa fa-lock"></i>
                                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                                    <div class="controls">
                                        <div class="input-icon">
                                            <i class="fa fa-check"></i>
                                            <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
                                    </div>
                                </div>
                                <p>Log into the MCMagic Server and type /pin to get your PIN. <i style="font-size:14px;">This is how we know you're you, make sure it's correct!</i></p>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">Minecraft Pin Number</label>
                                    <div class="input-icon">
                                        <i class="fa fa-lock"></i>
                                        <input class="form-control placeholder-no-fix" type="number" autocomplete="off" id="register_pin" placeholder="MC Pin #" name="pin" /> </div>
                                </div>
                                <label class="text">By registering for MyMCMagic, you agree to our <a href="/legal/tos/" target="_blank">Terms of Service</a> and <a href="/legal/privacy/" target="_blank">Privacy Policy</a></label>
                                <div class="form-group">
                                    <div id="register_tnc_error"> </div>
                                </div>
                                <div class="form-actions">
                                    <button id="register-back-btn" type="button" class="btn red btn-outline"> Back </button>
                                    <button type="submit" id="register-submit-btn" class="btn blue pull-right"> Sign Up </button>
                                </div>
                            </form>
                            <!-- END REGISTRATION FORM -->
                    </div>
                </div>
                <div class="col-md-6 bs-reset">
                    <div class="login-bg"> </div>
                </div>
            </div>
        </div>
        <!-- Look, we're back to the end. LIKE BACK TO THE FUTURE. -->
        <!-- JS FAM, THIS IS GLOBAL DONT TOUCH PLS. -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- Ending that. -->
        <!-- These are required for the page to run properly, dont touch unless you know JS, unlike me. -->
        <script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <!-- finally ending that. -->
        <!-- Again, this is required for the site to run properly, please don't touch unless you know what you're doing. -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- we're continuing with the ending trend here. -->
        <!-- AND THIS IS WHERE THE PAEG RUNS, PLES DO NOT TOUCH THINGS. *touch* -->
        <script src="assets/pages/scripts/site.js" type="text/javascript"></script>
        <!-- Finally, the end of the ends. -->
        <!-- adding another end here to shun the above message. -->
        <!-- It really ends here -->
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-65888998-3', 'auto');
            ga('send', 'pageview');
        </script>
        <?php
        if(isset($_GET['r']) && $_GET['r'] == 1){
        ?>
            <script>
                $('.login-form').hide();
                $('.register-form').show();
            </script>
            <?php
        }
        if(isset($_GET['pin']) && is_numeric($_GET['pin'])){
        ?>
                <script>
                    $('#register_pin').val(<?php echo intval(sanitize($_GET['pin'])); ?>);
                </script>
                <?php
        }
        ?>
                    <?php
        if(isset($_GET['fp']) && $_GET['fp'] == 1){
        ?>
                        <script>
                            $('.login-form').hide();
                            $('.forget-form').show();
                        </script>
                        <?php
        }else{
        ?>
                            <script>
                                $('.forget-form').hide();
                            </script>
                            <?php
        }
        ?>
    </body>

    </html>