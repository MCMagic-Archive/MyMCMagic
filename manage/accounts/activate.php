<?php
    require('../../auth.php');
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    $uuid = sanitize($_GET['uuid']);
    mysqli_query($conn, "UPDATE users SET status='1' WHERE uuid='" . $uuid . "';");
    header("Location: ../accounts");