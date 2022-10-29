<?php
    if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
        die('Invalid request');
    }
    date_default_timezone_set('America/New_York');
    $app = $_POST['app'];
    $open = $_POST['open'] == 'true' ? 0 : 1;
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "UPDATE app_types SET open=" . intval($open) . " WHERE name='" . $app . "';");