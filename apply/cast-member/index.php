<?php
require("../../auth.php");
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$open = mysqli_query($conn, "SELECT open FROM app_types WHERE name='castmember';");
if(mysqli_fetch_assoc($open)['open'] == 1){
    header("Location: ../?closed=1&type=cm");
    exit();
}
$hasApp = mysqli_query($conn, "SELECT submitted FROM cm_applications WHERE status != 5 AND uuid='" . $_SESSION['UUID'] . "';");
$submitted = false;
if($hasApp && mysqli_num_rows($hasApp) > 0){
    while($row = mysqli_fetch_assoc($hasApp)){
        $time = $row['submitted'];
        if(($time + 2592000) > time()){
            $submitted = true;
            break;
        }
    }
}
$ageq = mysqli_query($conn, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM users WHERE id='" . $_SESSION['ID'] . "';");
if(mysqli_fetch_assoc($ageq)['age'] > 200){
    header("Location: ../");
    exit();
}
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
        <?php $page = "Apply | Cast Member";
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
                                                <span class="caption-subject bold uppercase"> Cast Member Application</span>
                                            </div>
                                        </div>
                                        <div class="portlet body form">
                                            <?php
                                            if(!$submitted){
                                            ?>
                                                <form action="submit.php" method="post" id="application">
                                                    <input type="hidden" value="<?php echo $_SESSION['UUID']; ?>" name="uuid">
                                                    <input type="hidden" value="<?php echo $_SESSION['USERNAME']; ?>" name="username">
                                                    <input type="hidden" value="<?php echo $_SESSION['EMAIL']; ?>" name="email">
                                                    <label class="text">Please list any previous usernames you've had within the past 6 Months.</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="xBrant, HCBrant, HiItsBrant, etc." name="pastnames" required />
                                                    <label class="text">Do you own <i>or</i> share any <i>official</i> MCMagic Characters?</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Mickey Mouse, Minnie Mouse, etc." name="characters" required />
                                                    <label for="mumble" class="control-label">Are you active on our Mumble Server?</label>
                                                    <select id="mumble" name="mumble1" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                    <br/>
                                                    <label for="headset" class="control-label">Do you have headphones and a microphone?</label>
                                                    <select id="headset" name="mumble2" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                    <br/>
                                                    <label class="text">What region of the world do you live in?</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="North America, South America, EU, etc." name="region" required />
                                                    <label for="experience" class="control-label">Do you have previous moderation experience?</label>
                                                    <select id="experience" name="priorservers" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" onchange="javascript:yesnoCheck();">
                                                        <option value="yes" id="yesCheck">Yes</option>
                                                        <option value="no" selected id="noCheck">No</option>
                                                    </select>
                                                    <br/>
                                                    <div id="ifYes" style="visibility:hidden">
                                                        <label class="text">Where did you gain this experience?</label>
                                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="List Previous Experience" name="moderated" />
                                                    </div>
                                                    <label for="position" class="control-label">What interests you the most about becoming a Cast Member?</label>
                                                    <select id="position" name="role" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        <option value="gr">Helping Guests with any questions that they may have and keeping chat safe.</option>
                                                        <option value="im">Working with other Cast Members to help build and improve the parks.</option>
                                                        <option value="med">Producing content for MCMagic social media accounts.</option>
                                                        <option value="sd">Designing new and improved shows for our Guests to enjoy.</option>
                                                        <option value="cs">Working with others on the MCMagic Creative Server to ensure that it's safe.</option>
                                                    </select>
                                                    <br/>
                                                    <label for="why"> Why should we choose you to become a Cast Member?</label>
                                                    <textarea class="form-control" id="why" name="whyme" rows="5"></textarea>
                                                    <br/>
                                                    <center>
                                                        <h4 style="color:red">Check over your application! You can only submit once every 30 days.</h4>
                                                        <button type="button" class="btn blue btn-outline" onclick="formSubmit()">Submit!</button>
                                                    </center>
                                                </form>
                                                <?php
                                            } else {
                                                ?>
                                                    <h3>You've already submitted a Cast Member application in the past 30 days!</h3>
                                                    <h4>Wait until 30 days have passed since your last application to submit a new one. Return to your <a href="../../profile/">MyMCMagic Profile.</a></h4>
                                                    <?php
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
        <?php
        include("../footer.php");
        ?>
        <script>
            function yesnoCheck() {
                if (document.getElementById('yesCheck').selected) {
                    document.getElementById('ifYes').style.visibility = 'visible';
                } else {
                    document.getElementById('ifYes').style.visibility = 'hidden';
                }

            }

            function formSubmit() {
                document.getElementById('application').submit();
            }
        </script>
    </body>

    </html>