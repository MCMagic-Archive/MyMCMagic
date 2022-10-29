<?php
    require('../auth.php');
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    $type = sanitize($_POST['type']);
    $status = intval(sanitize($_POST['status']));
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "DELETE FROM " . $type . "_applications WHERE status=" . $status . ";");
    header("Location: ../applications/");