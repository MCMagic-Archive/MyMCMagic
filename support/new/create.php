<?php
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
    $uuid = sanitize($_POST['uuid']);
    $id = getRandomString(20);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    if(trim($message) == ''){
        header("Location: ../new/");
        exit();
    }
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    mysqli_query($conn, "INSERT INTO ticketids (uuid, ticketid, subject, time) VALUES ('" . $uuid . "','" . $id . "','" . $subject . "','" . time() . "');");
    mysqli_query($conn, "INSERT INTO tickets (ticketid, uuid, message, time) VALUES ('" . $id . "','" . $uuid . "','" . $message . "','" . time() . "')");
    mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','Create Ticket','" . $subject . "')");
    header("Location: ../ticket.php?id=" . $id);