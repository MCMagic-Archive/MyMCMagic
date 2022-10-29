<?php
    require("../auth.php");
    class Ticket
    {
        public $id = null;
        public $subject = null;
        public $status = null;
        public $time = null;
        public $messages = null;
        
        function setid($v){
            $this->id = $v;
        }
        
        function setsubject($v){
            $this->subject = $v;
        }
        
        function setstatus($v){
            $this->status = $v;
        }
        
        function settime($v){
            $this->time = $v;
        }
        
        function setmessages($v){
            $this->messages = $v;
        }
        
        function getid(){
            return $this->id;
        }
        
        function getsubject(){
            return $this->subject;
        }
        
        function getstatus(){
            return $this->status;
        }
        
        function gettime(){
            return $this->time;
        }
        
        function getmessages(){
            return $this->messages;
        }
    }
    class Message
    {
        public $uuid = null;
        public $message = null;
        public $time = null;
        public $user = null;
        
        function setuuid($v){
            $this->uuid = $v;
        }
        
        function setmessage($v){
            $this->message = $v;
        }
        
        function settime($v){
            $this->time = $v;
        }
        
        function setuser($v){
            $this->user = $v;
        }
        
        function getuuid(){
            return $this->uuid;
        }
        
        function getmessage(){
            return $this->message;
        }
        
        function gettime(){
            return $this->time;
        }
        
        function getuser(){
            return $this->user;
        }
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
    function getStatusTag($var){
        switch ($var){
            case '0':
                return "label-danger";
            case '1':
                return "label-success";
            case '2':
            case '3':
                return "label-warning";
        }
    }
    function getStatus($var){
        switch ($var){
            case '0':
                return "Closed";
            case '1':
                return "Open";
            case '2':
                return "Guest Reply";
            case '3':
                return "CM Reply";
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
    $names = array();

    #TICKET
    if(!isset($_GET['id']) || $_GET['id']==''){
        header("Location: ../support/");
        exit();
    }
    $id = $_GET['id'];
    mysqli_select_db($conn, "mymcmagic");
    $subject = '';
    $status = 0;
    $time = 0;
    $messages = array();
    $tq = mysqli_query($conn, "SELECT subject,status,time FROM ticketids WHERE ticketid='" . $id . "' AND uuid='" . $uuid . "'");
    if ($tq && mysqli_num_rows($tq) > 0){
        $row = mysqli_fetch_assoc($tq);
        $subject = $row['subject'];
        $status = $row['status'];
        $time = $row['time'];
    }
    if($subject == ''){
        header("Location: /support");
        exit();
    }
    $tkts = mysqli_query($conn, "SELECT uuid,message,time FROM tickets WHERE ticketid='" . $id . "' ORDER BY time ASC;");
    if ($tkts && mysqli_num_rows($tkts) > 0) {
        while($row = mysqli_fetch_assoc($tkts)) {
            $msg = new Message();
            $msg->setuuid($row['uuid']);
            $msg->setmessage(nl2br(preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $row['message'])));
            $msg->settime($row['time']);
            array_push($messages, $msg);
        }
    }
    mysqli_select_db($conn, "MainServer");
    foreach($messages as $msg){
        if(isset($names[$msg->getuuid()])){
            continue;
        }
        $names[$msg->getuuid()] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM player_data WHERE uuid='" . $msg->getuuid() . "';"))['username'];
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
        <?php $page = "View Ticket";
        include("../header.php");
        ?>

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
                                                        <a href=".">
                                                            <i class="fa fa-home"></i> Profile </a>
                                                    </li>
                                                    <li>
                                                        <a href="settings/">
                                                            <i class="fa fa-cogs"></i> Account Settings </a>
                                                    </li>
                                                    <li class="active">
                                                        <a href="../support/">
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
                                            <div class="row list-separated profile-stat">
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
                                    <h2><span class="profile-usertitle-name">Status: </span><span class="label <?php echo getStatusTag($status); ?>"><?php echo getStatus($status); ?></span> | <span class="profile-usertitle-name">Subject: <?php echo $subject; ?></span> </h2>
                                    <br/>
                                    <div class="profile-content">
                                        <?php
                                        $reply = false;
                                        foreach($messages as $msg){
                                            $usr = $names[$msg->getuuid()];
                                            $time = date('n/j/y g:i A', $msg->gettime());
                                            $body = $msg->getmessage();
                                            echo '<div class="row"><div class="col-md-12"><div class="portlet light "><div class="portlet-title"><div class="caption caption-md"><i class="icon-bar-chart theme-font hide"></i><span class="caption-subject font-blue-madison bold">' . ($reply ? 'Reply From' : 'Request By') .' ' . $usr . ' - ' . $time . '</span></div></div><div class="portlet-body"><div class="row number-stats"><p>' . $body . '</p></div></div></div></div></div>';
                                            $reply = true;
                                        }
                                        if($subject != 'Ban Appeal' || ($subject == 'Ban Appeal' && $status != 0)){
                                        ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="portlet light ">
                                                        <div class="portlet-body">
                                                            <div class="row number-stats">
                                                                <form id="response" class="account-settings-form" action="update.php" method="post">
                                                                    <input type="hidden" name="uuid" value="<?php echo $uuid; ?>">
                                                                    <input type="hidden" name="ticketid" value="<?php echo $id; ?>">
                                                                    <input type="hidden" id="toggle" name="toggle" value="<?php echo $status; ?>">
                                                                    <input type="hidden" name="status" value="<?php echo $status; ?>">
                                                                    <input type="hidden" name="subject" value="<?php echo $subject; ?>">
                                                                    <div class="form-group">
                                                                        <label class="control-label visible-ie8 visible-ie9">Enter your reply here...</label>
                                                                        <div class="controls">
                                                                            <div class="input-icon">
                                                                                <i class="fa fa-info-circle"></i>
                                                                                <textarea id="message" class="form-control placeholder-no-fix" type="text" placeholder="Enter your reply here..." name="message" style="resize:none;"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div id="register_tnc_error"> </div>
                                                                    </div>
                                                                    <div class="form-actions">
                                                                        <button type="submit" id="register-submit-btn" class="btn blue pull-right"> Submit Reply </button>
                                                                    </div>
                                                                    <?php
                                                                            if($subject != 'Ban Appeal'){
                                                                                if($status != 0){
                                                                                    echo '<button type="submit" id="toggle-btn" class="btn red pull-left"> Close Ticket </button>';
                                                                                }else{
                                                                                    echo '<button type="submit" id="toggle-btn" class="btn green pull-left"> Open Ticket </button>';
                                                                                }
                                                                            }
                                                                        ?>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                            ?>
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
                <script src="../assets/pages/scripts/new_ticket.js" type="text/javascript"></script>
                <script>
                    autosize(document.getElementById('message'));
                    document.getElementById('toggle-btn').onclick = function () {
                        document.getElementById('toggle').value = "<?php echo ($status != 0 ? "
                        0 " : "
                        1 "); ?>";
                        document.getElementById('response').submit();
                    }
                </script>
                <!-- END CORE PLUGINS -->
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