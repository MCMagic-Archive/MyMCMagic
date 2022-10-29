<?php
if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
    die('Invalid request');
}
    date_default_timezone_set('America/New_York');
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
    $uuid = $_POST['uuid'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "MainServer");

    #TRANSACTION DATA
    $transactions = array();
    $trans = mysqli_query($conn, "SELECT amount,type,source,server,timestamp FROM economy_logs WHERE uuid='" . $uuid . "' ORDER BY timestamp DESC;");
    if (mysqli_num_rows($trans) > 0) {
        while($row = mysqli_fetch_assoc($trans)) {
            $t = new Transaction();
            $t->setamount($row['amount']);
            $t->settype($row['type']);
            $t->setsource($row['source']);
            $t->setserver($row['server']);
            $t->settime($row['timestamp']);
            array_push($transactions, $t);
        }
    }
    
    echo '<table class="table table-hover" id="transactions"><thead><tr><th> Name </th><th> Cost </th><th> Server </th><th> Date </th></tr></thead><tbody id="trans">';
    foreach($transactions as $t){
        $amount = $t->getamount();
        $type = $t->gettype();
        $source = $t->getsource();
        $server = $t->getserver();
        $time = $t->gettime();
        if(strlen($source) >= 13 && substr($source, 0, 13) == "Command Block"){
            $source = "Ride, Show, Event";
        }
        if($source == 'plugin' && strlen($server) >= 2 && preg_match('^([s][0-9]*)$^', $server)){
            $source = 'Arcade Game';
            $server = 'Arcade';
        }
        if($source == ''){
            $source = "Unknown";
        }
        if($server == 'BungeeCord'){
            $server = "Network";
        }
        $currency = strpos($type, 'balance') !== false ? '$' : '✪ ';
        $date = date('n/j/y g:i:sa', $time);
        $color = $amount > 0 ? '#33cc00' : '#ff3333';
        echo '<tr><td> ' . $source . ' </td><td style="color:' . $color . '"> ' . $currency . abs($amount) . '</a> </td><td> ' . $server . ' </td><td> ' . $date . ' </td></tr>';
    }
    $mod = sizeof($transactions) % 15;
    if($mod != 0){
        for($i = 0; $i < (15 - $mod); $i++){
            echo '<tr style="height:36px"><td>&nbsp;</td><td> </td><td> </td><td> </td></tr>';
        }
    }
    echo '</tbody></table>';
    echo '<center><ul class="pagination" id="trans_page_navigation"></ul><div><input id="page_input" type="number" style="padding: 6px 12px;width:10%;" value="1"><a><input type="submit" value="Go" style="line-height: 1.6; padding:6px; margin:10px; background-color: white; border: none;" onclick="read_page();"></a></div></center>';
?>