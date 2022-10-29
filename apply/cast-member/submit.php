<?php
$uuid = sanitize($_POST['uuid']);
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$hasApp = mysqli_query($conn, "SELECT submitted FROM cm_applications WHERE status != 5 AND uuid='" . $uuid . "';");
if($hasApp && mysqli_num_rows($hasApp) > 0){
    while($row = mysqli_fetch_assoc($hasApp)){
        $time = $row['submitted'];
        if(($time + 2592000) > time()){
            die("You've already submitted this type of application in the past 30 days! <a href=\"../../profile\">Click here</a> to return to your Profile");
            break;
        }
    }
}
function sanitize($string){
    $string = addslashes($string);
    $pattern = "/;/";
    $replacement = "";
    return preg_replace($pattern, $replacement, $string);
}
$username = sanitize($_POST['username']);
$email = sanitize($_POST['email']);
$pastnames = sanitize($_POST['pastnames']);
$characters = sanitize($_POST['characters']);
$mumble1 = sanitize($_POST['mumble1']);
$mumble2 = sanitize($_POST['mumble2']);
$priorservers = sanitize($_POST['priorservers']);
$moderated = sanitize($_POST['moderated']);
$region = sanitize($_POST['region']);
$role = sanitize($_POST['role']);
$whyme = sanitize($_POST['whyme']);
if($uuid == '' || $username == '' || $email == ''){
    die("");
}
$submitted = time();
if(mysqli_query($conn, "INSERT INTO cm_applications (uuid,username,email,pastnames,characters,mumble1,mumble2,priorservers,moderated,region,role,whyme,submitted) VALUES ('" . $uuid . "','" . $username . "','" . $email . "','" . $pastnames . "','" . $characters . "','" . $mumble1 . "','" . $mumble2 . "','" . $priorservers . "','" . $moderated . "','" . $region . "','" . $role . "','" . $whyme . "','" . $submitted . "');")){
    header("Location: submitted/");
} else {
    echo "Error submitting your Application! Please contact a Cast Member: " . mysqli_error($conn);
}