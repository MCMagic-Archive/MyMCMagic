<?php
    require("../../auth.php");
    function sanitize($string){
        $string = addslashes($string);
        $pattern = "/;/";
        $replacement = "";
        return preg_replace($pattern, $replacement, $string);
    }
    $conn = mysqli_connect("mcmagicdb", "root", "cQr4qKQdkVQNFDrT");
    mysqli_select_db($conn, "mymcmagic");
    $status = sanitize($_POST['status']);
    $toggle = intval(sanitize($_POST['toggle'])) != intval($status);
    $uuid = sanitize($_POST['uuid']);
    $id = sanitize($_POST['ticketid']);
    $message = sanitize($_POST['message']);
    $subject = sanitize($_POST['subject']);
    if($toggle){
        mysqli_query($conn, "UPDATE ticketids SET status=" . ($status == 0 ? "1" : "0") . " WHERE ticketid='" . $id . "';");
        mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','" . ($status == 0 ? "Open" : "Close") . " Ticket','" . $subject . "')");
        header("Location: ticket.php?id=" . $id);
        exit();
    }
    if(strlen($message)<=0 || $message=='' || $message==' '){
        header("Location: ticket.php?id=" . $id);
        exit();
    }
    mysqli_query($conn, "UPDATE ticketids SET status=2,time=" . time() . " WHERE ticketid='" . $id . "';");
    mysqli_query($conn, "INSERT INTO tickets (ticketid, uuid, message, time) VALUES ('" . $id . "','" . $uuid . "','" . $message . "','" . time() . "');");
    mysqli_query($conn, "INSERT INTO activity (uuid, action, description) VALUES ('" . $uuid . "','Reply To Ticket','" . $subject . "')");
    header("Location: ticket.php?id=" . $id);