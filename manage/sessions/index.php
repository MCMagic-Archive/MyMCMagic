<?php
    require("../../auth.php");
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
    function getStatus($var){
        switch(intval($var)){
            case 0:
                return "Not Confirmed";
            case 1:
                return "Active";
        }
        return "Suspended";
    }
    function getStatusTag($var){
        switch(intval($var)){
            case '0':
                return "label-warning";
            case '1':
                return "label-success";
            case '2':
                return "label-danger";
        }
        return "label-success";
    }
    class Account
    {
        public $uuid = null;
        public $username = null;
        public $email = null;
        public $status = null;
        public $rank = null;
        
        public function setuuid($v){
            $this->uuid = $v;
        }
        
        public function setusername($v){
            $this->username = $v;
        }
        
        public function setemail($v){
            $this->email = $v;
        }
        
        public function setstatus($v){
            $this->status = $v;
        }
        
        public function setrank($v){
            $this->rank = $v;
        }
        
        public function getuuid(){
            return $this->uuid;
        }
        
        public function getusername(){
            return $this->username;
        }
        
        public function getemail(){
            return $this->email;
        }
        
        public function getstatus(){
            return $this->status;
        }
        
        public function getrank(){
            return $this->rank;
        }
    }
    $uuid = $_SESSION['UUID'];
    $username = $_SESSION['USERNAME'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    
    #MAIN DATA
    mysqli_select_db($conn, "MainServer");
    $main = mysqli_query($conn, "SELECT rank,balance,tokens FROM player_data WHERE uuid='" . $uuid . "';");
    $rank = "Guest";
    $rankcolor = '';
    $bal = 0;
    $tokens = 0;
    if (mysqli_num_rows($main) > 0) {
        while($row = mysqli_fetch_assoc($main)) {
            $rank = getrankname($row['rank']);
            $rankcolor = getrankcolor($row['rank']);
            $bal = $row['balance'];
            $tokens = $row['tokens'];
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
        <?php $page = "Manage Sessions";
        include("../../header.php");
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
            <link href="/assets/global/css/text.css" rel="stylesheet" id="style_components" type="text/css" />
            <!-- END THEME GLOBAL STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="/assets/pages/css/profile.css" rel="stylesheet" type="text/css" />
            <!-- END PAGE LEVEL STYLES -->
            <!-- BEGIN LAYOUT STYLES -->
            <link href="/assets/layout/css/layout.css" rel="stylesheet" type="text/css" />
            <link href="/assets/layout/css/default.css" rel="stylesheet" type="text/css" id="style_color" />
            <link href="/assets/layout/css/custom.css" rel="stylesheet" type="text/css" />
            <link href="/assets/custom.css" rel="stylesheet" type="text/css" />
            <style>
                .td {
                    font-size: 14px;
                }
                
                table tr.header {
                    background-color: white;
                }
                
                #button {
                    font-size: 16px;
                    border-radius: 25px;
                    #background: #39e600;
                    padding: 20px;
                }
            </style>
            <!-- END LAYOUT STYLES -->

    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-md">
        <input type='hidden' id='account_current_page' />
        <input type='hidden' id='account_show_per_page' />
        <input type='hidden' id='account_number_of_pages' />
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
                                                        <a href="../../manage/">
                                                            <i class="fa fa-wrench" aria-hidden="true"></i> Manage </a>
                                                    </li>
                                                    <li class="active">
                                                        <a href=".">
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> Sessions </a>
                                                    </li>
                                                    <li>
                                                        <a href="../appeals/">
                                                            <i class="fa fa-gavel" aria-hidden="true"></i> Ban Appeals </a>
                                                    </li>
                                                    <li>
                                                        <a href="../accounts/">
                                                            <i class="fa fa-users" aria-hidden="true"></i> Accounts </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- END MENU -->
                                        <!-- SIDEBAR MENU -->
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
                                        <!-- END MENU -->
                                        <!-- END PORTLET MAIN -->
                                    </div>
                                    <!-- END BEGIN PROFILE SIDEBAR -->
                                    <!-- BEGIN PROFILE CONTENT -->
                                    <div class="profile-content">
                                        <!-- Player Lookup -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <!-- Player Lookup -->
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Session History Lookup</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <!-- Playaer Basic Info-->
                                                        <div class="row number-stats margin-bottom-30">
                                                            <form class="account-settings-form" id="form">
                                                                <div class="form-group">
                                                                    <p>Search for a Cast Member's session history:</p>
                                                                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                                                                    <div class="input-icon">
                                                                        <i class="fa fa-users"></i>
                                                                        <input class="form-control placeholder-no-fix" type="text" placeholder="Guest Username" name="username" id="input" required>
                                                                    </div>
                                                                </div>
                                                                <button class="btn blue btn-outline" id="submit">LOOKUP</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Search Results</span>
                                                        </div>
                                                    </div>
                                                    <div class="table-scrollable-borderless">
                                                        <img id="loading_spinner" src="../../loading.gif" style="display:none;margin: 0 auto;">
                                                        <div id="lookup"></div>
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
            <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <!-- PAGINATION CODE -->
            <script src="/assets/pages/scripts/account_page.js"></script>
            <script>
                $('#form').submit(function (event) {
                    event.preventDefault();
                    $('#lookup').html("");
                    $('#loading_spinner').css("display", "block");
                    $('#loading_spinner').css("margin", "0 auto");
                    var post_data = "username=" + $('#input').val();
                    $.ajax({
                        url: 'session.php',
                        type: 'POST',
                        data: post_data,
                        dataType: 'html',
                        success: function (data) {
                            $('#lookup').html(data);
                            //Moved the hide event so it waits to run until the prior event completes
                            //It hide the spinner immediately, without waiting, until I moved it here
                            $('#loading_spinner').hide();
                        }
                    });
                });
            </script>
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