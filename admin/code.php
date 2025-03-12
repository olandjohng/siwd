<?php
include('../config/db-con.php');
include('../functions/functions.php');

// Insert
if(isset($_POST['add_user_btn']))
{
    $role = $_POST['role'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $designation = $_POST['designation'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0){
        echo "<script>alertify.error('Username already taken. Please choose a different username.');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO users (firstname, lastname, username, password, designation, role)
                        VALUES ('$firstname', '$lastname', '$username', '$hashed_password', '$designation', '$role')";

        if(mysqli_query($conn, $insert_query)) {
            redirect("users.php", "User added successfully");

        } else {
            redirect("users.php", "Something went wrong");
        }
    }

}
else if(isset($_POST['add_client_btn']))
{
    $account_name = $_POST['account_name'];
    $account_num = $_POST['account_num'];
    $account_type = $_POST['account_type'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $meter_num = $_POST['meter_num'];
    $zone = $_POST['zone'];
    $barangay = $_POST['barangay'];
    $status = $_POST['status'];

    $insert_client_query = "INSERT INTO clients (account_num, account_name, account_type, phone, zone, barangay, address, meter_num, status)
                            VALUES('$account_num', '$account_name', '$account_type', '$phone', '$zone', '$barangay', '$address', '$meter_num', '$status')";

    
    if(mysqli_query($conn, $insert_client_query)) {
        redirect("clients.php", "Client added successfully.");
    } else {
        redirect("clients.php", "Something went wrong.");
    }
}
else if(isset($_POST['add_billing_btn']))
{
    
    $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
    $billing_num = mysqli_real_escape_string($conn, $_POST['billing_num']);
    $covered_from = mysqli_real_escape_string($conn, $_POST['covered_from']);
    $covered_to = mysqli_real_escape_string($conn, $_POST['covered_to']);
    $reading_date = mysqli_real_escape_string($conn, $_POST['reading_date']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $previous_reading = mysqli_real_escape_string($conn, $_POST['previous_reading']);
    $present_reading = mysqli_real_escape_string($conn, $_POST['present_reading']);
    $consumption = mysqli_real_escape_string($conn, $_POST['consumption']);
    $billing_amount = mysqli_real_escape_string($conn, $_POST['billing_amount']);
    $discounted_billing = mysqli_real_escape_string($conn, $_POST['discounted_billing']);
    $arrears = mysqli_real_escape_string($conn, $_POST['arrears']);
    $surcharge = mysqli_real_escape_string($conn, $_POST['surcharge']);
    $materials_fee = mysqli_real_escape_string($conn, $_POST['materials_fee']);
    $installation_fee = mysqli_real_escape_string($conn, $_POST['installation_fee']);
    $wqi_fee = mysqli_real_escape_string($conn, $_POST['wqi_fee']);
    $wm_fee = mysqli_real_escape_string($conn, $_POST['wm_fee']);
    $subtotal = mysqli_real_escape_string($conn, $_POST['subtotal']);
    $tax = mysqli_real_escape_string($conn, $_POST['tax']);
    $total = mysqli_real_escape_string($conn, $_POST['total']);
    $discount_type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $discount_amount = mysqli_real_escape_string($conn, $_POST['discount_amount']);
    $discounted_total = mysqli_real_escape_string($conn, $_POST['discounted_total']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $previous_status_query = "SELECT status FROM billing WHERE client_id = ? AND reading_date < CURDATE() ORDER BY reading_date DESC LIMIT 1";
    $stmt_previous_status = mysqli_prepare($conn, $previous_status_query);
    mysqli_stmt_bind_param($stmt_previous_status, "i", $client_id);
    mysqli_stmt_execute($stmt_previous_status);
    $result_previous_status = mysqli_stmt_get_result($stmt_previous_status);
    $previous_status_row = mysqli_fetch_assoc($result_previous_status);
    $previous_status = $previous_status_row ? $previous_status_row['status'] : '';

    if ($previous_status === 'Due') {
        $update_previous_status_query = "UPDATE billing SET status = 'Rolled Over' WHERE client_id = ? AND status = 'Due'";
        $stmt_update_previous_status = mysqli_prepare($conn, $update_previous_status_query);
        mysqli_stmt_bind_param($stmt_update_previous_status, "i", $client_id);
        if(mysqli_stmt_execute($stmt_update_previous_status)) {
            
        } else {
            redirect("add-billing.php", "Error updating previous billing status: " . mysqli_error($conn));
        }
    }

    $insert_billing_query = "INSERT INTO billing (client_id, billing_num, covered_from, covered_to, reading_date, due_date,
                            previous_reading, present_reading, consumption, billing_amount, discounted_billing, arrears, surcharge, materials_fee,
                            installation_fee, wqi_fee, wm_fee, subtotal, tax, total, discount_type, discount_amount, discounted_total, status)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insert_billing_query);

    mysqli_stmt_bind_param($stmt, "isssssiiidddddddddddsdds", $client_id, $billing_num, $covered_from, $covered_to, $reading_date,
                            $due_date, $previous_reading, $present_reading, $consumption, $billing_amount, $discounted_billing, $arrears, $surcharge,
                            $materials_fee, $installation_fee, $wqi_fee, $wm_fee, $subtotal, $tax, $total, $discount_type, $discount_amount, $discounted_total, $status);
    
    echo "Insert Query: $insert_billing_query <br>";

    var_dump($client_id);
    var_dump($insert_billing_query);
    var_dump($billing_num); 

    if(mysqli_stmt_execute($stmt)) {
        redirect("billing.php", "Billing created successfully.");
    } else {
        $error_message = mysqli_error($conn);
        echo "Error inserting billing: $error_message <br>";
        redirect("add-billing.php", "Error: $error_message");
    }
    
    mysqli_stmt_close($stmt);
}


else if(isset($_POST['add_cashpayment_btn']))
{
    mysqli_begin_transaction($conn);
    
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // die;

    $billing_id = mysqli_real_escape_string($conn, $_POST['billing_id']);
    $or_num = mysqli_real_escape_string($conn, $_POST['or_num']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_purpose = mysqli_real_escape_string($conn, $_POST['payment_purpose']);
    $amount_due = mysqli_real_escape_string($conn, $_POST['total_amount_due']);
    $amount_received = mysqli_real_escape_string($conn, $_POST['payment_amount']);
    $change_amount = mysqli_real_escape_string($conn, $_POST['change']);
    $note = mysqli_real_escape_string($conn, $_POST['payment_note']);

    if ($amount_received >= $amount_due) {
        $status = 'Paid';

        $update_billing_query = "UPDATE billing SET status = '$status', remaining_balance = 0 WHERE billing_id = '$billing_id'";
        mysqli_query($conn, $update_billing_query);

        $insert_payment_query = "INSERT INTO payments (billing_id, or_num, payment_method, payment_date, payment_purpose, amount_due, amount_received, change_amount, note)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $insert_payment_query);

        mysqli_stmt_bind_param($stmt, "issssddds", $billing_id, $or_num, $payment_method, $payment_date, $payment_purpose, $amount_due, $amount_received, $change_amount, $note);

        if(mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            redirect("payment-success.php", "Payment inserted successfully.");
        } else {
            mysqli_rollback($conn);
            redirect("add-payment.php?id=$billing_id", "An error occured. Please try again.");
        }

        mysqli_stmt_close($stmt);
    } else {
        mysqli_rollback($conn);
        redirect("add-payment.php?id=$billing_id", "Billing not updated. Payment amount must be greater than or equal to the amount due.");
    }
}

else if(isset($_POST['add_bankcheck_btn']))
{
    mysqli_begin_transaction($conn);

    $billing_id = mysqli_real_escape_string($conn, $_POST['billing_id']);
    $or_num = mysqli_real_escape_string($conn, $_POST['or_num']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_purpose = mysqli_real_escape_string($conn, $_POST['payment_purpose']);
    $amount_due = mysqli_real_escape_string($conn, $_POST['total_amount_due3']);
    $amount_received = mysqli_real_escape_string($conn, $_POST['check_amount']);
    $check_number = mysqli_real_escape_string($conn, $_POST['check_number']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $check_date = mysqli_real_escape_string($conn, $_POST['check_date']);
    $note = mysqli_real_escape_string($conn, $_POST['payment_note3']);

    if ($amount_received >= $amount_due) {
        $status = 'Paid';

        $update_billing_query = "UPDATE billing SET status = '$status', remaining_balance = 0 WHERE billing_id = '$billing_id'";
        mysqli_query($conn, $update_billing_query);

        $insert_check_payment_query = "INSERT INTO payments (billing_id, or_num, payment_method, payment_date, payment_purpose, amount_due, amount_received, check_number, bank_name, check_date, note)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $insert_check_payment_query);

        mysqli_stmt_bind_param($stmt, "issssddssss", $billing_id, $or_num, $payment_method, $payment_date, $payment_purpose, $amount_due, $amount_received, $check_number, $bank_name, $check_date, $note);

        if(mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            redirect("payment-success.php", "Payment inserted sucessfully.");
        } else {
            mysqli_rollback($conn);
            redirect("add-payment.php?id=$billing_id", "An error occured. Please try again.");
        }

        mysqli_stmt_close($stmt);
    } else {
        mysqli_rollback($conn);
        redirect("add-payment.php?id=$billing_id", "Billing not updated. Check amount not valid.");
    }
    
}

else if(isset($_POST['add_partial_btn']))
{
    // Start the transaction
    mysqli_begin_transaction($conn);

    try {
        if (!isset($_POST['billing_id']) || empty($_POST['billing_id'])) {
            throw new Exception("Billing ID is missing in the form submission.");
        }
        // Escape input values
        $billing_id = mysqli_real_escape_string($conn, $_POST['billing_id']);
        $or_num = mysqli_real_escape_string($conn, $_POST['or_num']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
        $payment_purpose = mysqli_real_escape_string($conn, $_POST['payment_purpose']);
        $amount_due = mysqli_real_escape_string($conn, $_POST['total_amount_due2']);
        $amount_received = mysqli_real_escape_string($conn, $_POST['partial_amount']);
        $remaining_balance = mysqli_real_escape_string($conn, $_POST['remaining_balance']);
        $note = mysqli_real_escape_string($conn, $_POST['payment_note2']);


        $get_payment_details_sql = "select 
            ifnull(sum(water_qty_improvement), 0) as wqi_fee,
            ifnull(sum(water_management), 0) as wm_fee,
            ifnull(sum(installation), 0) as installation_fee,
            ifnull(sum(material), 0) as materials_fee,
            ifnull(sum(tax), 0) as tax_fee,
            ifnull(sum(arrears), 0) as arrears_fee,
            ifnull(sum(surcharge), 0) as surcharge
        from payments where billing_id = ?";

        $stmt =  mysqli_prepare($conn, $get_payment_details_sql);
        mysqli_stmt_bind_param($stmt, 'i', $billing_id);
        mysqli_stmt_execute($stmt);
        $payment_result = mysqli_stmt_get_result($stmt);
        $payment_details = mysqli_fetch_assoc($payment_result);
        mysqli_stmt_close($stmt);
        
        $get_billing_details_sql = "select 
            discounted_billing, 
            arrears as arrears_fee, 
            surcharge, 
            materials_fee, 
            installation_fee, 
            wqi_fee, wm_fee, tax as tax_fee, discount_amount,
        (discounted_billing + arrears + surcharge + materials_fee + installation_fee + wqi_fee + wm_fee + tax) 
            as subtotal 
        from billing b where billing_id = ?";
        
        $stmt = mysqli_prepare($conn, $get_billing_details_sql);
        mysqli_stmt_bind_param($stmt, 'i', $billing_id);
        mysqli_stmt_execute($stmt);
        $billing_result = mysqli_stmt_get_result($stmt);
        $billing_details = mysqli_fetch_assoc($billing_result);
        mysqli_stmt_close($stmt);


        $map = function() {
            return floatval(0);
        };

        $payments = $payment_details;
        
        $payment_amount = (float) $amount_received;
   
        foreach ($payment_details as $key => $value) { 
            $item_amount =  $billing_details[$key] - $payment_details[$key];

            if($item_amount == 0) {
                $payments[$key] = 0.00;
                continue;
            }

            if($payment_amount < $item_amount){
                $payments[$key] = $payment_amount;
                $payment_amount -= $payment_amount;
                break;
            }

            $payments[$key] = $item_amount;
            $payment_amount -= $item_amount;
        }
        
        $sum_of_fees = array_reduce(array_values($payments), function ($carry, $value) {
            return $carry += $value;
        }, 0 );
        
        
        if($sum_of_fees == 0 && $payment_amount) {
            $payments['arrears_fee'] = $payment_amount;
            $payment_amount = 0;
        }


        $inser_payment_sql = "INSERT INTO payments (
            billing_id, or_num, 
            payment_method, payment_date, 
            payment_purpose, amount_due, 
            amount_received, note,
            arrears, surcharge,
            water_qty_improvement, water_management, 
            tax, installation, 
            material, bill_amount
            )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $inser_payment_sql);
        
        mysqli_stmt_bind_param($stmt, 'issssddsdddddddd', 
            $billing_id, $or_num, 
            $payment_method, $payment_date, 
            $payment_purpose, $amount_due, 
            $amount_received, $note,
            $payments['arrears_fee'], $payments['surcharge'],
            $payments['wqi_fee'], $payments['wm_fee'],
            $payments['tax_fee'], $payments['installation_fee'], 
            $payments['materials_fee'], $payment_amount
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting payment: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);

        $new_status = ($remaining_balance > 0) ? 'Partially Paid' : 'Paid';
        
        $update_billing_sql = "
            UPDATE billing 
            SET remaining_balance = ?, status = ?
            WHERE billing_id = ?
        ";

        $stmt = mysqli_prepare($conn, $update_billing_sql);
        mysqli_stmt_bind_param($stmt, 'dsi', $remaining_balance, $new_status, $billing_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error updating billing record: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        redirect("payment-success.php", "Payment inserted successfully.");

        die();
        // Fetch current billing status
        // $billing_query = "SELECT status FROM billing WHERE billing_id = ?";
        // $stmt = mysqli_prepare($conn, $billing_query);
        // mysqli_stmt_bind_param($stmt, 'i', $billing_id);
        // mysqli_stmt_execute($stmt);
        // $billing_result = mysqli_stmt_get_result($stmt);
        // $billing = mysqli_fetch_assoc($billing_result);
        // mysqli_stmt_close($stmt);

        // if (!$billing) {
        //     throw new Exception("Error fetching billing details: " . mysqli_error($conn));
        // }

        // Determine new status
        // $new_status = ($remaining_balance > 0) ? 'Partially Paid' : 'Paid';

        //get 

        // Insert payment into payments table using prepared statement
        // $insert_payment_query = "
        //     INSERT INTO payments (billing_id, or_num, payment_method, payment_date, payment_purpose, amount_due, amount_received, note)
        //     VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        // ";
        
        // $stmt = mysqli_prepare($conn, $insert_payment_query);
        // mysqli_stmt_bind_param($stmt, 'issssdds', $billing_id, $or_num, $payment_method, $payment_date, $payment_purpose, $amount_due, $amount_received, $note);

        // if (!mysqli_stmt_execute($stmt)) {
        //     throw new Exception("Error inserting payment: " . mysqli_stmt_error($stmt));
        // }
        // mysqli_stmt_close($stmt);

        // Update billing table using prepared statement
        // $update_billing_query = "
        //     UPDATE billing 
        //     SET remaining_balance = ?, status = ?
        //     WHERE billing_id = ?
        // ";
        // $stmt = mysqli_prepare($conn, $update_billing_query);
        // mysqli_stmt_bind_param($stmt, 'dsi', $remaining_balance, $new_status, $billing_id);

        // if (!mysqli_stmt_execute($stmt)) {
        //     throw new Exception("Error updating billing record: " . mysqli_stmt_error($stmt));
        // }
        // mysqli_stmt_close($stmt);

        // Commit the transaction
        // mysqli_commit($conn);
        // redirect("payment-success.php", "Payment inserted successfully.");
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($conn);
        echo 'error: ' . $e->getMessage();
        // redirect("add-payment.php?id=$billing_id", "An error occurred. Please try again. " . $e->getMessage());
    }
}



else if(isset($_POST['add_otherpayment_btn']))
{
    mysqli_begin_transaction($conn);

    $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
    $or_num = mysqli_real_escape_string($conn, $_POST['or_num']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_purpose = mysqli_real_escape_string($conn, $_POST['payment_purpose']);
    $amount_due = mysqli_real_escape_string($conn, $_POST['amount_due']);
    $amount_received = mysqli_real_escape_string($conn, $_POST['payment_amount']);
    $change_amount = mysqli_real_escape_string($conn, $_POST['change']);
    $note = mysqli_real_escape_string($conn, $_POST['payment_note']);

    $insert_other_payment_query = "INSERT INTO other_payments (client_id, or_num, payment_date, payment_purpose, amount_due, amount_received, change_amount, note)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_other_payment_query);
    
    mysqli_stmt_bind_param($stmt, "isssddds", $client_id, $or_num, $payment_date, $payment_purpose, $amount_due, $amount_received, $change_amount, $note);

    if(mysqli_stmt_execute($stmt)) {
        mysqli_commit($conn);
        redirect("payment-success.php", "Payment inserted successfully.");
    } else {
        mysqli_rollback($conn);
        redirect("add-payment-other.php", "An error occurred. Please try again.");
    }

    mysqli_stmt_close($stmt);
}

else if(isset($_POST['add_refundpayment_btn']))
{
    mysqli_begin_transaction($conn);

    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $or_num = mysqli_real_escape_string($conn, $_POST['or_num']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_purpose = mysqli_real_escape_string($conn, $_POST['payment_purpose']);
    $amount_due = mysqli_real_escape_string($conn, $_POST['amount_due']);
    $amount_received = mysqli_real_escape_string($conn, $_POST['payment_amount']);
    $change_amount = mysqli_real_escape_string($conn, $_POST['change']);
    $note = mysqli_real_escape_string($conn, $_POST['payment_note']);

    $insert_refund_payment_query = "INSERT INTO refund_payments (account_name, or_num, payment_date, payment_purpose, amount_due, amount_received, change_amount, note)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_refund_payment_query);

    mysqli_stmt_bind_param($stmt, "ssssddds", $account_name, $or_num, $payment_date, $payment_purpose, $amount_due, $amount_received, $change_amount, $note);

    if(mysqli_stmt_execute($stmt)) {
        mysqli_commit($conn);
        redirect("payment-success.php", "Payment inserted successfully.");
    } else {
        mysqli_rollback($conn);
        redirect("add-payment-other.php", "An error occurred. Please try again.");
    }

    mysqli_stmt_close($stmt);
}

//Update
else if(isset($_POST['update_client_btn'])) 
{
    $client_id = $_POST['client_id'];
    $account_name = $_POST['account_name'];
    $account_num = $_POST['account_num'];
    $account_type = $_POST['account_type'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $meter_num = $_POST['meter_num'];
    $zone = $_POST['zone'];
    $barangay = $_POST['barangay'];
    $status = $_POST['status'];

    $update_client_query = "UPDATE clients SET account_num='$account_num', account_name='$account_name', account_type='$account_type',
                        phone='$phone', zone='$zone', barangay='$barangay', address='$address', meter_num='$meter_num', status='$status' WHERE client_id=$client_id; ";
    
    if(mysqli_query($conn, $update_client_query)) {
        redirect("update-client.php?id=$client_id", "Client updated successfully.");
    } else {
        redirect("update-client.php?id=$client_id", "Something went wrong.");
    }
}

else if(isset($_POST['update_billing_btn']))
{
    $billing_id = $_POST['billing_id'];
    $client_id = $_POST['client_id'];
    $billing_num = $_POST['billing_num'];
    $covered_from = $_POST['covered_from'];
    $covered_to = $_POST['covered_to'];
    $reading_date = $_POST['reading_date'];
    $due_date = $_POST['due_date'];
    $previous_reading = $_POST['previous_reading'];
    $present_reading = $_POST['present_reading'];
    $consumption = $_POST['consumption'];
    $billing_amount = $_POST['billing_amount'];
    $arrears = $_POST['arrears'];
    $surcharge = $_POST['surcharge'];
    $materials_fee = $_POST['materials_fee'];
    $installation_fee = $_POST['installation_fee'];
    $wqi_fee = $_POST['wqi_fee'];
    $wm_fee = $_POST['wm_fee'];
    $subtotal = $_POST['subtotal'];
    $tax = $_POST['tax'];
    $total = $_POST['total'];
    $status = $_POST['status'];

    $update_billing_query = "UPDATE billing SET client_id='$client_id', billing_num='$billing_num', covered_from='$covered_from', covered_to='$covered_to',
                            reading_date='$reading_date', due_date='$due_date', previous_reading='$previous_reading', present_reading='$present_reading',
                            consumption='$consumption', billing_amount='$billing_amount', arrears='$arrears', surcharge='$surcharge', materials_fee='$materials_fee',
                            installation_fee='$installation_fee', wqi_fee='$wqi_fee', wm_fee='$wm_fee', subtotal='$subtotal', tax='$tax', total='$total', status='$status'
                            WHERE billing_id='$billing_id'";

    if(mysqli_query($conn, $update_billing_query)) {
        redirect("update-billing.php?id=$billing_id", "Billing updated successfully.");
    } else {
        redirect("update-billing.php?id=$billing_id", "Something went wrong.");
    }
}

// Delete
else if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $delete_user_query = "DELETE FROM users WHERE user_id = ?";
    $delete_user_stmt = $conn->prepare($delete_user_query);
    $delete_user_stmt->bind_param("i", $user_id);

    if($delete_user_stmt->execute()) {
        echo '200';
    } else {
        echo '500';
    }
}
else if(isset($_POST['client_id'])) {
    $client_id = $_POST['client_id'];

    $delete_client_query = "DELETE FROM clients WHERE client_id = ?";
    $delete_client_stmt = $conn->prepare($delete_client_query);
    $delete_client_stmt->bind_param("i", $client_id);

    if($delete_client_stmt->execute()) {
        echo '200';
    } else {
        echo '500';
    }
}
else if(isset($_POST['billing_id'])) {
    $billing_id = $_POST['billing_id'];

    $delete_billing_query = "DELETE FROM billing WHERE billing_id = ?";
    $delete_billing_stmt = $conn->prepare($delete_billing_query);
    $delete_billing_stmt->bind_param("i", $billing_id);

    if($delete_billing_stmt->execute()) {
        echo '200';
    } else {
        echo '500';
    }
}
else if(isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    $delete_payment_query = "DELETE from payments WHERE payment_id = ?";
    $delete_payment_stmt = $conn->prepare($delete_payment_query);
    $delete_payment_stmt->bind_param("i", $payment_id);

    if($delete_payment_stmt->execute()) {
        echo '200';
    } else {
        echo '500';
    }
}

