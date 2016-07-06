<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$choice = $_GET['choice'];
$col = $_GET['column'];
$sel = $_GET['get'];
$query = "SELECT DISTINCT " . $sel . " as sel FROM car where " . $col . " = '" . $choice . "' ";
$stmt = $conn->prepare($query);
$stmt->execute();
$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<option value="0">Please Choose Option</option>';
foreach ($rec as $row) {
    echo '<option value="' . $row['sel'] . '">' . $row['sel'] . '</option>';
}
?>