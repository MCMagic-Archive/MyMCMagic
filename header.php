<title>MyMCMagic |
    <?php
            echo $page;
            if(substr($page, 0, strlen($page) >= 16 ? 16 : strlen($page)) === "The MCMagic Team"){
                session_start();
            }
            $amount = 0;
            if($_SESSION['ID'] != ''){
                $c = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
                mysqli_select_db($c, "mymcmagic");
                $amount = intval(mysqli_fetch_assoc(mysqli_query($c, 'SELECT count(*) AS count FROM ticketids WHERE uuid="' . $_SESSION['UUID'] . '" AND status=3;'))['count']);
            }
            $unread = $amount > 0;
        ?>
</title>
<noscript>
    <meta http-equiv="refresh" content="0; url=/nojs/" />
</noscript>
<!--<script>
    var loc = window.location.href + '';
    if (loc.indexOf('http://') == 0) {
        window.location.href = loc.replace('http://', 'https://');
    }
</script>-->

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta content="MyMCMagic" name="description" />
<meta content="MCMagic - The Most Magical World in Minecraft" name="author" />

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.css">

<link rel="apple-touch-icon" sizes="57x57" href="https://mcmagic.us/ico/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="https://mcmagic.us/ico/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="https://mcmagic.us/ico/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="https://mcmagic.us/ico/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="https://mcmagic.us/ico/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="https://mcmagic.us/ico/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="https://mcmagic.us/ico/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="https://mcmagic.us/ico/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="https://mcmagic.us/ico/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192" href="https://mcmagic.us/ico/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="https://mcmagic.us/ico/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="https://mcmagic.us/ico/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="https://mcmagic.us/ico/favicon-16x16.png">
<link href="/assets/global/css/text.css" rel="stylesheet" type="text/css" />
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        var height = $(window).height() - ($('.page-header').height()) - ($('.page-head').height()) - ($('footer').height());
        $('.page-content').css('min-height', height);
    });
</script>

<body class="page-container-bg-solid page-md">
    <!-- BEGIN HEADER -->
    <div class="page-header">
        <!-- BEGIN HEADER TOP -->
        <div class="page-header-top">
            <div class="container">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="/profile/">
                        <img src="/img/_logo.png" alt="logo" class="logo-default">
                    </a>
                </div>
                <!-- END LOGO -->
            </div>
        </div>
        <!-- END HEADER TOP -->
        <?php
            if(isset($_SESSION['UUID']) && $_SESSION['UUID'] != ''){
        ?>
            <!-- BEGIN HEADER MENU -->
            <div class="page-header-menu">
                <div class="container">
                    <!-- BEGIN MEGA MENU -->
                    <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                    <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                    <div class="hor-menu  ">
                        <ul class="nav navbar-nav">
                            <li class="menu-dropdown classic-menu-dropdown ">
                                <a href="/profile/"> Profile
                                    <span class="arrow"></span>
                            </a>
                                <ul class="dropdown-menu" style="min-width: 210px">
                                    <li><a href="/profile/friends/">Friends</a></li>
                                    <li><a href="/profile/transactions/">Transactions</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="/team/"> Team
                                    <span class="arrow"></span>
                            </a>
                            </li>
                            <?php
                        if($_SESSION['RANK'] == 'earningmyears' || $_SESSION['RANK'] == 'castmember' || $_SESSION['RANK'] == 'coordinator' || $_SESSION['RANK'] == 'developer' || $_SESSION['RANK'] == 'manager' || $_SESSION['RANK'] == 'owner'){
                        ?>
                                <li class="menu-dropdown mega-menu-dropdown">
                                    <a href="#"> Cast Member
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 210px">
                                        <li><a href="/castmember/player/">Player Lookup</a></li>
                                        <li><a href="/castmember/tickets/">Support Tickets</a></li>
                                        <li><a href="/castmember/chatlog/">Chat Lookup</a></li>
                                        <li><a href="/castmember/transactions/">Guest Transactions</a></li>
                                        <li><a href="/castmember/bio/">Edit Bio</a></li>
                                    </ul>
                                </li>
                                <?php
                        }
                        if($_SESSION['RANK'] == 'coordinator' || $_SESSION['RANK'] == 'developer' || $_SESSION['RANK'] == 'manager' || $_SESSION['RANK'] == 'owner'){
                        ?>
                                    <li class="menu-dropdown mega-menu-dropdown">
                                        <a href="/applications/"> Applications
                                    <span class="arrow"></span>
                                </a>
                                        <?php
                        }
                        ?>
                                            <?php
                        if($_SESSION['RANK'] == 'developer' || $_SESSION['RANK'] == 'manager' || $_SESSION['RANK'] == 'owner'){
                        ?>
                                                <li class="menu-dropdown classic-menu-dropdown ">
                                                    <a href="/manage/"> Manage
                                    <span class="arrow"></span>
                                </a>
                                                    <ul class="dropdown-menu" style="min-width: 210px">
                                                        <li><a href="/manage/sessions/">Sessions</a></li>
                                                        <li><a href="/manage/appeals/">Ban Appeals</a></li>
                                                        <li><a href="/manage/accounts/">Accounts</a></li>
                                                    </ul>
                                                </li>
                                                <?php
                        }
                        ?>
                                                    <li class="menu-dropdown classic-menu-dropdown">
                                                        <a href="/logout/"> Logout<span class="arrow"></span></a>
                                                    </li>
                                                    <?php
                                                    if($unread){
                                                        echo '<li class="classic-menu-dropdown menu-dropdown"style=position:absolute;right:2%><a href=/support/ style=font-size:18px>' . $amount . ' <i aria-hidden=true class="fa fa-envelope"></i><span class=arrow></span></a>';
                                                    }
                                                        ?>
                        </ul>
                    </div>
                    <!-- END MEGA MENU -->
                </div>
            </div>
            <!-- END HEADER MENU -->
            <?php
            }else{
                ?>
                <!-- BEGIN HEADER MENU -->
                <div class="page-header-menu">
                    <div class="container">
                        <!-- BEGIN MEGA MENU -->
                        <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                        <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                        <div class="hor-menu  ">
                            <ul class="nav navbar-nav">
                                <li>
                                    <a href="/team/"> Team
                                    <span class="arrow"></span>
                                    </a>
                                </li>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a href="/"> Login<span class="arrow"></span></a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MEGA MENU -->
                    </div>
                </div>
                <?php
            }
                ?>
    </div>
    <!-- END HEADER -->
    <div class="page-container">
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- BEGIN PAGE HEAD-->
            <div class="page-head">
                <div class="container">
                    <!-- BEGIN PAGE TITLE -->
                    <div class="page-title">
                        <h1><?php echo $page ?>
                            <small></small>
                        </h1>
                    </div>
                    <!-- END PAGE TITLE -->
                    <div align="center">
                        <h3>Welcome Beta Testers!</h3>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEAD-->
        </div>
    </div>
</body>