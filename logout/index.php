<?php
session_start();
$uuid = $_SESSION['UUID'];
unset($_SESSION['UUID']);
unset($_SESSION['EMAIL']);
unset($_SESSION['USERNAME']);
unset($_SESSION['ID']);
unset($_SESSION['RANK']);
unset($_SESSION['BANNED']);
if (isset($_COOKIE['remember'])) {
    unset($_COOKIE['remember']);
    setcookie('remember', '', time() - 3600, '/');
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "DELETE FROM remember WHERE uuid='" . $uuid . "';");
}
$tag = (isset($_GET['tag']) && $_GET['tag'] != '') ? ('?tag=' . $_GET['tag']) : '';
header("Location: ../" . $tag);
