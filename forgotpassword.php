<?php
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
    header("Location: .");
    exit();
}
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_select_db($conn, "mymcmagic");
$qry = "SELECT status,uuid FROM users WHERE email='" . $email . "';";
$result = mysqli_query($conn, $qry);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        if($row['status'] == 0){
            header("Location: .?tag=no-confirm");
            exit();
        }
        if($row['status'] == 2){
            header("Location: .?tag=suspended");
            exit();
        }
        $uuid = $row['uuid'];
        $token = getRandomString(64);
        mysqli_query($conn, "DELETE FROM forgotpassword WHERE uuid='" . $uuid . "';");
        mysqli_query($conn, "INSERT INTO forgotpassword (uuid, token) VALUES ('" . $uuid . "','" . $token . "');");
        mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','Request Password Reset','" . $email . "')");
        mysqli_select_db($conn, "MainServer");
        $getuser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM player_data WHERE uuid='" . $uuid . "';"));
        mysqli_close($conn);
        $username = $getuser['username'];
        $link = "https://my.mcmagic.us/forgotpassword?uuid=" . $uuid . "&token=" . $token;
        define('SENDER','MyMCMagic <mymcmagic@mcmagic.us>');
        define('RECIPIENT',$username . ' <' . $email . '>');
        define('USERNAME','AKIAIX4I2F52KXAP4O6Q');
        define('PASSWORD','AlAs61TedXF+6HlYv1RBrg4pAaqm1pcgfsVV2RzNz3Ll');
        define('HOST','email-smtp.us-east-1.amazonaws.com');
        define('PORT','587');
        define('SUBJECT','MyMCMagic Password Reset');
        define('BODY','<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /></head><body style="margin:0;padding:0"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border:1px solid #ccc;border-collpase:collapse"><tr><td align="center" bgcolor="#4094EE" style="padding:10px 10px 8px 5px"><img src="https://my.mcmagic.us/img/_logo.png" alt="MyMCMagic" width="224" height="60" style="display:block" /></td></tr><tr><td bgcolor="#ffffff" style="padding:40px 30px 40px 30px"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="center"><img src="https://minotar.net/helm/' . $username . '/256" alt="MyMCMagic" width="150px" height="150px" style="display:block;border-radius:15%" /><br/><br/></td></tr><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:24px"><b>Password Recovery</b></td></tr><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 20px 0;text-decoration:none"> Hi, ' . $username . '! You requested a password reset link for your MyMCMagic account. All you have to do is <a href="' . $link . '" target="_blank">click here</a> to change your password! </td></tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0;text-decoration:none"> Didn\'t request this password reset? It\'s possible someone has accessed your account without your permission. </td><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0;text-decoration:none"> Contact a Cast Member on our server for assistance in regaining access to your account. </td></tr><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 30px 0;text-decoration:none"> Can\'t click the link? Copy this to your web browser: <a href="' . $link . '" target="_blank">' . $link . '</a></td></tr></table></td></tr><tr><td align="center" bgcolor="#4094EE" style="color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;padding:30px 30px 30px 30px"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="left" width="75%"><b>© 2016 All rights reserved.</b><br/> This is an automated message from MyMCMagic. Do not reply. </td><td align="right" width="25%"><table border="0" cellpadding="0" cellspacing="0"><tr><td><a href="https://www.twitter.com/MCMagicParks" target="_blank"><img src="https://my.mcmagic.us/assets/layout/img/twitter.png" alt="Twitter" width="38" height="31" style="display:block" border="0" /></a></td><td style="font-size:0;line-height:0" width="20">&nbsp;</td></tr></table></td></tr></table></td></tr></table></td></tr></table></body></html>');
        require_once 'Mail.php';
        $headers = array('From'=>SENDER,'To'=>RECIPIENT,'Subject'=>SUBJECT,'Content-type'=>'text/html');
        $smtpParams = array('host'=>HOST,'port'=>PORT,'auth'=>true,'username'=>USERNAME,'password'=>PASSWORD);
        // Create an SMTP client.
        $mail = Mail::factory('smtp', $smtpParams);
        // Send the email.
        $result = $mail->send(RECIPIENT, $headers, BODY);
        if (PEAR::isError($result)) {
          error_log("Email not sent. " .$result->getMessage() ."\n");
        }
    }
}
header("Location: .?tag=reset-sent");