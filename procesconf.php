<?php

error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();

if (isset($_POST['confirm'])) {
    $pid = $_POST['pid'];
    $query = "delete from session where skey = 'pid'";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $query = "Insert into session (skey,value) VALUES ('pid',?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));

    header("Location:show.php", TRUE);
} else if (isset($_POST['cancel'])) {
    $pid = $_POST['pid'];
    $query = "DELETE FROM Item WHERE Pid = " . $pid;
    $conn->query($query);
    $query = "DELETE FROM Purchase WHERE Pid = " . $pid;
    $conn->query($query);
    
    header("Location:home.php", TRUE);
    echo '<script language="javascript">';
    echo 'alert("Purchase Request canceled")';
    echo '</script>';
}