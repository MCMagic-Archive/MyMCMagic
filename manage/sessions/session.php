<?php
    if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        die('Invalid request');
    }
    class Session
    {
        public $in = null;
        public $out = null;
        
        public function setin($v){
            $this->in = $v;
        }
        
        public function setout($v){
            $this->out = $v;
        }
        
        public function getin(){
            return $this->in;
        }
        
        public function getout(){
            return $this->out;
        }
    }
    date_default_timezone_set("America/New_York");
	$username = $_POST['username'];
	$uuid = $_POST['uuid'];
	if((!isset($uuid) && !isset($username)) || ($uuid == "" && $username == "")) {
		die("No Username or UUID provided!");
	}
	$link = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
	if(!$link) {
		die("There was an error connecting to the database, try again later!");
	}
	$db=mysqli_select_db($link, "MainServer");
	if(!$db){
		die("There was an error connecting to the database, try again later!");
	}
	$getuuid = "SELECT uuid,onlinetime FROM player_data WHERE username=\"" . $username . "\";";
	$uuidres = mysqli_query($link, $getuuid);
	$total = 0;
	if(!$uuidres){
		die("No Player Data found for this username!");
	}
    $sessions = array();
	while ($row = mysqli_fetch_array($uuidres)) {
		$uuid = $row['uuid'];
		$total = $row['onlinetime'];
		$days = floor($total/ (60*60*24));
		$hours = floor(($total - $days*60*60*24)/ (60*60));
		$minutes = floor(($total - $days*60*60*24 - $hours*60*60)/ 60);
		$seconds = floor($total - $days*60*60*24 - $hours*60*60 - $minutes*60);
		$total = ($days>0?($days . " Days "):"") . ($hours>0?($hours . " Hours "):"") . ($minutes>0?($minutes . " Minutes "):"") . ($seconds>0?($seconds . " Seconds "):"");
		break;
	}
	if($uuid == ""){
		die("No Player Data found for this username!");
	}
	$qry="SELECT action,time FROM staffclock WHERE user=\"" . $uuid . "\" ORDER BY id desc";
	$res=mysqli_query($link, $qry);
	$in = array();
	$out = array();
	$type = 1;
    $s = null;
	while ($row = mysqli_fetch_array($res)) {
        if($s == null){
            $s = new Session();
        }
		if($row['action']=='login' && $type == 0){
            $s->setin($row['time']);
			$type=1;
		} else if($row['action']=='logout' && $type==1){
            $s->setout($row['time']);
			$type=0;
		}
        if($s->getin()!=0 && $s->getout()!=0){
            array_push($sessions, $s);
            $s = null;
        }
	}
	//$sessions = array();
	$outsize = sizeof($out);
	for($i = 0; $i < sizeof($in); $i++){
		if($i >= $outsize){
			break;
		}
		$arrive = $in[$i];
		$leave = $out[$i];
		if($leave > $arrive){
			$sessions[$arrive] = $leave;
		}
	}
    echo "<table>";
    foreach ($sessions as $s) {
		$ind = new DateTime(date("c", $s->getin()));
		$outd = new DateTime(date("c", @$s->getout()));
		$ind->setTimezone(new DateTimeZone('America/New_York'));
		$outd->setTimezone(new DateTimeZone('America/New_York'));
		$diff = abs($s->getout() - $s->getin());
		$days = floor($diff/ (60*60*24));
		$hours = floor(($diff - $days*60*60*24)/ (60*60));
		$minutes = floor(($diff - $days*60*60*24 - $hours*60*60)/ 60);
		$seconds = floor($diff - $days*60*60*24 - $hours*60*60 - $minutes*60);
		$ot = ($days>0?($days . " Days "):"") . ($hours>0?($hours . " Hours "):"") . ($minutes>0?($minutes . " Minutes "):"") . ($seconds>0?($seconds . " Seconds "):"");
		echo "<tr><td id=\"button\">" . $ind->format('n-d-Y g:i:s A') . " -> " . $outd->format('n-d-Y g:i:s A') . " Online for " . $ot . "</td></tr>";
	}
    echo "</table>";