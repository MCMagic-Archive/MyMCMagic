<?php
    require("../auth.php");
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
    $cm = false;
    $char = false;
    $dev = false;
    mysqli_select_db($conn, "mymcmagic");
    $apps = mysqli_query($conn, "SELECT name,open FROM app_types;");
    while($row = mysqli_fetch_assoc($apps)){
        switch($row['name']){
            case 'castmember':
                $cm = $row['open']==0;
                break;
            case 'character':
                $char = $row['open']==0;
                break;
            case 'developer':
                $dev = $row['open']==0;
                break;
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
        <?php $page = "Manager Panel";
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
            <link href="/assets/custom.css" rel="stylesheet" type="text/css" />
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
                                                    <li class="active">
                                                        <a href=".">
                                                            <i class="fa fa-wrench" aria-hidden="true"></i> Manage </a>
                                                    </li>
                                                    <li>
                                                        <a href="/manage/sessions/">
                                                            <i class="fa fa-clock-o" aria-hidden="true"></i> Sessions </a>
                                                    </li>
                                                    <li>
                                                        <a href="/manage/appeals/">
                                                            <i class="fa fa-gavel" aria-hidden="true"></i> Ban Appeals </a>
                                                    </li>
                                                    <li>
                                                        <a href="/manage/accounts/">
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
                                        <!-- Applications -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Applications</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row number-stats margin-bottom-30">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <label class="switch">
                                                                            <input type="checkbox" <?php echo ($cm ? 'checked="true"' : '') ?> id="cm">
                                                                            <div class="slider"></div>
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="text profile-stat-title">Cast Member</label>
                                                                        <label class="saved" id="cm-save">Saved!</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="switch">
                                                                            <input type="checkbox" <?php echo ($char ? 'checked="true"' : '') ?> id="char">
                                                                            <div class="slider"></div>
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="text profile-stat-title">Character</label>
                                                                        <label class="saved" id="char-save">Saved!</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label class="switch">
                                                                            <input type="checkbox" <?php echo ($dev ? 'checked="true"' : '') ?> id="dev">
                                                                            <div class="slider"></div>
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="text profile-stat-title">Developer</label>
                                                                        <label class="saved" id="dev-save">Saved!</label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Moderation Log -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart theme-font hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Moderation Log</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row number-stats margin-bottom-30">

                                                            <div class="table-scrollable-borderless">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th> Staff Member </th>
                                                                            <th> Action </th>
                                                                            <th> Date </th>
                                                                            <th> IP Address</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">Brant</div>
                                                                            </td>
                                                                            <td> Ban Player "Turner" Reason: Nub </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">owils</div>
                                                                            </td>
                                                                            <td> Ban Player "DevSlashNull" Reason: I don't care. </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-cm">XtraFr3ckle</div>
                                                                            </td>
                                                                            <td> Respond to "xBrant" support ticket: "I borked it". </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-cm">HokaPoka812</div>
                                                                            </td>
                                                                            <td> Closed "xBrant" support ticket: "I borked it </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">Helopoh</div>
                                                                            </td>
                                                                            <td> Kick Player "therealduckie" Reason: afk </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <center>
                                                                    <ul class="pagination">
                                                                        <li class="disabled"><a href="#!"><i class="material-icons">Prev</i></a></li>
                                                                        <li class="active"><a href="#!">1</a></li>
                                                                        <li class="waves-effect"><a href="#!">2</a></li>
                                                                        <li class="waves-effect"><a href="#!">3</a></li>
                                                                        <li class="waves-effect"><a href="#!">4</a></li>
                                                                        <li class="waves-effect"><a href="#!">5</a></li>
                                                                        <li class="waves-effect"><a href="#!"><i >Next</i></a></li>
                                                                    </ul>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Staff Activity -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- BEGIN PORTLET -->
                                                <div class="portlet light ">
                                                    <div class="portlet-title">
                                                        <div class="caption caption-md">
                                                            <i class="icon-bar-chart font-blue-madison hide"></i>
                                                            <span class="caption-subject font-blue-madison bold uppercase">Staff Activity</span>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="row number-stats margin-bottom-30">

                                                            <div class="table-scrollable-borderless">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th> Staff Member </th>
                                                                            <th> Last Known Park </th>
                                                                            <th> Date Seen </th>
                                                                            <th> IP Address</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">Brant</div>
                                                                            </td>
                                                                            <td> Magic Kingdom </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">owils</div>
                                                                            </td>
                                                                            <td> Epcot </td>
                                                                            <td> 9/20/15 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-cm">XtraFr3ckle</div>
                                                                            </td>
                                                                            <td> Hollywood Studios </td>
                                                                            <td> 9/10/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-cm">GrailMore</div>
                                                                            </td>
                                                                            <td> Transportation & Ticketing Center </td>
                                                                            <td> 04/05/15 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="staff-manager">Helopoh</div>
                                                                            </td>
                                                                            <td> Creative </td>
                                                                            <td> 9/20/16 </td>
                                                                            <td> 127.0.0.1 </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <center>
                                                                    <ul class="pagination">
                                                                        <li class="disabled"><a href="#!"><i class="material-icons">Prev</i></a></li>
                                                                        <li class="active"><a href="#!">1</a></li>
                                                                        <li class="waves-effect"><a href="#!">2</a></li>
                                                                        <li class="waves-effect"><a href="#!">3</a></li>
                                                                        <li class="waves-effect"><a href="#!">4</a></li>
                                                                        <li class="waves-effect"><a href="#!">5</a></li>
                                                                        <li class="waves-effect"><a href="#!"><i >Next</i></a></li>
                                                                    </ul>
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
        <!-- BEGIN AJAX -->
        <script>
            $('#cm').click(function (event) {
                console.log(($('#cm').is(':checked') ? "0" : "1"));
                var post_data = "app=castmember&open=" + $('#cm').is(':checked');
                $.ajax({
                    url: 'apps.php',
                    type: 'POST',
                    data: post_data,
                    dataType: 'html',
                    success: function (data) {
                        $("#cm-save").fadeIn(function () {
                            $('#cm-save').delay(1500).fadeOut();
                        })
                    }
                });
            });
            $('#dev').click(function (event) {
                console.log(($('#dev').is(':checked') ? "0" : "1"));
                var post_data = "app=developer&open=" + $('#dev').is(':checked');
                $.ajax({
                    url: 'apps.php',
                    type: 'POST',
                    data: post_data,
                    dataType: 'html',
                    success: function (data) {
                        $("#dev-save").fadeIn(function () {
                            $('#dev-save').delay(1500).fadeOut();
                        })
                    }
                });
            });
            $('#char').click(function (event) {
                console.log(($('#char').is(':checked') ? "0" : "1"));
                var post_data = "app=character&open=" + $('#char').is(':checked');
                $.ajax({
                    url: 'apps.php',
                    type: 'POST',
                    data: post_data,
                    dataType: 'html',
                    success: function (data) {
                        $("#char-save").fadeIn(function () {
                            $('#char-save').delay(1500).fadeOut();
                        })
                    }
                });
            });
        </script>
        <!-- end ajax-->
    </body>

    </html>