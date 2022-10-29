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
$uuid = sanitize($_POST['uuid']);
$bio = sanitize($_POST['message']);
$twitter = sanitize($_POST['twitter']);
$instagram = sanitize($_POST['instagram']);
$beam = sanitize($_POST['beam']);
$favchar = sanitize($_POST['favchar']);
$favdisride = sanitize($_POST['favdisride']);
$favdismov = sanitize($_POST['favdismov']);
$location = sanitize($_POST['location']);
$birthday = sanitize($_POST['birthday']);
$favdispark = sanitize($_POST['favdispark']);
mysqli_query($conn, "INSERT INTO staff (uuid,bio,twitter,instagram,beam,favchar,favdisride,favdismov,location,birthday,favdispark) VALUES ('" . $uuid . "','" . $bio . "','" . $twitter . "','" . $instagram . "','" . $beam . "','" . $favchar . "','" . $favdisride . "','" . $favdismov . "','" . $location . "','" . $birthday . "','" . $favdispark . "') ON DUPLICATE KEY UPDATE bio='" . $bio . "',twitter='" . $twitter . "',instagram='" . $instagram . "',beam='" . $beam . "',favchar='" . $favchar . "',favdisride='" . $favdisride . "',favdismov='" . $favdismov . "',location='" . $location . "',birthday='" . $birthday . "',favdispark='" . $favdispark . "';");
header("Location: ../bio/");