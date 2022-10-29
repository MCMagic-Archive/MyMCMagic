<?php
require('../auth.php');
date_default_timezone_set('America/New_York');
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
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
if($_SESSION['RANK'] == 'coordinator' && $type != 'char'){
    header('Location: ../applications/');
    exit();
}
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$id = sanitize($_GET['id']);
$type = sanitize($_GET['type']);
$name = '';
$pastnames = '';
$age = 0;
$region = '';
$location = '';
$work = '';
$chars = '';
$char = '';
$quote = '';
$mumble = '';
$mumble1 = '';
$mumble2 = '';
$hasexp = '';
$exp = '';
$role = '';
$why = '';
$whydev = '';
$prevchar = '';
$acc = '';
$interview = '';
$data = mysqli_query($conn, "SELECT * FROM " . $type . "_applications WHERE id='" . $id . "';");
$app = new Application();
if (mysqli_num_rows($data) > 0){
    while($row = mysqli_fetch_assoc($data)){
        $app->setid($row['id']);
        $app->setuuid($row['uuid']);
        $app->setdate($row['submitted']);
        $app->settype("Cast Member");
        $app->setinterview($row['interview']);
        $app->setstatus($row['status']);
        $app->setname($row['username']);
        $name = $row['username'];
        $pastnames = $row['pastnames'];
        $region = $row['region'];
        $location = $row['location'];
        $work = $row['work'];
        $chars = $row['characters'];
        $char = $row['applying'];
        $quote = $row['quote'];
        $mumble = $row['mumble'] == 'yes' ? 'yes' : 'no';
        $mumble1 = $row['mumble1'] == 'yes' ? 'yes' : 'no';
        $mumble2 = $row['mumble2'] == 'yes' ? 'yes' : 'no';
        $hasexp = $row['priorservers'] == 'yes' ? 'yes' : 'no';
        $exp = $row['moderated'];
        $role = $row['role'];
        $why = $row['whyme'];
        $whydev = $row['why'];
        $prevchar = $row['experience'];
        $acc = $row['applying'];
        $interview = $row['interview'];
    }
}else{
    header("Location: ../applications/");
}
$age = mysqli_fetch_assoc(mysqli_query($conn, "SELECT TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM users WHERE uuid='" . $app->getuuid() . "'"))['age'];
$t = 'Cast Member';
switch($type){
    case 'cm':
        $t = 'Cast Member';
        break;
    case 'dev':
        $t = 'Developer';
        break;
    case 'char':
        $t = 'Character';
        break;
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
        <?php $page = "View Application";
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
                                                <?php
                                                    $extra = '';
                                                    if($type == 'cm'){
                                                        mysqli_select_db($conn, "MainServer");
                                                        $member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT creatortag FROM creative WHERE uuid='" . $app->getuuid() . "'"))['creatortag'] == 1;
                                                        if($member){
                                                            $extra = '<t style="position:absolute;right:2%">Creator Project Member</t>';
                                                        }
                                                    }
                                                ?>
                                                    <span class="caption-subject bold"> <?php echo $app->getname() . "'s " . $t . " Application" . $extra; ?></span>
                                            </div>
                                        </div>
                                        <div class="portlet body form">
                                            <?php
                                            switch($type){
                                                case 'cm':
                                                    echo '<label class=text>Please list any previous usernames you\'ve had within the past 6 Months.</label><input name=pastnames autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $pastnames . '"disabled><label class=text>Age</label><input name=age autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $age . '" disabled type=number><label class=text>Do you own <i>or</i> share any <i>official</i> MCMagic Characters?</label><input name=characters autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $chars . '"disabled><label class=control-label for=mumble>Are you active on our Mumble Server?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=mumble name=mumble1 tabindex=-1 disabled><option value="yes"' . ($mumble1=='yes'?' selected':'') . '>Yes<option value="no"' . ($mumble1=='no'?' selected':'') . '>No</select><br><label class=control-label for=headset>Do you have headphones and a microphone?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=headset name=mumble2 tabindex=-1 disabled><option value="yes"' . ($mumble2=='yes'?' selected':'') . '>Yes<option value="no"' . ($mumble2=='no'?' selected':'') . '>No</select><br><label class=text>What region of the world do you live in?</label><input name=region autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $region . '"disabled><label class=control-label for=experience>Do you have previous moderation experience?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=experience name=priorservers tabindex=-1 onchange=yesnoCheck() disabled><option value="yes"' . ($hasexp=='yes'?' selected':'') . '>Yes<option value="no"' . ($hasexp=='no'?' selected':'') . '>No</select><br><div id=ifYes><label class=text>Where did you gain this experience?</label><input name=moderated autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $exp . '" disabled></div><label class=control-label for=position>What interests you the most about becoming a Cast Member?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=position name=role tabindex=-1 disabled><option value=gr' . ($role=='gr'?' selected':'') . '>Helping Guests with any questions that they may have and keeping chat safe.<option value=im' . ($role=='im'?' selected':'') . '>Working with other Cast Members to help build and improve the parks.<option value=med' . ($role=='med'?' selected':'') . '>Producing content for MCMagic social media accounts.<option value=sd' . ($role=='sd'?' selected':'') . '>Designing new and improved shows for our Guests to enjoy.<option value=cs' . ($role=='cs'?' selected':'') . '>Working with others on the MCMagic Creative Server to ensure that it\'s safe.</select><br><label for=why>Why should we choose you to become a Cast Member?</label><textarea class=form-control id=why name=whyme rows=5 disabled>' . $why . '</textarea><br>';
                                                    break;
                                                case 'dev':
                                                    echo '<label class=text>What is your REAL name? (First and Last)</label><input name=name autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $name . '"disabled><label class=text>What country, state, or timezone do you live in? (Provide as many as you want)</label><input name=location autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $location . '"disabled><label class=text>Please provide links to your previous work</label><input name=work autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $work . '"><label class=text>How old are you?</label><input name=age autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $age . '" disabled type=number><label for=why>Why should you become the newest MCMagic Developer?</label><textarea class=form-control id=why name=why rows=5 disabled>' . $whydev . '</textarea><br>';
                                                    break;
                                                case 'char':
                                                    echo '<label class=text>Age</label><input name=age autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $age . '" disabled type=number><label class=control-label for=experience>Do you currently or have you previously worked as a Character?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=experience name=experience tabindex=-1 onchange=yesnoCheck() disabled><option value="yes"' . ($prevchar=='yes'?' selected':'') . '>Yes<option value="no"' . ($prevchar=='no'?' selected':'') . '>No</select><br><div id=ifYes><label class=text>Please list your current/previous Characters:</label><input name=characters autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $chars . '" disabled></div><label class=control-label for=mumble>Are you active on our Mumble Server?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=mumble name=mumble tabindex=-1 disabled><option value="yes"' . ($mumble=='yes'?' selected':'') . '>Yes<option value="no"' . ($mumble=='no'?' selected':'') . '>No</select><br><label class=control-label for=account>Are you able to get a secondary account within 30 days after acceptance?</label><select aria-hidden=true class="form-control select2 select2-hidden-accessible"id=account name=account tabindex=-1 disabled><option value="yes"' . ($acc=='yes'?' selected':'') . '>Yes<option value="no"' . ($acc=='no'?' selected':'') . '>No</select><br><label class=text>What Character are you applying for?</label><input name=applying autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $char . '" disabled><label class=control-label for=quote>Please quote this Character:</label><input name=quote autocomplete=off class="form-control form-control-solid form-group placeholder-no-fix"value="' . $quote . '"disabled><br>';
                                                    break;
                                            }
                                                ?>
                                                <center>
                                                    <form action='schedule.php' id='interview-scheduling' method='post'>
                                                        <input type='hidden' name='type' value='<?php echo $type; ?>'>
                                                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                                                        <input type='hidden' name='uuid' value='<?php echo $app->getuuid(); ?>'>
                                                        <input type='hidden' name='username' value='<?php echo $app->getname(); ?>'>
                                                        <h4>Interview Dates</h4>
                                                        <input type='text' name='interview' placeholder='January 1st to 7th between 8:00 and 17:00' style='width:50%' value="<?php echo $interview = '' ? '' : $interview; ?>">
                                                    </form>
                                                    <br>
                                                    <button type="button" class="btn red btn-outline" id="deny">Deny</button>
                                                    <button type="button" class="btn yellow btn-outline" id="interview">Schedule Interview Dates</button>
                                                    <button type="button" class="btn green btn-outline" id="accept">Accept</button>
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
            <script>
                $('#deny').click(function () {
                    window.location = "appupdate.php?type=<?php echo $type; ?>&id=<?php echo $id; ?>&status=0";
                });
                $('#interview').click(function () {
                    $('#interview-scheduling').submit();
                });
                $('#accept').click(function () {
                    window.location = "appupdate.php?type=<?php echo $type; ?>&id=<?php echo $id; ?>&status=5";
                });
            </script>
    </body>

    </html>