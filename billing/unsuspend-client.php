<?php
include('../config/db-con.php');
include('../functions/functions.php');

if(isset($_POST['client_id'])) {

    $client_id = $_POST['client_id'];
    $status = 'Active';

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the client's status in the clients table
        $unsuspend_client_query = "UPDATE clients SET status = 1 WHERE client_id = ?";
        $unsuspend_client_stmt = $conn->prepare($unsuspend_client_query);
        $unsuspend_client_stmt->bind_param("i", $client_id);

        if(!$unsuspend_client_stmt->execute()) {
            throw new Exception('Failed to update client status.');
        }

        // Insert the status update into the status_history table
        $insert_history_query = "INSERT INTO status_history (client_id, status, date_updated) VALUES (?, ?, NOW())";
        $insert_history_stmt = $conn->prepare($insert_history_query);
        $insert_history_stmt->bind_param("is", $client_id, $status);

        if(!$insert_history_stmt->execute()) {
            throw new Exception('Failed to insert status history.');
        }

        // Commit the transaction
        $conn->commit();
        echo '200';

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo '500';
    }
} else {
    echo '400';
}

?>
