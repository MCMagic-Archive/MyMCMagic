<?php
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
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
if((isset($_POST['uuid']) && isset($_POST['token']) && isset($_POST['password'])) && ($_POST['uuid'] != '' && $_POST['token'] != '' && $_POST['password'] != '')){
    $uuid = sanitize($_POST['uuid']);
    $token = sanitize($_POST['token']);
    $password = sanitize($_POST['password']);
    $hash = sanitize(getRandomString(64));
    $hashed = sha1($password . $hash);
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "DELETE FROM forgotpassword WHERE uuid='" . $uuid . "';");
    mysqli_query($conn, "UPDATE users SET password='" . $hashed . "',hash='" . $hash . "' WHERE uuid='" . $uuid . "';");
    mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','Change Password','Forgotten Password Form')");
    header("Location: ../?tag=pass-changed");
    exit();
}
if(!isset($_GET['uuid']) || $_GET['uuid'] == '' || !isset($_GET['token']) || $_GET['token'] == ''){
    header("Location: ../?fp=1");
    exit();
}
$uuid = sanitize($_GET['uuid']);
$token = sanitize($_GET['token']);
mysqli_select_db($conn, "mymcmagic");
$result = mysqli_query($conn, "SELECT * FROM forgotpassword WHERE uuid='" . $uuid . "';");
$equals = false;
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        if($row['token'] == $token){
            $equals = true;
            break;
        }
    }
}
if(!$equals){
    mysqli_query($conn, "DELETE FROM forgotpassword WHERE uuid='" . $uuid . "';");
    echo 'Invalid reset token! <a href="../?fp=1">Request a new one here.</a>';
    exit();
}
?>
<head>
        <meta charset="utf-8" />
        <?php $page = "Password Reset";
    include("../header_login.php");
    ?>

            <!-- Global Mandatory Page Stuff, no touchy por favor -->
            <link href="../https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- end that -->
            <!-- Page Specific stuff, you can touch if you really have to. -->
            <link href="../assets/global/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css" />
            <!-- ending that too -->
            <!-- Global Styles, this is for later when the actual site is made fam, please dont touch. -->
            <link href="../assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
            <link href="../assets/global/css/plugins.css" rel="stylesheet" type="text/css" />
            <!-- ending that again -->
            <!-- Page specific styling, this is where the MCMAGIC HAPPENS. -->
            <link href="../assets/pages/css/site.css" rel="stylesheet" type="text/css" />
            <!-- Finally, we've reached the end of the ends, for now lel -->
            <link rel="shortcut icon" href="../favicon.ico" /> </head>
    <!-- ending header magic, this is where the fun begins v v v v v v -->

    <body class=" login">
        <!-- beginning bulk content, what'd I get myself into again? -->
        <div class="user-login-5">
            <div class="row bs-reset">
                <div class="col-md-6 login-container bs-reset">
                    <img class="login-logo login-6" src="../img/_logo.png" href="../index.php" />
                    <div class="login-content">
                        <h1>Password Reset</h1>
                        <p> Enter your new password below </p>
                        <form class="login-form" method="post">
                            <input type="hidden" name="uuid" value="<?php echo $uuid;?>">
                            <input type="hidden" name="token" value="<?php echo $token;?>">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required/> </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <button class="btn blue" type="submit">Change Password</button>
                                </div>
                            </div>
                            <br/>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 bs-reset">
                    <div class="login-bg"> </div>
                </div>
            </div>
        </div>
        <!-- Look, we're back to the end. LIKE BACK TO THE FUTURE. -->
        <!-- JS FAM, THIS IS GLOBAL DONT TOUCH PLS. -->
        <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- Ending that. -->
        <!-- These are required for the page to run properly, dont touch unless you know JS, unlike me. -->
        <script src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <script src="../assets/global/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <!-- finally ending that. -->
        <!-- Again, this is required for the site to run properly, please don't touch unless you know what you're doing. -->
        <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- we're continuing with the ending trend here. -->
        <!-- AND THIS IS WHERE THE PAEG RUNS, PLES DO NOT TOUCH THINGS. *touch* -->
        <script src="../assets/pages/scripts/site.js" type="text/javascript"></script>
        <!-- Finally, the end of the ends. -->
        <!-- adding another end here to shun the above message. -->
        <!-- It really ends here -->
    </body>