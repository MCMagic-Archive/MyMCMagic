<?php
    require("../../auth.php");
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    function getRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
    date_default_timezone_set('America/New_York');
    function getrankname($var){
        switch ($var){
            case 'guest':
                return "Guest";
            case 'dvc':
                return "DVC";
            case 'shareholder':
                return "Shareholder";
            case 'character':
            case 'characterguest':
                return "Character";
            case 'specialguest':
                return "Special Guest";
            case 'minedisney':
            case 'adventureridge':
            case 'anchornetwork':
            case 'magicaldreams':
            case 'craftventure':
                return "Partner";
            case 'mcprohosting':
                return "MCProHosting";
            case 'earningmyears':
                return "Earning My Ears";
            case 'castmember':
                return "Cast Member";
            case 'coordinator':
                return "Coordinator";
            case 'developer':
                return "Developer";
            case 'manager':
                return "Manager";
            case 'owner':
                return "Owner";
            case 'mayor':
                return "Mayor";
        }
    }
    function getrankcolor($var){
        switch ($var){
            case 'dvc':
                return "donor-dvc";
            case 'shareholder':
                return "donor-shareholder";
            case 'specialguest':
            case 'minedisney':
            case 'adventureridge':
            case 'anchornetwork':
            case 'magicaldreams':
            case 'craftventure':
            case 'mcprohosting':
                return "special";
            case 'earningmyears':
            case 'castmember':
            case 'coordinator':
                return "staff-cm";
            case 'developer':
            case 'manager':
            case 'owner':
            case 'mayor':
                return "staff-manager";
        }
    }
    $uuid = $_SESSION['UUID'];
    $username = $_SESSION['USERNAME'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "MainServer");

    #MAIN DATA
    $main = mysqli_query($conn, "SELECT rank,balance,tokens FROM player_data WHERE uuid='" . $uuid . "';");
    $rank = "Guest";
    $rankcolor = "";
    $bal = 0;
    $tokens = 0;
    if (mysqli_num_rows($main) > 0) {
        while($row = mysqli_fetch_assoc($main)) {
            $rank = getrankname($row['rank']);
            if($rank != 'Guest'){
                $rankcolor = ' ' . getrankcolor($row['rank']);
            }
            $bal = $row['balance'];
            $tokens = $row['tokens'];
        }
    }
    
    $tag = array();

    #CHANGE EMAIL
    if(isset($_POST['email']) && $_POST['email'] != ""){
        $email = sanitize($_POST['email']);
        mysqli_select_db($conn, "mymcmagic");
        mysqli_query($conn, "UPDATE users SET email='" . $email . "' WHERE uuid='" . $_SESSION['UUID'] . "';");
        mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $_SESSION['UUID'] . "','Change Email Address','" . $email . "')");
        array_push($tag, "email-changed");
    }

    #CHANGE PASSWORD
    if(isset($_POST['password']) && isset($_POST['rpassword']) && $_POST['password'] != "" && $_POST['rpassword'] != ""){
        $pass = sanitize($_POST['password']);
        $repeat = sanitize($_POST['rpassword']);
        mysqli_select_db($conn, "mymcmagic");
        if($pass == $repeat){
            $hash = sanitize(getRandomString(64));
            $hashed = sha1($pass . $hash);
            mysqli_query($conn, "UPDATE users SET password='" . $hashed . "',hash='" . $hash . "' WHERE uuid='" . $_SESSION['UUID'] . "';");
            mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $_SESSION['UUID'] . "','Change Password','Account Settings')");
            array_push($tag, "pass-changed");
        }else{
            array_push($tag, "no-equal");
        }
    }
    mysqli_close($conn);
?>
    <!DOCTYPE html>
    <!--
