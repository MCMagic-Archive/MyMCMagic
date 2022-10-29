<?php
    require("../auth.php");
    date_default_timezone_set('America/New_York');
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    function getName($var){
        switch($var){
            case 'character':
                return 'Character';
            case 'castmember':
                return 'Cast Member';
            case 'developer':
                return 'Developer';
        }
        return '';
    }
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    $open = mysqli_query($conn, "SELECT name FROM app_types WHERE open=0 ORDER BY name ASC;");
    $apps = array();
    if($open && mysqli_num_rows($open) > 0){
        while($row = mysqli_fetch_assoc($open)){
            array_push($apps, $row['name']);
        }
    }
    $date = sanitize($_POST['date']);
    $day = sanitize($_POST['day']);
    $month = sanitize($_POST['month']);
    $year = sanitize($_POST['year']);
    if((isset($date) && $date != '')){
        $s = $date;
        $arr = explode('-', $s);
        $day = $arr[2];
        $month = $arr[1];
        $year = $arr[0];
    }
    $under13 = false;
    if(isset($day) && $day != 0 && isset($month) && $month != 0 && isset($year) && $year != 0){
        mysqli_query($conn, "UPDATE users SET dob=('" . $year . "-" . $month . "-" . $day . " 00:00:00') WHERE id='" . $_SESSION['ID'] . "';");
    }
    $ageq = mysqli_query($conn, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM users WHERE id='" . $_SESSION['ID'] . "';");
    $age = mysqli_fetch_assoc($ageq)['age'];
    if($age < 13){
        mysqli_query($conn, "UPDATE users SET dob=('1000-01-01 00:00:00') WHERE id='" . $_SESSION['ID'] . "';");
        $ageq2 = mysqli_query($conn, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM users WHERE id='" . $_SESSION['ID'] . "';");
        $age = mysqli_fetch_assoc($ageq2)['age'];
        $under13 = true;
    }
    $hasAge = $age < 200;
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
        <?php $page = "Apply";
        include("../header.php");
        ?>

            <!-- BEGIN GLOBAL MANDATORY STYLES -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="/assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="/assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL STYLES -->
            <link href="/assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
            <link href="/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css" />
            <!-- END THEME GLOBAL STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="/assets/pages/css/profile.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL STYLES -->
            <!-- BEGIN LAYOUT STYLES -->
            <link href="/assets/layout/css/layout.css" rel="stylesheet" type="text/css" />
            <link href="/assets/layout/css/default.css" rel="stylesheet" type="text/css" id="style_color" />
            <link href="/assets/layout/css/custom.css" rel="stylesheet" type="text/css" />
            <!-- END LAYOUT STYLES -->

    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-md">
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <!-- BEGIN PAGE CONTENT BODY -->
                <div class="page-content">
                    <div class="container">
                        <!-- BEGIN PAGE CONTENT INNER -->
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light">
                                        <div class="portlet-title">
                                            <div class="caption font-blue">
                                                <i class="icon-settings font-blue"></i>
                                                <span class="caption-subject bold uppercase"> MCMagic Applications</span>
                                                <?php
                                                    if($_GET['closed'] == 1 && isset($_GET['type']) && $_GET['type'] != ''){
                                                        $msg = "";
                                                        switch($_GET['type']){
                                                            case "cm":
                                                                $msg = "We're currently not accepting Cast Member applications, sorry!";
                                                                break;
                                                            case "dev":
                                                                $msg = "We're currently not accepting Developer applications, sorry!";
                                                                break;
                                                            case "char":
                                                                $msg = "We're currently not accepting Character applications, sorry!";
                                                                break;
                                                        }
                                                        if($msg != ''){
                                                            echo '<div class="alert alert-danger" style="margin-top:5%;"><span>' . $msg . ' </span></div>';
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <div id="disclaimer" class="portlet body form" <?php echo ($hasAge ? '' : 'style="text-align:center;"'); ?>>
                                            <?php
                                                if($hasAge){
                                                    echo '<label class=text>By choosing an application below you agree to the following terms:</label><ul><li>You are 13 years old or older<li>You want to genuinely help guests individually and as a group<li>You understand <i>all</i> roles are volunteer positions.<li>You will make sure that the server is running smoothly and report it when it isn’t<li>You have:</li><ul><li>The ability to work well in a team setting<li>The ability to dedicate consistent time to the server (breaks are allowed)<li>The ability to handle tough situations, think quickly on your feet and help when others need it<li>Solid communication skills (Grammar and sentence structure)</ul><li>You will be required to:</li><ul><li>Deal with and protect sensitive information<li>Abide by our privacy policy (See a Manager for more info)<li>Attend trainings and meetings<li>Actively use our Mumble Server<li>Hear and talk to others while on Mumble</ul><li>The Minecraft name you apply with should remain the same throughout the duration of your service with us (slight variations are okay)<li>You will use all required tools/services on a daily basis (<a href=https://slack.com/is target=_blank>Slack</a>, <a href=https://mcmagic.us/mumble target=_blank>Mumble</a>, <a href=https://trello.com/tour target=_blank>Trello</a>, <a href=https://www.google.com/drive target=_blank>Google Drive</a>)<li>While a Cast Member on MCMagic, you will not be a staff member on <i>any other server</i></li><br><i>The DVC or Shareholder rank does not increase your chance at becoming a member of staff.</i><br><br><li>We will look at your history as a Guest and hope to see that you have provided help to others while visiting our parks.<li>Please fill out the following application(s) truthfully and to the best of your ability.</ul><br><label class=text>We are currently accepting applications for the following roles:</label>';
                                                    if(!empty($apps)){
                                                        echo '<center>';
                                                        foreach($apps as $a){
                                                            echo '<button type="button" class="btn blue btn-outline" id="' . $a . '">' . getName($a) . '</button>' . "\xA";
                                                        }
                                                        echo '</center>';
                                                    }else{
                                                        echo '<center>';
                                                        echo '<h4>We are currently not accepting any applications!</h4>';
                                                        echo '</center>';
                                                    }
                                                }else{
                                                    echo '<h4>Before submitting an application, we require your date of birth:</h4><form action=. id=form method=post><div id=dob><input name=date type=date id=date></div></form><br><label class=text><label class=bold>Important:</label><br>You <b>must</b> be at least 13 years old or older to submit an application.</label><br><label class=text>Once submitted, your date of birth can <b>not</b> be changed.</label><br><label class=text>If you submit an incorrect date of birth so you can submit an application, we will <i><b>never</b></i> review <i><b>any</b></i> of your applications.</label><br><label class=text>To comply with the <a href=https://www.ftc.gov/enforcement/rules/rulemaking-regulatory-reform-proceedings/childrens-online-privacy-protection-rule target=_blank>COPPA Act,</a> if you submit a date of birth below 13 years of age, we will <b>not</b> store your information.</label><div class=actions><div class="btn-group btn-group-devided"data-toggle=buttons id=update><br><label class="blue btn btn-circle btn-outline btn-sm btn-transparent"><input name=options type=radio class=toggle>Save Date of Birth</label></div></div>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN INNER FOOTER -->
        <?php
        include("../footer.php");
        ?>
        <!-- END INNER FOOTER -->
        <!-- END FOOTER -->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- END PAGINATION CODE -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/layout/scripts/layout.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            <?php
            if($hasAge){
                if(in_array('character', $apps)){
                    echo 'document.getElementById("character").onclick=function(){window.location="character/";}' . "\xA";
                }
                if(in_array('castmember', $apps)){
                    echo 'document.getElementById("castmember").onclick=function(){window.location="cast-member/";}' . "\xA";
                }
                if(in_array('developer', $apps)){
                    echo 'document.getElementById("developer").onclick=function(){window.location="developer/";}' . "\xA";
                }
            }
            if($under13){
                echo "$(document).ready(function(){alert('Since you are not 13 years old, we will not store your age information. Come back and apply when you\'re 13!');});";
            }
        ?>
            if ($('#date')[0].type != 'date') {
                $('#dob').html('<h4>Month:</h4><input type="number" id="date" min="1" max="12" value="<?php echo date("m"); ?>" style="width:3.3%"><h4>Day:</h4><input type="number" id="date" min="1" max="31" value="<?php echo date("d"); ?>" style="width:3.3%"><h4>Year:</h4><input type="number" id="date" min="1900" max="<?php echo date("Y"); ?>" value="<?php echo date("Y"); ?>" style="width:5%">');
            }
            document.getElementById('update').onclick = function () {
                $('form').submit();
            }
        </script>
    </body>

    </html>