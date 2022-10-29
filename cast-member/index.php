<?php
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    function getName($v){
        switch($v){
            case "owner":
                return "Owner";
            case "manager":
                return "Manager";
            case "developer":
                return "Developer";
            case "coordinator":
                return "Coordinator";
            case "castmember":
                return "Cast Member";
            case "earningmyears":
                return "Earning My Ears";
        }
    }
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
    $username = sanitize(str_replace("/cast-member/","",$_GET['name']));
    if(!isset($username) || $username == ''){
        header("Location: /people/team/");
        exit();
    }
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "MainServer");
    $q = mysqli_query($conn, "SELECT uuid,username,rank FROM player_data WHERE username='" . $username . "' AND (rank='earningmyears' OR rank='castmember' OR rank='coordinator' OR rank='developer' OR rank='manager' OR rank='owner');");
    if(mysqli_num_rows($q) <= 0){
        header("Location: /people/team/");
        exit();
    }
    $arr = mysqli_fetch_array($q);
    $uuid = $arr['uuid'];
    $username = $arr['username'];
    $rank = getName($arr['rank']);
    $rankcolor = getrankcolor($arr['rank']);
    mysqli_select_db($conn, "mymcmagic");
    $data = mysqli_query($conn, "SELECT bio,twitter,instagram,beam,location,birthday,favchar,favdispark,favdisride,favdismov FROM staff WHERE uuid='" . $uuid . "';");
    $arr = mysqli_fetch_array($data);
    $bio = nl2br(preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $arr['bio']));
    $twitter = str_replace('@','',$arr['twitter']);
    $instagram = strtolower(str_replace('@','',$arr['instagram']));
    $beam = $arr['beam'];
    $location = $arr['location'];
    $birthday = $arr['birthday'];
    $character = $arr['favchar'];
    $dispark = $arr['favdispark'];
    $disride = $arr['favdisride'];
    $dismov = $arr['favdismov'];
    mysqli_select_db($conn, "MainServer");
    $rc = mysqli_query($conn, "SELECT name,COUNT(*) AS count FROM ride_counter WHERE uuid='" . $uuid . "' GROUP BY name ORDER BY count DESC LIMIT 0,1;");
    $arr = mysqli_fetch_array($rc);
    $topname = $arr['name'];
    $topcount = $arr['count'];
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
        <?php $page = 'The MCMagic Team - ' . $username;
        include("../header.php");
        ?>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1" name="viewport" />
            <meta content="Cast Member Bio: <?php echo $username; ?>" name="description" />
            <meta content="xBrant, Turner and Legobuilder" name="author" />
            <meta name="twitter:card" content="summary" />
            <meta name="twitter:site" content="@MCMagicParks" />
            <meta name="twitter:title" content="Cast Member Bio - <?php echo $username; ?>" />
            <meta name="twitter:description" content="Learn more about our <?php echo ($rank . ' ' . $username); ?> on their Bio page!" />
            <meta name="twitter:image" content="https://minotar.net/helm/<?php echo $username; ?>/256" />
            <!-- BEGIN GLOBAL MANDATORY STYLES -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <link href="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL STYLES -->
            <link href="../assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
            <link href="../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css" />
            <!-- END THEME GLOBAL STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="../assets/pages/css/profile.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL STYLES -->
            <!-- BEGIN LAYOUT STYLES -->
            <link href="../assets/layout/css/layout.css" rel="stylesheet" type="text/css" />
            <link href="../assets/layout/css/default.css" rel="stylesheet" type="text/css" id="style_color" />
            <link href="../assets/layout/css/custom.css" rel="stylesheet" type="text/css" />
            <!-- END LAYOUT STYLES -->
    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-md">
        <input type='hidden' id='activity_current_page' />
        <input type='hidden' id='activity_show_per_page' />
        <input type='hidden' id='activity_number_of_pages' />
        <input type='hidden' id='friends_current_page' />
        <input type='hidden' id='friends_show_per_page' />
        <input type='hidden' id='friends_number_of_pages' />
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
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
                                                <img src="https://minotar.net/helm/<?php echo $username; ?>/256" class="img-responsive" alt=""> </div>
                                            <!-- END SIDEBAR USERPIC -->
                                            <!-- SIDEBAR USER TITLE -->
                                            <div class="profile-usertitle">
                                                <div class="profile-usertitle-name">
                                                    <?php echo $username; ?>
                                                </div>
                                                <br>
                                                <div class="profile-usertitle-loc">
                                                    <?php echo $location; ?>
                                                </div>
                                                <div class="profile-usertitle-job">
                                                    <div <?php echo ($rankcolor !='' ? 'class="' . $rankcolor . '"' : '') ?>>
                                                        <?php echo $rank; ?>
                                                    </div>
                                                    <?php
                                                        if($uuid == $_SESSION['UUID']){
                                                            echo '<br><a href="/castmember/bio/">Edit</a>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <br>
                                            <!-- END SIDEBAR USER TITLE -->
                                        </div>
                                        <!-- END PORTLET MAIN -->
                                    </div>
                                    <!-- END BEGIN PROFILE SIDEBAR -->
                                    <!-- BEGIN PROFILE CONTENT -->
                                    <div class="profile-content">
                                        <!-- Moderation Log -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN BIO -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Bio</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <?php
                                                        if($bio == ''){
                                                            echo '<h4>This Cast Member hasn\'t written a Bio!</h4>';
                                                        }
                                                        ?>
                                                            <p>
                                                                <?php echo $bio; ?>
                                                            </p>
                                                    </div>
                                                </div>
                                                <!-- END BIO -->
                                                <!-- BEGIN FUN STUFF -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Fun Stuff</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <?php
                                                        if($birthday == '' && $character == '' && $dispark == '' && $disride == '' && $dismov == '' && $topcount == '' && $topname == ''){
                                                            echo '<h4>This Cast Member hasn\'t added any Fun Stuff!</h4>';
                                                        }
                                                        if($birthday != ''){
                                                            echo '<p>Birthday: ' . $birthday . '</p>';
                                                        }
                                                        if($character != ''){
                                                            echo '<p>Favorite Disney Character: ' . $character . '</p>';
                                                        }
                                                        if($dispark != ''){
                                                            echo '<p>Favorite Disney Park: ' . $dispark . '</p>';
                                                        }
                                                        if($disride != ''){
                                                            echo '<p>Favorite Disney Park Ride: ' . $disride . '</p>';
                                                        }
                                                        if($dismov != ''){
                                                            echo '<p>Favorite Disney Movie: ' . $dismov . '</p>';
                                                        }
                                                        if($topcount != '' && $topname != ''){
                                                            echo '<p>Top Ride: ' . $topname . ' - ' . $topcount . ' times</p>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- END FUN STUFF -->
                                                <!-- BEGIN SOCIAL MEDIA -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Social Media</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <?php
                                                        if($twitter == '' && $beam == '' && $instagram == ''){
                                                            echo '<h4>This Cast Member hasn\'t setup any Social Media links!</h4>';
                                                        }
                                                        if($twitter != ''){
                                                            echo '<p>Twitter: <a href="https://twitter.com/' . $twitter . '" target="_blank">@' . $twitter . '</a></p>';
                                                        }
                                                        if($beam != ''){
                                                            echo '<p>Beam: <a href="https://beam.pro/' . $beam . '" target="_blank">' . $beam . '</a></p>';
                                                        }
                                                        if($instagram != ''){
                                                            echo '<p>Instagram: <a href="https://www.instagram.com/' . $instagram . '" target="_blank">@' . $instagram . '</a></p>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- END SOCIAL MEDIA -->
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
                    <script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
                    <!-- END CORE PLUGINS -->
                    <!-- PAGINATION CODE -->
                    <script src="../assets/pages/scripts/activity_page.js"></script>
                    <script src="../assets/pages/scripts/friends_page.js"></script>
                    <!-- END PAGINATION CODE -->
                    <!-- BEGIN PAGE LEVEL PLUGINS -->
                    <script src="../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
                    <script src="../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
                    <!-- END PAGE LEVEL PLUGINS -->
                    <!-- BEGIN THEME GLOBAL SCRIPTS -->
                    <script src="../assets/global/scripts/app.min.js" type="text/javascript"></script>
                    <!-- END THEME GLOBAL SCRIPTS -->
                    <!-- BEGIN PAGE LEVEL SCRIPTS -->
                    <script src="../assets/pages/scripts/profile.min.js" type="text/javascript"></script>
                    <script src="../assets/pages/scripts/timeline.min.js" type="text/javascript"></script>
                    <!-- END PAGE LEVEL SCRIPTS -->
                    <!-- BEGIN THEME LAYOUT SCRIPTS -->
                    <script src="../assets/layout/scripts/layout.min.js" type="text/javascript"></script>
                    <script src="../assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
                    <!-- END THEME LAYOUT SCRIPTS -->
                </div>
                <?php mysqli_close($conn); ?>
            </div>
        </div>
    </body>

    </html>