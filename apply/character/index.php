<?php
require("../../auth.php");
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$open = mysqli_query($conn, "SELECT open FROM app_types WHERE name='character';");
if(mysqli_fetch_assoc($open)['open'] == 1){
    header("Location: ../?closed=1&type=char");
    exit();
}
$hasApp = mysqli_query($conn, "SELECT submitted FROM char_applications WHERE status != 5 AND uuid='" . $_SESSION['UUID'] . "';");
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
$chars = array();
$charq = mysqli_query($conn, "SELECT name FROM available_characters ORDER BY name ASC;");
if($hasApp && mysqli_num_rows($charq) > 0){
    while($row = mysqli_fetch_assoc($charq)){
        array_push($chars, $row['name']);
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
        <?php $page = "Apply | Character";
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
                                                <span class="caption-subject bold uppercase"> Agreement</span>
                                            </div>
                                        </div>
                                        <div class="portlet body form">
                                            <label class="text">By applying for a Character you agree to the following terms:</label>
                                            <br>
                                            <ul>
                                                <li>You are willing <i>and</i> able to purchase or use a secondary account. (do not purchase until AFTER acceptance)</li>
                                                <li>You are 13 years old or older</li>
                                                <li>You will be required to:</li>
                                                <ul>
                                                    <li>Attend meetings and trainings</li>
                                                    <li>Actively use our Mumble server</li>
                                                    <li>Hear and talk to others while on Mumble</li>
                                                    <li>Be active on our Character Slack</li>
                                                </ul>
                                                <li>The character name you begin with should stay the same unless otherwise approved by your group's Character Attendant</li>
                                                <li>You are expected to stay current with both the MCMagic and character based rules</li>
                                            </ul>
                                            <label class="text"><b>Important:</b></label>
                                            <ul>
                                                <li>Upon loss of a character tag for any reason, you will NOT be compensated</li>
                                                <li>Managers and Character Attendants reserve the right to remove a character tag</li>
                                                <li>Please fill out the following application truthfully and to the best of your ability.</li>
                                            </ul>
                                            <br>
                                            <label class="text">What Characters can you apply for this month?</label>
                                            <ul>
                                                <?php
                                                    foreach($chars as $char){
                                                        echo '<li>' . $char . '</li>';
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="portlet-title">
                                            <div class="caption font-blue">
                                                <i class="icon-settings font-blue"></i>
                                                <span class="caption-subject bold uppercase"> Character Application</span>
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
                                                    <label for="experience" class="control-label">Do you currently or have you previously worked as a Character?</label>
                                                    <select id="experience" name="experience" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true" onchange="javascript:yesnoCheck();">
                                                        <option value="yes" id="yesCheck">Yes</option>
                                                        <option value="no" selected id="noCheck">No</option>
                                                    </select>
                                                    <br/>
                                                    <div id="ifYes" style="visibility:hidden">
                                                        <label class="text">Please list your current/previous Characters:</label>
                                                        <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="List Characters" name="characters" />
                                                    </div>
                                                    <label for="mumble" class="control-label">Are you active on our Mumble Server?</label>
                                                    <select id="mumble" name="mumble" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                    <br/>
                                                    <label for="account" class="control-label">Are you able to get a secondary account within 30 days after acceptance?</label>
                                                    <select id="account" name="account" class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                        <option value="yes">Yes</option>
                                                        <option value="no" selected>No</option>
                                                    </select>
                                                    <br/>
                                                    <label class="text">What Character are you applying for?</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Baymax" name="applying" required />
                                                    <label for="quote" class="control-label">Please quote this Character:</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Hello, I am Baymax, your personal healthcare companiion." name="quote" required />
                                                    <br/>
                                                    <center>
                                                        <h4 style="color:red">Check over your application! You can only submit once a month.</h4>
                                                        <button type="button" class="btn blue btn-outline" onclick="formSubmit()">Submit!</button>
                                                    </center>
                                                </form>
                                                <?php
                                            } else {
                                                ?>
                                                    <h3>You've already submitted a Character application this month!</h3>
                                                    <h4>You can only submit one application each month. Return to your <a href="../../profile/">MyMCMagic Profile.</a></h4>
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
        include("../../footer.php");
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