<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$make = $_GET['make'];
$model = $_GET['model'];
$submodel = $_GET['submodel'];
$query = "SELECT Price as price FROM car where make = '" . $make . "' AND model = '" . $model . "' AND submodel = '" . $submodel . "'";

$stmt = $conn->prepare($query);
$stmt->execute();
$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row = $rec[0];
if (isset($row['price'])) {
    echo '$'.$row['price'];
} else {
    echo '$10000.00';
}
?>