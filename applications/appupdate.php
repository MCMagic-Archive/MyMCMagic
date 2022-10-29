<?php
require('../auth.php');
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
$id = sanitize($_GET['id']);
$type = sanitize($_GET['type']);
$status = sanitize($_GET['status']);
if($_SESSION['RANK'] == 'coordinator' && $type != 'char'){
    header('Location: ../applications/');
    exit();
}
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
mysqli_query($conn, "UPDATE " . $type . "_applications SET status=" . $status . " WHERE id=" . $id . ";");
header('Location: ../applications/');