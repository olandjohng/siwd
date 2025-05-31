<?php

require_once '../config/db-con.php';

$tableName = $_POST['tableName'];
$consumption = $_POST['consumption'];


$sql = "SELECT price FROM $tableName WHERE consumption >= ? ORDER BY consumption ASC LIMIT 1";

if((int)$consumption >= 51) {
    $sql = "SELECT MAX(price) as price from $tableName";
}

$stmt = $conn->prepare($sql);

if((int)$consumption < 51) {
    $stmt->bind_param("i", $consumption);
}
// $sql = "SELECT MAX(price) as price from $tableName";
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $additianal = max(0, $consumption - 50) * 60;
    echo (float)$row['price'] + $additianal;
} else {
    // echo $row['price'];
    echo "No price found for the given consumption.";
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();


?>