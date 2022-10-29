<?php
    require('../auth.php');
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    $type = sanitize($_POST['type']);
    $status = sanitize($_POST['status']);
    $is = sanitize($_POST['is']);
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "UPDATE " . $type . "_applications SET status=" . $status . " WHERE status=" . $is . ";");
    header("Location: ../applications/");