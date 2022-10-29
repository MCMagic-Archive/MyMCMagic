<?php
session_start();
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
function getRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $string;
}
$email = sanitize($_POST['email']);
if(!isset($email)){
    die("Invalid email");
}
$pass = sanitize($_POST['pass']);
$remember;
if(isset($_POST['remember_me'])){
    $remember = $_POST['remember_me'];
}
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_select_db($conn, "mymcmagic");
$qry = "SELECT status,uuid,password,hash,id FROM users WHERE email='" . $email . "';";
$result = mysqli_query($conn, $qry);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        if($row['status'] == 0){
            header("Location: .?tag=no-confirm&uuid=" . $row['uuid']);
            exit();
        }
        if($row['status'] == 2){
            header("Location: .?tag=suspended");
            exit();
        }
        $hashed = sha1($pass . $row['hash']);
        if($hashed == $row['password']){
            session_regenerate_id();
            $uuid = $row['uuid'];
            $_SESSION['UUID'] = $uuid;
            $_SESSION['EMAIL'] = $email;
            $_SESSION['ID'] = $row['id'];
            mysqli_select_db($conn, "MainServer");
            $getuser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username,rank FROM player_data WHERE uuid='" . $uuid . "';"));
            $_SESSION['USERNAME'] = $getuser['username'];
            $_SESSION['RANK'] = $getuser['rank'];
            if(isset($remember) && $remember){
                $token = getRandomString(24);
                setcookie("remember", $token, time()+86400*30);
                mysqli_select_db($conn, "mymcmagic");
                mysqli_query($conn, "DELETE FROM remember WHERE uuid='" . $uuid . "';");
                mysqli_query($conn, "INSERT INTO remember (uuid, token) VALUES ('" . $uuid . "','" . $token . "');");
            }
            session_write_close();
            if($_POST['redirect'] != '' && (0 === strpos($_POST['redirect'], 'https://my.mcmagic.us/') || 0 === strpos($_POST['redirect'], 'http://127.0.0.1/'))){
                header("Location: " . sanitize($_POST['redirect']));
                exit();
            }
            header("Location: profile/");
            exit();
        }
    }
}
header("Location: .?tag=inv-pass");