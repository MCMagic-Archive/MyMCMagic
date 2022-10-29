<?php
require("../../auth.php");
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$open = mysqli_query($conn, "SELECT open FROM app_types WHERE name='developer';");
if(mysqli_fetch_assoc($open)['open'] == 1){
    header("Location: ../?closed=1&type=dev");
    exit();
}
$hasApp = mysqli_query($conn, "SELECT submitted FROM dev_applications WHERE status != 5 AND uuid='" . $_SESSION['UUID'] . "';");
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
        <?php $page = "Apply | Developer";
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
                                                <span class="caption-subject bold uppercase"> Developer Application</span>
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
                                                    <h2>What positions we're looking for:</h2>
                                                    <!--<h3>System Administrator</h3>
                                                <ul style="list-style-type: square;">
                                                    <li>Knowledge with Linux (Debian)</li>
                                                    <li>Experience with MySQL</li>
                                                    <li>Thorough understanding of Java</li>
                                                    <li>Able to work well with others and can meet deadlines on time</li>
                                                </ul>-->
                                                    <h3>Plugin Developer</h3>
                                                    <ul style="list-style-type: square;">
                                                        <li>Ability to utilize the Spigot/Bukkit API in creative and new ways</li>
                                                        <li>Knows their limitations - You know when you take on too many projects</li>
                                                        <li>Passionate about Minecraft and Disney</li>
                                                        <li>Able to work well with others and can meet deadlines on time</li>
                                                    </ul>
                                                    <!--<h3>Website Developer</h3>
                                                <ul style="list-style-type: square;">
                                                    <li>Extensive knowledge and experience with HTML, CSS, PHP, JavaScript and/or Python</li>
                                                    <li>Able to create awesome webpages</li>
                                                    <li>MySQL experience recommended, not required</li>
                                                    <li>Able to work well with others and can meet deadlines on time</li>
                                                </ul>-->
                                                    <h3>iOS Developer</h3>
                                                    <ul style="list-style-type: square;">
                                                        <li>Previous experience developing iOS Apps</li>
                                                        <li>Able to create an easy-to-use UI</li>
                                                        <li>Able to work well with others and can meet deadlines on time</li>
                                                    </ul>
                                                    <p>Even if you don't fit any of the listed positions, we are still looking for talented Developers. Send your Application anyway!</p>
                                                    <label class="text">What is your REAL name? (First and Last)</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Walt Disney" name="name" required />
                                                    <label class="text">What country, state, or timezone do you live in? (Provide as many as you want)</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="United States, NY, EST" name="location" required />
                                                    <label class="text">Please provide links to your previous work</label>
                                                    <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="GitHub, YouTube, etc." name="work" required />
                                                    <label for="why"> Why should you become the newest MCMagic Developer?</label>
                                                    <textarea class="form-control" id="why" name="why" rows="5"></textarea>
                                                    <br/>
                                                    <center>
                                                        <h4 style="color:red">Check over your application! You can only submit once every 30 days.</h4>
                                                        <button type="button" class="btn blue btn-outline" onclick="formSubmit()">Submit!</button>
                                                    </center>
                                                </form>
                                                <?php
                                            } else {
                                                ?>
                                                    <h3>You've already submitted a Developer application in the past 30 days!</h3>
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
        include("../../footer.php");
        ?>
        <script>
            function formSubmit() {
                document.getElementById('application').submit();
            }
        </script>
    </body>

    </html>