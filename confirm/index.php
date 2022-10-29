<?php
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
session_start();
if(isset($_SESSION['USERNAME']) && (trim($_SESSION['USERNAME']) !== '')){
    header("Location: ../");
    exit();
}
$uuid = sanitize($_GET['uuid']);
$token = sanitize($_GET['token']);
$email = sanitize($_GET['email']);
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_select_db($conn, "mymcmagic");
$get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT token FROM emailconfirm WHERE uuid='" . $uuid . "';"));
if($get['token'] != $token){
    header("Location: ../?r=1");
    exit();
}
//Delete token
mysqli_query($conn, "DELETE FROM emailconfirm WHERE uuid='" . $uuid . "';");
mysqli_query($conn, "UPDATE users SET status='1' WHERE uuid='" . $uuid . "';");
mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','Email Address Confirmed','" . $email . "')");
mysqli_close($conn);
header("Location: ../?tag=account-created");
