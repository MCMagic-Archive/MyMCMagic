<?php
require('../auth.php');
date_default_timezone_set('America/New_York');
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
$uuid = sanitize($_POST['uuid']);
$username = sanitize($_POST['username']);
$type = sanitize($_POST['type']);
$id = sanitize($_POST['id']);
$interview = sanitize($_POST['interview']);
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
if($interview == ''){
    exit();
}
mysqli_query($conn, "UPDATE " . $type . "_applications SET interview='" . $interview . "' WHERE id=" . $id . ";");
$email = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE uuid='" . $uuid . "'"))['email'];
define('SENDER','MCMagic Applications <applications@mcmagic.us>');
header("Location: app.php?id=" . $id . "&type=" . $type);
define('RECIPIENT',$username . ' <' . $email . '>');
define('USERNAME','AKIAIX4I2F52KXAP4O6Q');
define('PASSWORD','AlAs61TedXF+6HlYv1RBrg4pAaqm1pcgfsVV2RzNz3Ll');
define('HOST','email-smtp.us-east-1.amazonaws.com');
define('PORT','587');
$apptype = 'MCMagic';
switch($type){
    case 'cm':
        $apptype='Cast Member';
        break;
    case 'char':
        $apptype='Character';
        break;
    case 'dev':
        $apptype='Developer';
        break;
}
define('SUBJECT',$apptype . ' Application Response');
define('BODY','<!DOCTYPE html><html xmlns=http://www.w3.org/1999/xhtml><meta content="text/html; charset=UTF-8"http-equiv=Content-Type><meta content="width=device-width,initial-scale=1"name=viewport><body style=margin:0;padding:0><table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td><table border=0 cellpadding=0 cellspacing=0 width=600 align=center style="border:1px solid #ccc;border-collpase:collapse"><tr><td style="padding:10px 10px 8px 5px"bgcolor=#4094EE align=center><img alt=MyMCMagic height=60 src=https://my.mcmagic.us/img/_logo.png style=display:block width=224><tr><td style="padding:40px 30px 40px 30px"bgcolor=#ffffff><table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=center><img alt=MyMCMagic height=150px src=https://minotar.net/helm/' . $username . '/256 style=display:block;border-radius:15% width=150px><br><br><tr><td style=color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:24px><b>You\'re one step closer!</b><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0">Congratulations! You\'ve been moved onto the next step in our application process. We would like to meet with you and discuss your past experience, intentions for the server, and more.<tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0">Since we know everyone has a busy schedule, we don\'t pick just one time you can meet with us; we give you a range of dates to choose from.<tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0"><b>Your interview dates are <u>' . $interview . '</u>.</b><tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0">Whenever you\'re available for your interview, join our Mumble server and contact Turner, xBrant or GrailMore. <i>If you\'re unable to make it to any of the interview dates,</i> please message a Manager on mumble as soon as possible. If you don\'t show up to any of the dates without contacting us, we will assume you\'re not interested in the position anymore and will cancel your application.<tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0">Once again, congratulations! We look forward to meeting with you.<tr><td style="color:#4094ee;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding:20px 0 5px 0">Sincerely,<br>MCMagic Management</table><tr><td style="color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12px;padding:30px 30px 30px 30px"bgcolor=#4094EE align=center><table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=left width=75%><b>© 2016 All rights reserved.</b><br>This is an automated message from MyMCMagic. Do not reply.<td align=right width=25%><table border=0 cellpadding=0 cellspacing=0><tr><td><a href=https://www.twitter.com/MCMagicParks target=_blank><img alt=Twitter height=31 src=https://my.mcmagic.us/assets/layout/img/twitter.png style=display:block width=38 border=0></a><td style=font-size:0;line-height:0 width=20></table></table></table></table>');
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