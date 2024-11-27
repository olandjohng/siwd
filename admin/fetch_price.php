<?php

require_once '../config/db-con.php';

$tableName = $_POST['tableName'];
$consumption = $_POST['consumption'];

$sql = "SELECT price FROM $tableName WHERE consumption >= ? ORDER BY consumption ASC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $consumption);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['price'];
} else {
    echo "No price found for the given consumption.";
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();


?>