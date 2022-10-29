<?php
$uuid = sanitize($_POST['uuid']);
$conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
mysqli_select_db($conn, "mymcmagic");
$hasApp = mysqli_query($conn, "SELECT submitted FROM char_applications WHERE status != 5 AND uuid='" . $uuid . "';");
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
$experience = sanitize($_POST['experience']);
$characters = sanitize($_POST['characters']);
$mumble = sanitize($_POST['mumble']);
$account = sanitize($_POST['account']);
$applying = sanitize($_POST['applying']);
$quote = sanitize($_POST['quote']);
if($uuid == '' || $username == '' || $email == ''){
    die("");
}
$submitted = time();
if(mysqli_query($conn, "INSERT INTO char_applications (uuid,username,email,experience,characters,mumble,account,applying,quote,submitted) VALUES ('" . $uuid . "','" . $username . "','" . $email . "','" . $experience . "','" . $characters . "','" . $mumble . "','" . $account . "','" . $applying . "','" . $quote . "','" . $submitted . "');")){
    header("Location: submitted/");
} else {
    echo "Error submitting your Application! Please contact a Cast Member: " . mysqli_error($conn);
}