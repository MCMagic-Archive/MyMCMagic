<?php
    require("../../auth.php");
    class Ticket
    {
        public $id = null;
        public $uuid = null;
        public $username = null;
        public $subject = null;
        public $status = null;
        public $time = null;
        public $messages = null;
        
        function setid($v){
            $this->id = $v;
        }
        
        function setuuid($v){
            $this->uuid = $v;
        }
        
        function setusername($v){
            $this->username = $v;
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
        
        function getuuid(){
            return $this->uuid;
        }
        
        function getusername(){
            return $this->username;
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
        
        function setuuid($v){
            $this->uuid = $v;
        }
        
        function setmessage($v){
            $this->message = $v;
        }
        
        function settime($v){
            $this->time = $v;
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

    #TICKETS
    mysqli_select_db($conn, "mymcmagic");
    $tickets = array();
    $closed = array();
    $tq = mysqli_query($conn, "SELECT ticketid,uuid,subject,status,time FROM ticketids WHERE subject='Ban Appeal' ORDER BY time DESC");
    if ($tq && mysqli_num_rows($tq) > 0){
        while($row = mysqli_fetch_assoc($tq)){
            $ticket = new Ticket();
            $ticket->setid($row['ticketid']);
            $ticket->setuuid($row['uuid']);
            $ticket->setsubject($row['subject']);
            $ticket->setstatus($row['status']);
            $ticket->settime($row['time']);
            if(intval($row['status'])==0){
                array_push($closed, $ticket);
            }else{
                array_push($tickets, $ticket);
            }
        }
    }
    for($i = 0; $i < sizeof($tickets); $i++){
        $ticket = $tickets[$i];
        $tkts = mysqli_query($conn, "SELECT uuid,message,time FROM tickets WHERE ticketid='" . $ticket->getid() . "' ORDER BY id DESC LIMIT 0,1;");
        $msgs = array();
        if ($tkts && mysqli_num_rows($tkts) > 0) {
            while($row = mysqli_fetch_assoc($tkts)) {
                $msg = new Message();
                $msg->setuuid($row['uuid']);
                $msg->setmessage($row['message']);
                $msg->settime($row['time']);
                array_push($msgs, $msg);
            }
        }
        $ticket->setmessages($msgs);
    }
    for($i = 0; $i < sizeof($closed); $i++){
        $ticket = $closed[$i];
        $tkts = mysqli_query($conn, "SELECT uuid,message,time FROM tickets WHERE ticketid='" . $ticket->getid() . "' ORDER BY id DESC LIMIT 0,1;");
        $msgs = array();
        if ($tkts && mysqli_num_rows($tkts) > 0) {
            while($row = mysqli_fetch_assoc($tkts)) {
                $msg = new Message();
                $msg->setuuid($row['uuid']);
                $msg->setmessage($row['message']);
                $msg->settime($row['time']);
                array_push($msgs, $msg);
            }
        }
        $ticket->setmessages($msgs);
    }
    mysqli_select_db($conn, "MainServer");
    $uuids = array();
    $tlist = array_merge($tickets, $closed);
    foreach($tlist as $t){
        if(in_array($t->getuuid(), $uuids)){
            continue;
        }
        array_push($uuids, $t->getuuid());
    }
    $q = "SELECT uuid,username FROM player_data WHERE ";
    $f = true;
    foreach($uuids as $id){
        if(!$f){
            $q .= ' OR ';
        }
        $q .= "uuid='" . $id . "'";
        $f = false;
    }
    $qry = mysqli_query($conn, $q . ';');
    $names = array();
    while($row = mysqli_fetch_assoc($qry)) {
        $names[$row['uuid']] = $row['username'];
    }
    foreach($tlist as $t){
        if(isset($names[$t->getuuid()]) && $names[$t->getuuid()] != ''){
            $t->setusername($names[$t->getuuid()]);
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
        <?php $page = "Ban Appeals";
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
        <input type='hidden' id='tickets_current_page' />
        <input type='hidden' id='tickets_show_per_page' />
        <input type='hidden' id='tickets_number_of_pages' />
        <input type='hidden' id='closed_current_page' />
        <input type='hidden' id='closed_show_per_page' />
        <input type='hidden' id='closed_number_of_pages' />
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
                                                    <li>
                                                        <a href="../sessions/">
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> Sessions </a>
                                                    </li>
                                                    <li class="active">
                                                        <a href=".">
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
                                    <!-- END PROFILE SIDEBAR -->
                                    <!-- BEGIN PROFILE CONTENT -->
                                    <div class="profile-content">

                                        <!-- Player Lookup -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <!-- Begin Support Tickets -->
                                                <?php
                                                if($_GET['tag']=='not-trained'){
                                                    echo '<div class="alert alert-danger"><button class="close" data-close="alert"></button><span>You have not been trained to answer Support Tickets! Contact a Manager to be trained.</span></div>';
                                                }
                                                    ?>
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">Ban Appeals</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <!-- Open Tickets -->
                                                            <div class="row">
                                                                <!-- BEGIN PORTLET -->
                                                                <div class="portlet light ">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-md">
                                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                                            <span class="caption-subject font-blue-madison uppercase">Open Ban Appeals</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="row number-stats margin-bottom-30">
                                                                            <div class="table-scrollable-borderless">
                                                                                <?php
                                                                            if(empty($tickets)){
                                                                                echo '<h3 style="text-align:center">No open tickets, good job! &#127881;</h3>';
                                                                            }else{
                                                                                echo '<table class="table table-hover"><thead><tr><th> Last Update </th><th> Submitter </th><th> Subject </th><th> Last Response </th><th> Status </th></tr></thead><tbody id="tickets">';
                                                                                foreach($tickets as $ticket){
                                                                                    if(sizeof($ticket->getmessages()) <= 0){
                                                                                        continue;
                                                                                    }
                                                                                    $i = sizeof($ticket->getmessages()) - 1;
                                                                                    $resp = $ticket->getmessages()[$i]->getmessage();
                                                                                    echo '<tr id="ticket-' . $ticket->getid() . '"><td> ' . date('n/j/y g:i A', $ticket->gettime()) . ' </td><td>' . $ticket->getusername() . '</td><td style="text-overflow:ellipsis;max-width:200px;overflow:hidden;white-space:nowrap;"> ' . $ticket->getsubject() . ' </td><td style="text-overflow:ellipsis;max-width:250px;overflow:hidden;white-space:nowrap;">' . $resp . '</td><td><span class="label label-sm ' . getStatusTag($ticket->getstatus()) . '"> ' . getStatus($ticket->getstatus()) . ' </span></td><td><a href="ticket.php?id=' . $ticket->getid() . '"> View </a></td></tr>';
                                                                                }
                                                                                $mod = sizeof($tickets) % 5;
                                                                                if($mod != 0){
                                                                                    for($i = 0; $i < (5 - $mod); $i++){
                                                                                        echo '<tr style="height:36px"><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td></tr>';
                                                                                    }
                                                                                }
                                                                                echo '</tbody></table><center><ul class="pagination" id="tickets_page_navigation"></ul><div><input id="page_input" type="number" style="padding: 6px 12px;width:10%;" value="1"><a><input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page();"></a></div></center>';
                                                                            }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Archived Tickets -->
                                                            <div class="row">
                                                                <!-- BEGIN PORTLET -->
                                                                <div class="portlet light ">
                                                                    <div class="portlet-title">
                                                                        <div class="caption caption-md">
                                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                                            <span class="caption-subject font-blue-madison uppercase">Closed Ban Appeals</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="row number-stats margin-bottom-30">
                                                                            <div class="table-scrollable-borderless">
                                                                                <table class="table table-hover">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th> Last Update </th>
                                                                                            <th> Submitter </th>
                                                                                            <th> Subject </th>
                                                                                            <th> Last Response </th>
                                                                                            <th> Status </th>
                                                                                            <th></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="closed">
                                                                                        <?php
                                                                                    foreach($closed as $ticket){
                                                                                        if(sizeof($ticket->getmessages()) <= 0){
                                                                                            continue;
                                                                                        }
                                                                                        $i = sizeof($ticket->getmessages()) - 1;
                                                                                        $resp = $ticket->getmessages()[$i]->getmessage();
                                                                                        echo '<tr id="ticket-' . $ticket->getid() . '"><td> ' . date('n/j/y g:i A', $ticket->gettime()) . ' </td><td>' . $ticket->getusername() . '</td><td style="text-overflow:ellipsis;max-width:200px;overflow:hidden;white-space:nowrap;"> ' . $ticket->getsubject() . ' </td><td style="text-overflow:ellipsis;max-width:250px;overflow:hidden;white-space:nowrap;">' . $resp . '</td><td><span class="label label-sm ' . getStatusTag($ticket->getstatus()) . '"> ' . getStatus($ticket->getstatus()) . ' </span></td><td><a href="ticket.php?id=' . $ticket->getid() . '"> View </a></td></tr>';
                                                                                    }
                                                                                    $mod = sizeof($closed) % 5;
                                                                                    if($mod != 0){
                                                                                        for($i = 0; $i < (5 - $mod); $i++){
                                                                                            echo '<tr style="height:36px"><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td></tr>';
                                                                                        }
                                                                                    }
                                                                                        ?>
                                                                                    </tbody>
                                                                                </table>
                                                                                <center>
                                                                                    <ul class="pagination" id="closed_page_navigation"></ul>
                                                                                    <div>
                                                                                        <input id="page_input" type="number" style="padding: 6px 12px;width:10%;" value="1">
                                                                                        <a>
                                                                                            <input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page();">
                                                                                        </a>
                                                                                    </div>
                                                                                </center>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
            <script src="/assets/pages/scripts/tickets_page.js"></script>
            <script src="/assets/pages/scripts/closed_page.js"></script>
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