<?php

error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();

$pid = 0;
$show = FALSE;
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $query = "delete from session where skey = 'pid'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $query = "Insert into session (skey,value) VALUES ('pid',?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));


    $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));

    if (!$stmt->rowCount() > 0) {
        header("location: purchase.php", TRUE);
    }else{
        header("location: purchase1.php", TRUE);
    }
} else {
    header("location: purchase.php", TRUE);
}