<?php
    date_default_timezone_set('America/New_York');
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "MainServer");
    $ranks = array("owner", "manager", "developer", "coordinator", "castmember", "earningmyears");
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
                return "Earning My Ear";
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
    $total = 0;
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
        <?php $page = "The MCMagic Team";
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
            <link href="/assets/global/css/text.css" rel="stylesheet" type="text/css" />
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
                        <!-- BEGIN PROFILE CONTENT -->
                        <div class="profile-content">
                            <?php
                                foreach($ranks as $rank){
                                    $plural = 's';
                                    if($rank == 'owner'){
                                        $plural = '';
                                    }
                                    $cqry = "SELECT COUNT(rank) AS count FROM player_data WHERE rank='" . $rank ."' AND username != 'Cast_Member' AND username != 'MCMagicSecurity'";
                                    $lqry = "SELECT username FROM player_data WHERE rank='" . $rank ."' AND username != 'Cast_Member' AND username != 'MCMagicSecurity' ORDER BY `username` ASC";
                                    $cres = mysqli_query($conn, $cqry);
                                    $count = mysqli_fetch_array($cres)['count'];
                                    echo '<div class="row"><div class="col-md-12"><div class="portlet-title"><i class="icon-bar-chart theme-font hide"></i><span class="staff-cm caption-subject font-blue-madison bold uppercase"><div class="' . getrankcolor($rank) . '">Meet Your ' . getName($rank) . $plural . '! (' . $count . ')</div></span></div></div></div><br/>';
                                    //echo '<div class="portlet-title"><div class="caption font-blue"><span class="caption-subject bold uppercase"> ' . getName($rank) . ' (' . $count . '):</span></div></div>';
                                    $lres = mysqli_query($conn, $lqry);
                                    $amount = 0;
                                    echo '<div class="row">';
                                    while ($row = mysqli_fetch_array($lres)) {
                                        $usr = $row['username'];
                                        if($amount >= 4){
                                            echo '</div><div class="row">';
                                            $amount = 0;
                                        }
                                        echo '<div class="col-md-3"><div class="portlet light"><div class="portlet-body" style="text-align:center;"><div class="profile-userpic"><a href="https://my.mcmagic.us/cast-member/' . $usr . '"><img src="https://minotar.net/helm/' . $usr . '/256" class="img-responsive" alt=""></a></div><a href="https://my.mcmagic.us/cast-member/' . $usr . '"><h4>' . $usr . '</h4></a></div></div></div>';
                                        $amount++;
                                        $total++;
                                    }
                                    if($amount == 0 || $rank == 'earningmyears'){
                                        echo '<div class="col-md-3"><div class="portlet light"><div class="portlet-body" style="text-align:center;"><div class="profile-userpic"><a href="../apply/"><img src="https://minotar.net/helm/Cast_Member/256" class="img-responsive" alt=""></a></div><a href="../apply/"><h4>This could be you!</h4></a></div></div></div>';
                                    }
                                    echo '</div>';
                                }
                            ?>
                        </div>
                        <!-- END PROFILE CONTENT -->
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light">
                                        <div class="portlet-title">
                                            <div class="caption font-blue">
                                                <span class="caption-subject bold uppercase"> We currently have <?php echo $total; ?> Cast Members!</span>
                                            </div>
                                        </div>
                                        <div class="portlet body form">
                                            <h4>What is a Cast Member?</h4>
                                            <p>Cast Members help ensure our guests have enjoyable experiences while visiting our server. Whether directing Guests to rides or food locations, operating our rides/shows/attractions, or assisting Guests with any questions or concerns they might have, Cast Members have a direct impact on our Guests’ experiences.</p>
                                            <h4>What is Earning My Ears?</h4>
                                            <p>This is the position we assign to candidates for the Cast Member role for 30 days. During their time in this position, they will experience different training and team-building exercises to ensure they know how to moderate chat properly, direct Guests around the Parks and even run a show. If you’re interested in being a Cast Member on our server, <a href="../apply/">apply here!</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE CONTENT INNER -->
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
        <!-- PAGINATION CODE -->
        <script src="/assets/pages/scripts/activity_page.js"></script>
        <script src="/assets/pages/scripts/friends_page.js"></script>
        <!-- END PAGINATION CODE -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/assets/pages/scripts/profile.min.js" type="text/javascript"></script>
        <script src="/assets/pages/scripts/timeline.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

    </html>