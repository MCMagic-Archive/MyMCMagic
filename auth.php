<?php
session_start();
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http') . '://' .  $_SERVER['HTTP_HOST'];
$path = $_SERVER['REQUEST_URI'];
$url = $base_url . $path;
if (!isset($_SESSION['UUID']) || (trim($_SESSION['UUID']) == '') || !isset($_SESSION['USERNAME']) || (trim($_SESSION['USERNAME']) == '')) {
    header("Location: /?redir=" . $url);
    exit();
}
function startsWith($haystack, $needle){
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}
$rank = strtolower($_SESSION['RANK']);
if(!isset($rank) || trim($rank) == ''){
    header("Location: /?redir=" . $url);
    exit();
}
if(!($rank == 'owner' || $rank == 'manager' || $rank == 'developer')){
    if(startsWith($path, '/manage/')){
        header('HTTP/1.0 404 Not Found');
        include('404.php');
        die();
    }
}
if(!($rank == 'owner' || $rank == 'manager' || $rank == 'developer' || $rank == 'coordinator')){
    if(startsWith($path, '/applications/')){
        header('HTTP/1.0 404 Not Found');
        include('404.php');
        die();
    }
}
if(!($rank == 'owner' || $rank == 'manager' || $rank == 'developer' || $rank == 'coordinator' || $rank == 'castmember' || $rank == 'earningmyears')){
    if(startsWith($path, '/castmember/')){
        header('HTTP/1.0 404 Not Found');
        include('404.php');
        die();
    }
}
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$s = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM users WHERE id='" . $_SESSION['ID'] . "';"))['status'];
if(intval($s) != 1){
    header("Location: /logout/" . (intval($s) == 2 ? "?tag=suspended" : ""));
    exit();
}
mysqli_select_db($conn, "MainServer");
$_SESSION['RANK'] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT rank FROM player_data WHERE uuid='" . $_SESSION['UUID'] . "';"))['rank'];
$_SESSION['BANNED'] = intval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT active FROM banned_players WHERE uuid='" . $_SESSION['UUID'] . "' AND active=1 LIMIT 0,1;"))['active']) == 1;
if($_SESSION['BANNED']){
    if(!startsWith($path, '/support/') && !startsWith($path, '/profile/settings/')){
        header("Location: /support/");
        exit();
    }
}