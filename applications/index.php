<?php
    require("../auth.php");
    class Application
    {
        public $id = 0;
        public $uuid = null;
        public $date = null;
        public $type = null;
        public $interview = null;
        public $status = null;
        public $name = null;
        
        public function setid($v){
            $this->id = $v;
        }
        
        public function setuuid($v){
            $this->uuid = $v;
        }
        
        public function setdate($v){
            $this->date = $v;
        }
        
        public function settype($v){
            $this->type = $v;
        }
        
        public function setinterview($v){
            $this->interview = $v;
        }
        
        public function setstatus($v){
            $this->status = $v;
        }
        
        public function setname($v){
            $this->name = $v;
        }
        
        public function getid(){
            return $this->id;
        }
        
        public function getuuid(){
            return $this->uuid;
        }
        
        public function getdate(){
            return $this->date;
        }
        
        public function gettype(){
            return $this->type;
        }
        
        public function getinterview(){
            return $this->interview;
        }
        
        public function getstatus(){
            return $this->status;
        }
        
        public function getname(){
            return $this->name;
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
    function getAppStatusTag($var){
        switch ($var){
            case '0':
                return "label-danger";
            case '1':
                return "label-info";
            case '2':
            case '3':
            case '4':
                return "label-warning";
            case '5':
                return "label-success";
        }
    }
    function getAppStatus($var){
        switch ($var){
            case '0':
                return "Denied";
            case '1':
                return "Submitted";
            case '2':
                return "Review";
            case '3':
                return "Interview";
            case '4':
                return "Training";
            case '5':
                return "Accepted";
        }
    }
    date_default_timezone_set('America/New_York');
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

    #CM APPLICATIONS
    mysqli_select_db($conn, "mymcmagic");
    $cmappres = mysqli_query($conn, "SELECT * FROM cm_applications ORDER BY submitted DESC;");
    $cmapplications = array();
    if (mysqli_num_rows($cmappres) > 0){
        while($row = mysqli_fetch_assoc($cmappres)){
            $app = new Application();
            $app->setid($row['id']);
            $app->setuuid($row['uuid']);
            $app->setdate($row['submitted']);
            $app->settype("Cast Member");
            $app->setinterview($row['interview']);
            $app->setstatus($row['status']);
            $app->setname($row['username']);
            array_push($cmapplications, $app);
        }
    }

    #DEV APPLICATIONS
    $devappres = mysqli_query($conn, "SELECT * FROM dev_applications ORDER BY submitted DESC;");
    $devapplications = array();
    if (mysqli_num_rows($devappres) > 0){
        while($row = mysqli_fetch_assoc($devappres)){
            $app = new Application();
            $app->setid($row['id']);
            $app->setuuid($row['uuid']);
            $app->setdate($row['submitted']);
            $app->settype("Developer");
            $app->setinterview($row['interview']);
            $app->setstatus($row['status']);
            $app->setname($row['username']);
            array_push($devapplications, $app);
        }
    }

    #CHAR APPLICATIONS
    $charappres = mysqli_query($conn, "SELECT * FROM char_applications ORDER BY submitted DESC;");
    $charapplications = array();
    if (mysqli_num_rows($charappres) > 0){
        while($row = mysqli_fetch_assoc($charappres)){
            $app = new Application();
            $app->setid($row['id']);
            $app->setuuid($row['uuid']);
            $app->setdate($row['submitted']);
            $app->settype("Character");
            $app->setinterview($row['interview']);
            $app->setstatus($row['status']);
            $app->setname($row['username']);
            array_push($charapplications, $app);
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
        <?php $page = "View Staff Applications";
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
            <link href="/assets/global/css/text.css" rel="stylesheet" id="style_components" type="text/css" />
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
        <input type='hidden' id='cm_current_page' />
        <input type='hidden' id='cm_show_per_page' />
        <input type='hidden' id='cm_number_of_pages' />
        <input type='hidden' id='char_current_page' />
        <input type='hidden' id='char_show_per_page' />
        <input type='hidden' id='char_number_of_pages' />
        <input type='hidden' id='dev_current_page' />
        <input type='hidden' id='dev_show_per_page' />
        <input type='hidden' id='dev_number_of_pages' />
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
                                    <!-- BEGIN PROFILE CONTENT -->
                                    <div class="profile-content">
                                        <?php
                                        if($_SESSION['RANK'] == 'manager' || $_SESSION['RANK'] == 'owner'){
                                        ?>
                                            <!-- Cast Member Applications -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN PORTLET -->
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">Cast Member Applications</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row number-stats margin-bottom-30">
                                                                <div class="table-scrollable-borderless">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th> Date Submitted </th>
                                                                                <th> Applicant </th>
                                                                                <th> Interview </th>
                                                                                <th> Status </th>
                                                                                <th> </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="cm">
                                                                            <?php
                                                                        foreach($cmapplications as $app){
                                                                            echo '<tr><td> ' . date('n/j/y g:i:s A', $app->getdate()) . ' </td><td> ' . $app->getname() . ' </td><td> ' . ($app->getinterview() == '' ? 'Not Scheduled' : $app->getinterview()) . ' </td><td><span class="label label-sm ' . getAppStatusTag($app->getstatus()) . '"> ' . getAppStatus($app->getstatus()) . ' </span></td><td> <a href="app.php?id=' . $app->getid() . '&type=cm"> View </a></td></tr>';
                                                                        }
                                                                        $mod = sizeof($cmapplications) % 5;
                                                                        if($mod != 0){
                                                                            for($i = 0; $i < (5 - $mod); $i++){
                                                                                echo '<tr><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td></tr>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <center>
                                                                        <ul class="pagination" id="cm_page_navigation"></ul>
                                                                        <div>
                                                                            <input id="page_input_cm" type="number" style="padding: 6px 12px;width:10%;" value="1">
                                                                            <a>
                                                                                <input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page_cm();">
                                                                            </a>
                                                                        </div>
                                                                        <form action="update.php" method="post" id="cm_form_2">
                                                                            <h4>Set all Cast Member applications with status
                                                                            <input type="hidden" value="cm" name="type">
                                                                            <input type="hidden" value="0" name="status" id="cm_status">
                                                                            <select name="is">
                                                                                <option value="0">Denied</option>
                                                                                <option value="1">Submitted</option>
                                                                                <option value="2">Review</option>
                                                                                <option value="3">Interview</option>
                                                                                <option value="4">Training</option>
                                                                                <option value="5">Accepted</option>
                                                                            </select>
                                                                            to
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="cm_review">
                                                                                <label class="btn btn-transparent yellow btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Review
                                                                                </label>
                                                                            </div>
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="cm_deny">
                                                                                <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Denied
                                                                                </label>
                                                                            </div>
                                                                        </h4>
                                                                        </form>
                                                                        <form action="delete.php" method="post" id="cm_form">
                                                                            <h4>Delete all Cast Member applications with status
                                                                            <input type="hidden" value="cm" name="type">
                                                                            <select name="status">
                                                                                <option value="0">Denied</option>
                                                                                <option value="1">Submitted</option>
                                                                                <option value="2">Review</option>
                                                                                <option value="3">Interview</option>
                                                                                <option value="4">Training</option>
                                                                                <option value="5">Accepted</option>
                                                                            </select>
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="cm_delete">
                                                                                <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Delete
                                                                                </label>
                                                                            </div>
                                                                        </h4>
                                                                        </form>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Developer Applications -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- BEGIN PORTLET -->
                                                    <div class="portlet light ">
                                                        <div class="portlet-title">
                                                            <div class="caption caption-md">
                                                                <i class="icon-bar-chart theme-font hide"></i>
                                                                <span class="caption-subject font-blue-madison bold uppercase">Developer Applications</span>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="table-scrollable-borderless">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th> Date Submitted </th>
                                                                            <th> Applicant </th>
                                                                            <th> Interview </th>
                                                                            <th> Status </th>
                                                                            <th> </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="dev">
                                                                        <?php
                                                                        foreach($devapplications as $app){
                                                                            echo '<tr><td> ' . date('n/j/y g:i:s A', $app->getdate()) . ' </td><td> ' . $app->getname() . ' </td><td> ' . ($app->getinterview() == '' ? 'Not Scheduled' : $app->getinterview()) . ' </td><td><span class="label label-sm ' . getAppStatusTag($app->getstatus()) . '"> ' . getAppStatus($app->getstatus()) . ' </span></td><td> <a href="app.php?id=' . $app->getid() . '&type=dev"> View </a></td></tr>';
                                                                        }
                                                                        $mod = sizeof($devapplications) % 5;
                                                                        if($mod != 0){
                                                                            for($i = 0; $i < (5 - $mod); $i++){
                                                                                echo '<tr><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td></tr>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                <center>
                                                                    <ul class="pagination" id="dev_page_navigation"></ul>
                                                                    <div>
                                                                        <input id="page_input_dev" type="number" style="padding: 6px 12px;width:10%;" value="1">
                                                                        <a>
                                                                            <input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page_dev();">
                                                                        </a>
                                                                    </div>
                                                                    <form action="update.php" method="post" id="dev_form_2">
                                                                        <h4>Set all Developer applications with status
                                                                        <input type="hidden" value="dev" name="type">
                                                                        <input type="hidden" value="0" name="status" id="dev_status">
                                                                        <select name="is">
                                                                            <option value="0">Denied</option>
                                                                            <option value="1">Submitted</option>
                                                                            <option value="2">Review</option>
                                                                            <option value="3">Interview</option>
                                                                            <option value="4">Training</option>
                                                                            <option value="5">Accepted</option>
                                                                        </select>
                                                                        to
                                                                        <div class="btn-group btn-group-devided" data-toggle="buttons" id="dev_review">
                                                                            <label class="btn btn-transparent yellow btn-outline btn-circle btn-sm">
                                                                                <input type="radio" name="options" class="toggle">Review
                                                                            </label>
                                                                        </div>
                                                                        <div class="btn-group btn-group-devided" data-toggle="buttons" id="dev_deny">
                                                                            <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                <input type="radio" name="options" class="toggle">Denied
                                                                            </label>
                                                                        </div>
                                                                    </h4>
                                                                    </form>
                                                                    <form action="delete.php" method="post" id="dev_form">
                                                                        <h4>Delete all Developer applications with status
                                                                            <input type="hidden" value="dev" name="type">
                                                                            <select name="status">
                                                                                <option value="0">Denied</option>
                                                                                <option value="1">Submitted</option>
                                                                                <option value="2">Review</option>
                                                                                <option value="3">Interview</option>
                                                                                <option value="4">Training</option>
                                                                                <option value="5">Accepted</option>
                                                                            </select>
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="dev_delete">
                                                                                <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Delete
                                                                                </label>
                                                                            </div>
                                                                        </h4>
                                                                    </form>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                            ?>
                                                <!-- Character Applications -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!-- BEGIN PORTLET -->
                                                        <div class="portlet light ">
                                                            <div class="portlet-title">
                                                                <div class="caption caption-md">
                                                                    <i class="icon-bar-chart theme-font hide"></i>
                                                                    <span class="caption-subject font-blue-madison bold uppercase">Character Applications</span>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body">
                                                                <div class="row number-stats margin-bottom-30">
                                                                    <div class="table-scrollable-borderless">
                                                                        <table class="table table-hover">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th> Date Submitted </th>
                                                                                    <th> Applicant </th>
                                                                                    <th> Interview </th>
                                                                                    <th> Status </th>
                                                                                    <th> </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="char">
                                                                                <?php
                                                                        foreach($charapplications as $app){
                                                                            echo '<tr><td> ' . date('n/j/y g:i:s A', $app->getdate()) . ' </td><td> ' . $app->getname() . ' </td><td> ' . ($app->getinterview() == '' ? 'Not Scheduled' : $app->getinterview()) . ' </td><td><span class="label label-sm ' . getAppStatusTag($app->getstatus()) . '"> ' . getAppStatus($app->getstatus()) . ' </span></td><td> <a href="app.php?id=' . $app->getid() . '&type=char"> View </a></td></tr>';
                                                                        }
                                                                        $mod = sizeof($charapplications) % 5;
                                                                        if($mod != 0){
                                                                            for($i = 0; $i < (5 - $mod); $i++){
                                                                                echo '<tr><td>&nbsp;</td><td> </td><td> </td><td> </td><td> </td></tr>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                            </tbody>
                                                                        </table>
                                                                        <center>
                                                                            <ul class="pagination" id="char_page_navigation"></ul>
                                                                            <div>
                                                                                <input id="page_input_char" type="number" style="padding: 6px 12px;width:10%;" value="1">
                                                                                <a>
                                                                                    <input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page_char();">
                                                                                </a>
                                                                            </div>
                                                                            <form action="update.php" method="post" id="char_form_2">
                                                                                <h4>Set all Character applications with status
                                                                            <input type="hidden" value="char" name="type">
                                                                            <input type="hidden" value="0" name="status" id="char_status">
                                                                            <select name="is">
                                                                                <option value="0">Denied</option>
                                                                                <option value="1">Submitted</option>
                                                                                <option value="2">Review</option>
                                                                                <option value="3">Interview</option>
                                                                                <option value="4">Training</option>
                                                                                <option value="5">Accepted</option>
                                                                            </select>
                                                                            to
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="char_review">
                                                                                <label class="btn btn-transparent yellow btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Review
                                                                                </label>
                                                                            </div>
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="char_deny">
                                                                                <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Denied
                                                                                </label>
                                                                            </div>
                                                                        </h4>
                                                                            </form>
                                                                            <form action="delete.php" method="post" id="char_form">
                                                                                <h4>Delete all Character applications with status
                                                                            <input type="hidden" value="char" name="type">
                                                                            <select name="status">
                                                                                <option value="0">Denied</option>
                                                                                <option value="1">Submitted</option>
                                                                                <option value="2">Review</option>
                                                                                <option value="3">Interview</option>
                                                                                <option value="4">Training</option>
                                                                                <option value="5">Accepted</option>
                                                                            </select>
                                                                            <div class="btn-group btn-group-devided" data-toggle="buttons" id="char_delete">
                                                                                <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                                                                    <input type="radio" name="options" class="toggle">Delete
                                                                                </label>
                                                                            </div>
                                                                        </h4>
                                                                            </form>
                                                                        </center>
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
            <script src="/assets/pages/scripts/cm_apps.js"></script>
            <script src="/assets/pages/scripts/char_apps.js"></script>
            <script src="/assets/pages/scripts/dev_apps.js"></script>
            <script>
                function read_page_cm() {
                    var page = parseInt($('#page_input_cm').val());
                    cm_go_to_page(page - 1);
                }

                function read_page_dev() {
                    var page = parseInt($('#page_input_dev').val());
                    dev_go_to_page(page - 1);
                }

                function read_page_char() {
                    var page = parseInt($('#page_input_char').val());
                    char_go_to_page(page - 1);
                }
                document.getElementById('cm_delete').onclick = function () {
                    document.getElementById('cm_form').submit();
                }
                document.getElementById('dev_delete').onclick = function () {
                    document.getElementById('dev_form').submit();
                }
                document.getElementById('char_delete').onclick = function () {
                    document.getElementById('char_form').submit();
                }
                document.getElementById('cm_review').onclick = function () {
                    document.getElementById('cm_status').value = '2';
                    document.getElementById('cm_form_2').submit();
                }
                document.getElementById('cm_deny').onclick = function () {
                    document.getElementById('cm_form_2').submit();
                }
                document.getElementById('dev_review').onclick = function () {
                    document.getElementById('dev_status').value = '2';
                    document.getElementById('dev_form_2').submit();
                }
                document.getElementById('dev_deny').onclick = function () {
                    document.getElementById('dev_form_2').submit();
                }
                document.getElementById('char_review').onclick = function () {
                    document.getElementById('char_status').value = '2';
                    document.getElementById('char_form_2').submit();
                }
                document.getElementById('char_deny').onclick = function () {
                    document.getElementById('char_form_2').submit();
                }
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