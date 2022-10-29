<?php
    require("../../auth.php");
    class Friend
    {
        public $uuid = null;
        public $username = null;
        public $rank = null;
        public $status = null;
        
        public function setuuid($v){
            $this->uuid = $v;
        }
        
        public function setusername($v){
            $this->username = $v;
        }
        
        public function setrank($v){
            $this->rank = $v;
        }
        
        public function setstatus($v){
            $this->status = $v;
        }
        
        public function getuuid(){
            return $this->uuid;
        }
        
        public function getusername(){
            return $this->username;
        }
        
        public function getrank(){
            return $this->rank;
        }
        
        public function getstatus(){
            return $this->status;
        }
    }
    date_default_timezone_set('America/New_York');
    function getStatusTag($var){
        switch ($var){
            case '0':
                return "label-warning";
            case '1':
                return "label-success";
        }
    }
    function getStatus($var){
        switch ($var){
            case '0':
                return "Pending";
            case '1':
                return "Approved";
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
    $uuid = $_SESSION['UUID'];
    $username = $_SESSION['USERNAME'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");

    #MAIN DATA
    mysqli_select_db($conn, "MainServer");
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

    #FRIENDS
    mysqli_select_db($conn, "MainServer");
    $fres = mysqli_query($conn, "SELECT sender,receiver,status FROM friends WHERE sender='" . $uuid . "' OR receiver='" . $uuid . "';");
    $frienduuids = array();
    if(mysqli_num_rows($fres) > 0){
        while($row = mysqli_fetch_assoc($fres)){
            if($row['sender'] == $uuid){
                $frienduuids[$row['receiver']] = $row['status'];
            }else{
                $frienduuids[$row['sender']] = $row['status'];
            }
        }
    }
    $friends = array();
    if(sizeof($frienduuids) > 0){
        $friendkeys = array_keys($frienduuids);
        $qry = "SELECT uuid,username,rank FROM player_data WHERE uuid='";
        for ($x = 0; $x <= sizeof($friendkeys); $x++) {
            if($x == (sizeof($friendkeys)-1)){
                $qry .= ($friendkeys[$x] . "' ORDER BY username ASC;");
                break;
            }
            $qry .= ($friendkeys[$x] . "' OR uuid='");
        }
        $getnames = mysqli_query($conn, $qry);
        if(mysqli_num_rows($getnames) > 0){
            while($row = mysqli_fetch_assoc($getnames)){
                $status = 0;
                foreach($frienduuids as $fuuid => $stat){
                    if($fuuid == $row['uuid']){
                        $status = $stat;
                        break;
                    }
                }
                $f = new Friend();
                $f->setuuid($fuuid);
                $f->setusername($row['username']);
                $f->setrank($row['rank']);
                $f->setstatus($status);
                array_push($friends, $f);
            }
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
        <?php $page = "Friends";
        include("../../header.php");
        ?>

            <!-- BEGIN GLOBAL MANDATORY STYLES -->
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
            <link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.css" rel="stylesheet" type="text/css" />
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN THEME GLOBAL STYLES -->
            <link href="../../assets/global/css/components.css" rel="stylesheet" id="style_components" type="text/css" />
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
                <!-- BEGIN CONTENT BODY -->
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
                                                <img <?php echo 'src="https://minotar.net/helm/' . $username . '/256"'; ?> class="img-responsive" alt=""> </div>
                                            <!-- END SIDEBAR USERPIC -->
                                            <!-- SIDEBAR USER TITLE -->
                                            <div class="profile-usertitle">
                                                <div class="profile-usertitle-name">
                                                    <?php echo $username; ?>
                                                </div>
                                                <div class="profile-usertitle-job">
                                                    <div <?php echo ($rankcolor !='' ? 'class="' . $rankcolor . '"' : '') ?>>
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
                                                    <li>
                                                        <a href="../settings/">
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
                                                            <span class="caption-subject font-blue-madison bold uppercase">How To Make New Friends</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row number-stats margin-bottom-30">
                                                            <p class="mc-text">/friend add [Username] - Send Friend Request</p>
                                                            <p class="mc-text">/friend accept [Username] - Accept a Friend Request</p>
                                                            <p class="mc-text">/friend deny [Username] - Deny a Friend Request</p>
                                                            <p class="mc-text">/friend remove [Username] - Remove a Friend</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Your Friends<?php echo ' (' . sizeof($friends) . ')' ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row number-stats margin-bottom-30">

                                                            <div class="table-scrollable-borderless">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr style="height:32px">
                                                                            <th></th>
                                                                            <th> Username </th>
                                                                            <th> Rank </th>
                                                                            <th> Status </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="friends">
                                                                        <?php
                                                                            foreach($friends as $f){
                                                                                echo '<tr><td><img src="https://minotar.net/helm/' . $f->getusername() . '/32" style="height:20px;width:20px"></td><td> ' . $f->getusername() . ' </td><td> ' . getrankname($f->getrank()) . ' </td><td><span class="label label-sm ' . getStatusTag($f->getstatus()) . '"> ' . getStatus($f->getstatus()) . ' </span></td></tr>';
                                                                            }
                                                                            $mod = sizeof($friends) % 7;
                                                                            if($mod != 0){
                                                                                for($i = 0; $i < (7 - $mod); $i++){
                                                                                    echo '<tr style="height:38px"><td>&nbsp;</td><td> </td><td> </td><td> </td></tr>';
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                <center>
                                                                    <ul class="pagination" id="friends_page_navigation"></ul>
                                                                </center>
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
                <!-- PAGINATION CODE -->
                <script src="../../assets/pages/scripts/friends_page.js"></script>
                <!-- END PAGINATION CODE -->
                <!-- BEGIN THEME GLOBAL SCRIPTS -->
                <script src="../../assets/global/scripts/app.min.js" type="text/javascript"></script>
                <!-- END THEME GLOBAL SCRIPTS -->
                <!-- BEGIN THEME LAYOUT SCRIPTS -->
                <script src="../../assets/layout/scripts/layout.min.js" type="text/javascript"></script>
                <!-- END THEME LAYOUT SCRIPTS -->
            </div>
        </div>
    </body>

    </html>