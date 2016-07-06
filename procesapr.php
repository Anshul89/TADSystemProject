<?php

error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$pid = 0;
$user = '';
$reject = false;

if (isset($_POST['approve'])) {
    $pid = $_POST['pid'];
} else if (isset($_POST['reject'])) {
    $pid = $_POST['pid'];
    $reject = true;
}
if ($pid > 0) {
    $query = "SELECT * FROM session where skey = 'user'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $val = $rec[0]['value'];
    $status = $_POST['status'];
    if (!$reject) {
        if ($status == "Pending") {
            $apr = ($val == 'tucker' ? 'Mr. Tucker' : 'GM');
            $query = "UPDATE Purchase SET Status='Approved by " . $apr . "', AppGM = TRUE WHERE pid=?";
        } else {
            $query = "UPDATE Purchase SET Status='Approved', AppGM = TRUE WHERE pid=?";
        }
    } else {
        $query = "UPDATE Purchase SET Status='Rejected', AppGM = TRUE WHERE pid=?";
    }
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));

    $query = "Insert into session (skey,value) VALUES ('pid',?)";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    header("Location: show.php", TRUE);
}else{
    header("Location: search.php", TRUE);
}