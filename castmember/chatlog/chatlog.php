<?php
    if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        die('Invalid request');
    }
    date_default_timezone_set('America/New_York');
    $username = $_POST['username'];
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "MainServer");
    $guuid = mysqli_query($conn, "SELECT uuid FROM player_data WHERE username='" . $username . "';");
    $uuid = '';
    $response = false;
    while($row = mysqli_fetch_assoc($guuid)) {
        $uuid = $row['uuid'];
        $response = true;
        break;
    }
    if(!$response){
        echo 'No Guest found by that username!';
        exit();
    }
    $qry = mysqli_query($conn, "SELECT timestamp, user, message FROM chat WHERE user='" . $uuid . "' ORDER BY timestamp DESC LIMIT 0,5000;");
    $chat = '';
    while($row = mysqli_fetch_assoc($qry)) {
        $chat .= "<tr><td>" . $row['message'] . "</td><td>" . date('F jS, Y \a\t g:i A', strtotime($row['timestamp'])) . "</td></tr>";
    }

    echo '<div class="portlet-body"><div class="row number-stats margin-bottom-30"><div class="table-scrollable-borderless"><table class="table table-hover"><thead><tr><th> Message </th><th> Date </th></tr></thead><tbody>' . $chat . '</tbody></table></div></div></div></div></div>';