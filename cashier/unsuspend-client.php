<?php
include('../config/db-con.php');
include('../functions/functions.php');

if(isset($_POST['client_id'])) {

    $client_id = $_POST['client_id'];
    $unsuspend_client_query = "UPDATE clients SET status = 1 WHERE client_id = ?";
    $unsuspend_client_stmt = $conn->prepare($unsuspend_client_query);
    $unsuspend_client_stmt->bind_param("i", $client_id);

    if($unsuspend_client_stmt->execute()) { 
        echo '200';
    } else {
        echo '500';
    }
} else {
    echo '400';
}
?>