Page: Profile Page
Version: 0.0.1
Made with <3 by Brant & Turner
[if !IE]><!-->
    <html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <?php $page = "Settings";
        include("../../header.php");
        ?>

            <!-- BEGIN GLOBAL MANDATORY STYLES -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <link href="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL STYLES -->
            <link href="../../assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
            <link href="../../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css" />
            <!-- END THEME GLOBAL STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="../../assets/pages/css/profile.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL STYLES -->
            <!-- BEGIN LAYOUT STYLES -->
            <link href="../../assets/layout/css/layout.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/layout/css/default.css" rel="stylesheet" type="text/css" id="style_color" />
            <link href="../../assets/layout/css/custom.css" rel="stylesheet" type="text/css" />
            <!-- END LAYOUT STYLES -->
        
    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-md">
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <!-- BEGIN PAGE HEAD-->
                <!-- BEGIN PAGE CONTENT BODY -->
                <div class="page-content">
                    <div class="container">
                        <!-- BEGIN PAGE CONTENT INNER -->
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- BEGIN PROFILE SIDEBAR -->
                                    <div class="profile-sidebar">
                                        <!-- PORTLET MAIN -->
                                        <div class="portlet light profile-sidebar-portlet ">
                                            <!-- SIDEBAR USERPIC -->
                                            <div class="profile-userpic">
                                                <img <?php echo 'src="https://minotar.net/helm/' . $username . '/150"'; ?> class="img-responsive" alt=""> </div>
                                            <!-- END SIDEBAR USERPIC -->
                                            <!-- SIDEBAR USER TITLE -->
                                            <div class="profile-usertitle">
                                                <div class="profile-usertitle-name">
                                                    <?php echo $username; ?>
                                                </div>
                                                <div class="profile-usertitle-job">
                                                    <div <?php echo ($rankcolor != '' ? 'class="' . $rankcolor . '"' : '') ?>>
                                                    <?php echo $rank; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END SIDEBAR USER TITLE -->
                                            <!-- SIDEBAR MENU -->
                                            <div class="profile-usermenu">
                                                <ul class="nav">
                                                    <li>
                                                        <a href="../">
                                                            <i class="fa fa-home"></i> Profile </a>
                                                    </li>
                                                    <li class="active">
                                                        <a href=".">
                                                            <i class="fa fa-cogs"></i> Account Settings </a>
                                                    </li>
                                                    <li>
                                                        <a href="../../support/">
                                                            <i class="fa fa-question"></i> Support </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- END MENU -->
                                        </div>
                                        <!-- END PORTLET MAIN -->
                                        <!-- PORTLET MAIN -->
                                        <div class="portlet light ">
                                            <!-- STAT -->
                                            <div class="row profile-stat">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="uppercase profile-stat-title">
                                                        <?php echo '$' . $bal; ?>
                                                    </div>
                                                    <div class="uppercase profile-stat-text"> Balance </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="uppercase profile-stat-title">
                                                        <?php echo $tokens; ?>
                                                    </div>
                                                    <div class="uppercase profile-stat-text"> Tokens </div>
                                                </div>
                                            </div>
                                            <!-- END STAT -->
                                        </div>
                                        <!-- END PORTLET MAIN -->
                                    </div>
                                    <!-- END BEGIN PROFILE SIDEBAR -->
                                    <!-- BEGIN PROFILE CONTENT -->
                                    <div class="profile-content">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Account Settings</span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    foreach($tag as $t){
                                                        $type = "info";
                                                        $msg = "";
                                                        switch($t){
                                                            case "no-equal":
                                                                $msg = "The two passwords must match!";
                                                                $type = "danger";
                                                                break;
                                                            case "not-email":
                                                                $msg = "The entered value is not a valid email!";
                                                                $type = "danger";
                                                                break;
                                                            case "email-changed":
                                                                $msg = "Email address changed successfully!";
                                                                break;
                                                            case "pass-changed":
                                                                $msg = "Password changed successfully!";
                                                                break;
                                                        }
                                                        echo '<div class="alert alert-' . $type . '"><button class="close" data-close="alert"></button><span>' . $msg . ' </span></div>';
                                                    }
                                                    ?>
                                                        <div class="portlet-body">
                                                            <div class="row number-stats margin-bottom-30">

                                                                <form class="account-settings-form" action="." method="post">
                                                                    <div class="form-group">
                                                                        <p>Only fill out this field if you intend on changing the email linked to your account.</p>
                                                                        <label class="control-label visible-ie8 visible-ie9">Email Address</label>
                                                                        <div class="input-icon">
                                                                            <i class="fa fa-envelope"></i>
                                                                            <input class="form-control placeholder-no-fix" type="email" placeholder="Email Address" name="email" /> </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <p>Only fill out this field if you intend on changing the password linked to your account.</p>
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
                                                                    <div class="form-group">
                                                                        <div id="register_tnc_error"> </div>
                                                                    </div>
                                                                    <div class="form-actions">
                                                                        <button type="submit" id="register-submit-btn" class="btn blue pull-right"> Save Changes </button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- END PROFILE CONTENT -->
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                    </div>
                </div>
                <!-- END PAGE CONTENT BODY -->
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <?php
        include("../../footer.php");
        ?>
        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!-- BEGIN CORE PLUGINS -->
        <script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script src="../../assets/global/plugins/gmaps/gmaps.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="../../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../../assets/pages/scripts/profile.min.js" type="text/javascript"></script>
        <script src="../../assets/pages/scripts/timeline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="../../assets/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="../../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->        
        </body>
</html>