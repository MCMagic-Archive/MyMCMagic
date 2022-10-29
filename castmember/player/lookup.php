<?php
    if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        die('Invalid request');
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
    class Transaction
    {
        public $amount = null;
        public $type = null;
        public $server = null;
        public $source = null;
        public $time = null;
        
        public function setamount($v){
            $this->amount = $v;
        }
        
        public function settype($v){
            $this->type = $v;
        }
        
        public function setserver($v){
            $this->server = $v;
        }
        
        public function setsource($v){
            $this->source = $v;
        }
        
        public function settime($v){
            $this->time = $v;
        }
        
        public function getamount(){
            return $this->amount;
        }
        
        public function gettype(){
            return $this->type;
        }
        
        public function getserver(){
            return $this->server;
        }
        
        public function getsource(){
            return $this->source;
        }
        
        public function gettime(){
            return $this->time;
        }
    }
    class Ban
    {
        public $reason = null;
        public $permanent = false;
        public $release = null;
        public $source = null;
        public $active = false;
        
        public function setreason($v){
            $this->reason = $v;
        }
        
        public function setpermanent($v){
            $this->permanent = $v;
        }
        
        public function setrelease($v){
            $this->release = $v;
        }
        
        public function setsource($v){
            $this->source = $v;
        }
        
        public function setactive($v){
            $this->active = $v;
        }
        
        public function getreason(){
            return $this->reason;
        }
        
        public function getpermanent(){
            return $this->permanent;
        }
        
        public function getrelease(){
            return $this->release;
        }
        
        public function getsource(){
            return $this->source;
        }
        
        public function getactive(){
            return $this->active;
        }
    }
    class Kick
    {
        public $reason = null;
        public $source = null;
        public $time = null;
        
        public function setreason($v){
            $this->reason = $v;
        }
        
        public function setsource($v){
            $this->source = $v;
        }
        
        public function settime($v){
            $this->time = $v;
        }
        
        public function getreason(){
            return $this->reason;
        }
        
        public function getsource(){
            return $this->source;
        }
        
        public function gettime(){
            return $this->time;
        }
    }
    class Mute
    {
        public $reason = null;
        public $source = null;
        public $release = null;
        public $active = null;
        
        public function setreason($v){
            $this->reason = $v;
        }
        
        public function setsource($v){
            $this->source = $v;
        }
        
        public function setrelease($v){
            $this->release = $v;
        }
        
        public function setactive($v){
            $this->active = $v;
        }
        
        public function getreason(){
            return $this->reason;
        }
        
        public function getsource(){
            return $this->source;
        }
        
        public function getrelease(){
            return $this->release;
        }
        
        public function getactive(){
            return $this->active;
        }
    }
    $username = $_POST['username'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    $uuid = '';
    $rank = '';
    $ip = '';
    $bal = '';
    $token = '';
    $trans = '';
    $lastonline = '';
    $lastserver = '';
    mysqli_select_db($conn, "MainServer");
    $response = false;
    $main = mysqli_query($conn, "SELECT uuid,username,rank,ipAddress,balance,tokens,lastseen,server FROM player_data WHERE username='" . $username . "';");
    if (mysqli_num_rows($main) > 0) {
        while($row = mysqli_fetch_assoc($main)) {
            $response = true;
            $uuid = $row['uuid'];
            $rank = $row['rank'];
            $ip = $row['ipAddress'];
            $bal = $row['balance'];
            $token = $row['tokens'];
            $lastonline = date('n/j/y g:i:sa', strtotime($row['lastseen']));
            $lastserver = $row['server'];
        }
    }
    if(!$response){
        echo 'No Guest found by that username!';
        exit();
    }
    $transactions = array();
    $tran = mysqli_query($conn, "SELECT amount,type,source,server,timestamp FROM economy_logs WHERE uuid='" . $uuid . "' AND timestamp>" . (time()-86400) . " ORDER BY timestamp DESC;");
    if (mysqli_num_rows($tran) > 0) {
        while($row = mysqli_fetch_assoc($tran)) {
            $t = new Transaction();
            $t->setamount($row['amount']);
            $t->settype($row['type']);
            $t->setsource($row['source']);
            $t->setserver($row['server']);
            $t->settime($row['timestamp']);
            array_push($transactions, $t);
            break;
        }
    }
    $trans = '';
    foreach($transactions as $t){
        $amount = $t->getamount();
        $type = $t->gettype();
        $source = $t->getsource();
        $server = $t->getserver();
        $time = $t->gettime();
        $currency = strpos($type, 'balance') !== false ? '$' : '✪ ';
        $date = date('n/j/y g:i:sa', $time);
        $color = $amount > 0 ? '#33cc00' : '#ff3333';
        $trans .= '<tr><td> ' . $source . ' </td><td style="color:' . $color . '"> ' . $currency . abs($amount) . '</a> </td><td> ' . $server . ' </td><td> ' . $date . ' </td></tr>';
    }
    $puns = '';
    $bans = array();
    $kicks = array();
    $mutes = array();
    $banq = mysqli_query($conn, "SELECT reason,permanent,`release`,source,active FROM banned_players WHERE uuid='" . $uuid . "' ORDER BY id DESC;");
    if (mysqli_num_rows($banq) > 0) {
        while($row = mysqli_fetch_assoc($banq)) {
            $ban = new Ban();
            $ban->setreason($row['reason']);
            $ban->setpermanent($row['permanent'] == 1);
            $ban->setrelease($row['release']);
            $ban->setsource($row['source']);
            $ban->setactive($row['active'] == 1);
            array_push($bans, $ban);
        }
    }
    $kickq = mysqli_query($conn, "SELECT reason,source,time FROM kicks WHERE uuid='" . $uuid . "' ORDER BY id DESC;");
    if (mysqli_num_rows($kickq) > 0) {
        while($row = mysqli_fetch_assoc($kickq)) {
            $kick = new Kick();
            $kick->setreason($row['reason']);
            $kick->setsource($row['source']);
            $kick->settime($row['time']);
            array_push($kicks, $kick);
        }
    }
    $muteq = mysqli_query($conn, "SELECT `release`,source,reason,active FROM muted_players WHERE uuid='" . $uuid . "' ORDER BY id DESC;");
    if (mysqli_num_rows($muteq) > 0) {
        while($row = mysqli_fetch_assoc($muteq)) {
            $mute = new Mute();
            $mute->setrelease($row['release']);
            $mute->setsource($row['source']);
            $mute->setreason($row['reason']);
            $mute->setactive($row['active'] == 1);
            array_push($mutes, $mute);
        }
    }
    foreach($bans as $ban){
        $puns .= '<tr><td> ' . $ban->getsource() . ' </td><td> ' . $ban->getreason() . ' </td><td> ' . ($ban->getpermanent() ? 'Perm-' : '') . 'Ban </td><td> ' . date('n/j/y g:i:s A', strtotime($ban->getrelease())) . ' </td><td> ' . ($ban->getactive() ? 'True' : 'False') . ' </td></tr>';
    }
    foreach($mutes as $mute){
        $puns .= '<tr><td> ' . $mute->getsource() . ' </td><td> ' . $mute->getreason() . ' </td><td> Mute </td><td> ' . date('n/j/y g:i:s A', strtotime($mute->getrelease())) . ' </td><td> ' . ($mute->getactive() ? 'True' : 'False') . ' </td></tr>';
        $puns .= '';
    }
    foreach($kicks as $kick){
        $puns .= '<tr><td> ' . $kick->getsource() . ' </td><td> ' . $kick->getreason() . ' </td><td> Kick </td><td> ' . date('n/j/y g:i:s A', strtotime($kick->gettime())) . ' </td><td> N/A </td></tr>';
    }
    echo '<div class="portlet-body"><div class="row number-stats margin-bottom-30"><div class="caption-subject font-blue-madison">Rank:</div><p>' . getrankname($rank) . '</p><div class="caption-subject font-blue-madison">Past Names: </div><p><a href="https://namemc.com/s/' . $uuid . '" target="_blank">https://namemc.com/s/' . $username . '</a></p><div class="caption-subject font-blue-madison">Last Online:</div><p>' . $lastonline . '</p><div class="caption-subject font-blue-madison">Last Server:</div><p>' . $lastserver . '</p><div class="caption-subject font-blue-madison">IP Address:</div><p>' . $ip . '</p><div class="caption-subject font-blue-madison">Balance:</div><p>$' . $bal . '</p><div class="caption-subject font-blue-madison">Tokens:</div><p>' . $token . '</p></div><div class="row"><div class="portlet light"><div class="portlet-title"><div class="caption caption-md"><i class="icon-bar-chart theme-font hide"></i><span class="caption-subject font-blue-madison">Recent Transactions (24 hours)</span></div></div><div class="portlet-body"><div class="row number-stats margin-bottom-30"><div class="table-scrollable-borderless"><table class="table table-hover"><thead><tr><th> Name </th><th> Cost </th><th> Server </th><th> Date </th></tr></thead><tbody>' . $trans . '</tbody></table></div></div></div></div></div><div class="row"><div class="portlet light"><div class="portlet-title"><div class="caption caption-md"><i class="icon-bar-chart theme-font hide"></i><span class="caption-subject font-blue-madison">Global Punishments</span></div></div><div class="portlet-body"><div class="row number-stats margin-bottom-30"><div class="table-scrollable-borderless"><table class="table table-hover"><thead><tr><th> Source </th><th> Reason </th><th> Type </th><th> Date </th><th> Active </th></tr></thead><tbody>' . $puns . '</tbody></table></div></div></div></div></div>';