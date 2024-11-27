<?php
session_start();
include('../config/db-con.php');

function getAll($table)
{
    global $conn;
    $query = "SELECT * FROM $table";
    return $query_run = mysqli_query($conn, $query);
}

function getAllClientsAsc() {
    global $conn;
    $query = "SELECT account_name, account_num, client_id FROM clients ORDER BY account_name ASC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return $result;
    } else {
        return "Error fetching clients: " . mysqli_error($conn);
    }
}


function getBilling()
{
    global $conn;
    $query = "SELECT billing.*, clients.account_name, clients.account_num, clients.zone
            FROM billing
            JOIN clients ON billing.client_id = clients.client_id";
    $query_run = mysqli_query($conn, $query);

    $billing = array();

    while($row = mysqli_fetch_assoc($query_run)) {
        $billing[] = $row;
    }

    return $billing;
}

function getPayment() {
    global $conn;

    //Fetch payments
    $payments_query = "
        SELECT payments.payment_id, payments.or_num, payments.payment_date, payments.payment_method, payments.payment_purpose, 
               billing.discounted_total AS amount, payments.amount_received, billing.billing_id, billing.billing_num, billing.billing_amount, billing.discounted_billing, billing.arrears, billing.surcharge, 
               billing.wqi_fee, billing.wm_fee, billing.installation_fee, billing.materials_fee, billing.tax, billing.status, clients.account_name, clients.account_num, 'payment' AS source
        FROM payments
        JOIN billing ON billing.billing_id = payments.billing_id
        JOIN clients ON billing.client_id = clients.client_id
    ";

    $payments_result = mysqli_query($conn, $payments_query);

    if (!$payments_result) {
        die("Error fetching payments: " . mysqli_error($conn));
    }

    $payments = array();
    while ($row = mysqli_fetch_assoc($payments_result)) {
        $payments[] = $row;
    }

    //Fetch other_payments
    $other_payments_query = "
        SELECT other_payments.payment_id, other_payments.or_num, other_payments.payment_date, 'N/A' AS payment_method, other_payments.payment_purpose, 
               other_payments.amount_due AS amount, 'N/A AS amount_received', 'N/A' AS billing_num, 0 AS billing_amount, 0 AS arrears, 0 AS surcharge, 
               0 AS wqi_fee, 0 AS wm_fee, 0 AS installation_fee, 0 AS materials_fee, 0 AS tax, 'N/A' as status, clients.account_name, clients.account_num, 'other_payment' AS source
        FROM other_payments
        JOIN clients ON other_payments.client_id = clients.client_id
    ";

    $other_payments_result = mysqli_query($conn, $other_payments_query);

    if (!$other_payments_result) {
        die("Error fetching other payments: " . mysqli_error($conn));
    }

    $other_payments = array();
    while ($row = mysqli_fetch_assoc($other_payments_result)) {
        $other_payments[] = $row;
    }

    //Fetch refund_payments
    $refund_payments_query = "
        SELECT refund_payments.payment_id, refund_payments.account_name, refund_payments.or_num, refund_payments.payment_date, 'N/A' AS payment_method, 'Refund' AS payment_purpose,
                refund_payments.amount_due as amount, 'N/A AS amount_received', 'N/A' AS billing_num, 0 AS billing_amount, 0 AS arrears, 0 AS surcharge, 0 AS wqi_fee, 0 AS wm_fee, 0 AS installation_fee, 0 AS materials_fee,
                0 AS tax, 'N/A AS status', 'N/A' AS account_num, 'refund_payment' AS source
        FROM refund_payments
    ";
    $refund_payments_result = mysqli_query($conn, $refund_payments_query);
    if (!$refund_payments_result) {
        die("Error fetching refund payments: " . mysqli_error($conn));
    }
    $refund_payments = array();
    while ($row = mysqli_fetch_assoc($refund_payments_result)) {
        $refund_payments[] = $row;
    }


    $all_payments = array_merge($payments, $other_payments, $refund_payments);

    usort($all_payments, function($a, $b) {
        return $b['or_num'] - $a['or_num'];
    });

    return $all_payments;
}


function getBillingId($id)
{
    global $conn;
    $query = "SELECT billing.*, clients.account_name, clients.account_num, clients.account_type, clients.address, clients.barangay
            FROM billing
            JOIN clients ON billing.client_id = clients.client_id
            WHERE billing.billing_id = $id";

    $query_run = mysqli_query($conn, $query);

    $billing = mysqli_fetch_assoc($query_run);

    return $billing;
}

function getPaymentId($id, $source)
{
    global $conn;

    if ($source === 'payment') {
        $query = "SELECT payments.*, billing.*, clients.*
                  FROM payments
                  JOIN billing ON billing.billing_id = payments.billing_id
                  JOIN clients ON billing.client_id = clients.client_id
                  WHERE payments.payment_id = ?";
    } elseif ($source === 'other_payment') {
        $query = "SELECT other_payments.*, clients.*
                  FROM other_payments
                  JOIN clients ON other_payments.client_id = clients.client_id
                  WHERE other_payments.payment_id = ?";
    } else {
        return null; // Invalid source
    }

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $query_run = mysqli_stmt_get_result($stmt);

    $paymentId = mysqli_fetch_assoc($query_run);

    mysqli_stmt_close($stmt);

    return $paymentId;
}


function getBillingHistory($id)
{
    global $conn;
    $query = "SELECT billing.* FROM billing
            JOIN clients ON billing.client_id = clients.client_id
            WHERE clients.client_id = $id";
    
    $query_run = mysqli_query($conn, $query);

    $billingHistory = array();

    if ($query_run) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $billingHistory[] = $row;
        }
    } else {
        echo "Error retrieving billing history: " . mysqli_error($conn);
    }

    return $billingHistory;
}

function getStatusHistory($client_id)
{
    global $conn;
    
    $query = "SELECT status_history.* 
              FROM status_history
              JOIN clients ON status_history.client_id = clients.client_id
              WHERE clients.client_id = $client_id
              ORDER BY status_history.date_updated DESC";
    
    $query_run = mysqli_query($conn, $query);

    $statusHistory = array();

    if ($query_run) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $statusHistory[] = $row;
        }
    } else {
        echo "Error retrieving status history: " . mysqli_error($conn);
    }

    return $statusHistory;
}


// function getPaymentHistory($id)
// {
//     global $conn;
//     $query = "
//         SELECT payments.*, 'payments' AS payment_type 
//         FROM payments
//         JOIN billing ON payments.billing_id = billing.billing_id
//         WHERE billing.client_id = $id
//         UNION ALL
//         SELECT other_payments.*, 'other_payments' AS payment_type 
//         FROM other_payments
//         WHERE other_payments.client_id = $id
//         ORDER BY payment_date DESC"; 
    
//     $query_run = mysqli_query($conn, $query);

//     $paymentHistory = array();

//     if ($query_run) {
//         while ($row = mysqli_fetch_assoc($query_run)) {
//             $paymentHistory[] = $row;
//         }
//     } else {
//         echo "Error retrieving payment history: " . mysqli_error($conn);
//     }

//     return $paymentHistory;
// }

function getPaymentHistory($id)
{
    global $conn;
    $query = "
        SELECT 
            payments.or_num, 
            payments.payment_purpose, 
            payments.amount_due, 
            payments.payment_date, 
            'payments' AS payment_type 
        FROM payments
        JOIN billing ON payments.billing_id = billing.billing_id
        WHERE billing.client_id = $id
        UNION ALL
        SELECT 
            other_payments.or_num, 
            other_payments.payment_purpose, 
            other_payments.amount_due, 
            other_payments.payment_date, 
            'other_payments' AS payment_type 
        FROM other_payments
        WHERE other_payments.client_id = $id
        ORDER BY payment_date DESC";
    
    $query_run = mysqli_query($conn, $query);

    $paymentHistory = array();

    if ($query_run) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $paymentHistory[] = $row;
        }
    } else {
        echo "Error retrieving payment history: " . mysqli_error($conn);
    }

    return $paymentHistory;
}

function getCombinedHistory($client_id) {
    $billingHistory = getBillingHistory($client_id);
    $paymentHistory = getPaymentHistory($client_id);

    // Combine both arrays
    $combinedHistory = array_merge($billingHistory, $paymentHistory);

    // Sort combined history by date
    usort($combinedHistory, function ($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });

    return $combinedHistory;
}



function getTotalClients()
{
    global $conn;
    $query = "SELECT COUNT(*) AS total_clients FROM clients";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $row = mysqli_fetch_assoc($query_run);
        return $row['total_clients'];
    } else {
        return "N/A";
    }
}

function getTotalPending()
{
    global $conn;
    $query = "SELECT SUM(total) AS total_pending FROM billing WHERE status IN ('Pending', 'Due')";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $row = mysqli_fetch_assoc($query_run);
        return $row['total_pending'];
    } else {
        return "N/A";
    }
}

function getTotalConsumption()
{
    global $conn;
    $query = "SELECT SUM(consumption) AS total_consumption
            FROM billing
            WHERE MONTH(reading_date) = MONTH(CURRENT_DATE())
            AND YEAR(reading_date) = YEAR(CURRENT_DATE())";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $row = mysqli_fetch_assoc($query_run);
        return $row['total_consumption'];
    } else {
        return "N/A";
    }
}

function getConsumptionByZone()
{
    global $conn;
    $query = "SELECT clients.zone, SUM(billing.consumption) AS zone_consumption
            FROM billing
            RIGHT JOIN clients ON billing.client_id = clients.client_id
            WHERE MONTH(billing.reading_date) = MONTH(CURRENT_DATE()) AND YEAR(billing.reading_date) = YEAR(CURRENT_DATE())
            GROUP BY clients.zone";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $data = array();

        $zones = array ("1A", "1B", "2", "3", "4A", "4B", "5", "6A", "6B", "7", "8", "9", "10", "11", "12");
        foreach ($zones as $zone) {
            $data[$zone] = 0;
        }

        while($row = mysqli_fetch_assoc($query_run)) {
            $zone_name = $row['zone'];
            $consumption = $row['zone_consumption'];

            $data[$zone_name] = $consumption;
        }
        return $data;
    } else {
        return "N/A";
    }
}

function getConsumptionAndBillingByZone()
{
    global $conn;
    $query = "SELECT clients.zone, 
                     COUNT(clients.client_id) AS total_clients,
                     SUM(billing.consumption) AS zone_consumption,
                     SUM(billing.discounted_total) AS zone_discounted_total
              FROM clients
              LEFT JOIN billing ON billing.client_id = clients.client_id 
                                 AND MONTH(billing.reading_date) = MONTH(CURRENT_DATE()) 
                                 AND YEAR(billing.reading_date) = YEAR(CURRENT_DATE())
              GROUP BY clients.zone";

    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        $data = array();

        $zones = array("1A", "1B", "2", "3", "4A", "4B", "5", "6A", "6B", "7", "8", "9", "10", "11", "12");
        foreach ($zones as $zone) {
            $data[$zone]['clients'] = 0;
            $data[$zone]['consumption'] = 0;
            $data[$zone]['discounted_total'] = 0; // Changed from 'billing_amount' to 'discounted_total'
        }

        while($row = mysqli_fetch_assoc($query_run)) {
            $zone_name = $row['zone'];
            $total_clients = $row['total_clients'];
            $consumption = $row['zone_consumption'];
            $discounted_total = $row['zone_discounted_total']; // Changed from 'zone_billing_amount' to 'zone_discounted_total'

            $data[$zone_name]['clients'] = $total_clients;
            $data[$zone_name]['consumption'] = $consumption;
            $data[$zone_name]['discounted_total'] = $discounted_total;
        }
        return $data;
    } else {
        return "N/A";
    }
}


function getTotalActiveClients() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_active_clients
            FROM clients WHERE status = 1";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $row = mysqli_fetch_assoc($query_run);
        return $row['total_active_clients'];
    } else {
        return "Error fetching total active clients";
    }
}

function getTotalInactiveClients() {
    global $conn;
    $query ="SELECT COUNT(*) AS total_inactive_clients
            FROM clients WHERE status = 0";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $row = mysqli_fetch_assoc($query_run);
        return $row['total_inactive_clients'];
    } else {
        return "Error fetching total inactive clients";
    }
}

function getTodaySales() {
    global $conn;

    $today = date('Y-m-d');

    $query_payments = "
        SELECT SUM(
            CASE 
                WHEN billing.status = 'Partially Paid' THEN payments.amount_received 
                ELSE payments.amount_due 
            END
        ) AS total_payments
        FROM payments
        JOIN billing ON payments.billing_id = billing.billing_id
        WHERE DATE(payments.payment_date) = '$today'
    ";
    
    $query_other_payments = "
        SELECT SUM(amount_due) AS total_other_payments
        FROM other_payments
        WHERE DATE(payment_date) = '$today'
    ";

    $query_refund_payments = "
        SELECT SUM(amount_due) AS total_refund_payments
        FROM refund_payments
        WHERE DATE(payment_date) = '$today'
    ";

    $query_run_payments = mysqli_query($conn, $query_payments);
    $query_run_other_payments = mysqli_query($conn, $query_other_payments);
    $query_run_refund_payments = mysqli_query($conn, $query_refund_payments);

    if ($query_run_payments && $query_run_other_payments && $query_run_refund_payments) {
        $row_payments = mysqli_fetch_assoc($query_run_payments);
        $row_other_payments = mysqli_fetch_assoc($query_run_other_payments);
        $row_refund_payments = mysqli_fetch_assoc($query_run_refund_payments);

        $total_sales = $row_payments['total_payments'] + $row_other_payments['total_other_payments'] + $row_refund_payments['total_refund_payments'];
        return $total_sales;
    } else {
        return "Error fetching today's sales";
    }
}


function getMonthlySales() {
    global $conn;

    $current_month = date('m');
    $current_year = date('Y');

    $query_payments = "
        SELECT SUM(
            CASE 
                WHEN billing.status = 'Partially Paid' THEN payments.amount_received 
                ELSE payments.amount_due 
            END
        ) AS total_payments
        FROM payments
        JOIN billing ON payments.billing_id = billing.billing_id
        WHERE MONTH(payments.payment_date) = '$current_month' AND YEAR(payments.payment_date) = '$current_year'
    ";
    
    $query_other_payments = "
        SELECT SUM(amount_due) AS total_other_payments
        FROM other_payments
        WHERE MONTH(payment_date) = '$current_month' AND YEAR(payment_date) = '$current_year'
    ";

    $query_refund_payments = "
        SELECT SUM(amount_due) AS total_refund_payments
        FROM refund_payments
        WHERE MONTH(payment_date) = '$current_month' AND YEAR(payment_date) = '$current_year'
    ";

    $query_run_payments = mysqli_query($conn, $query_payments);
    $query_run_other_payments = mysqli_query($conn, $query_other_payments);
    $query_run_refund_payments = mysqli_query($conn, $query_refund_payments);

    if ($query_run_payments && $query_run_other_payments && $query_run_refund_payments) {
        $row_payments = mysqli_fetch_assoc($query_run_payments);
        $row_other_payments = mysqli_fetch_assoc($query_run_other_payments);
        $row_refund_payments = mysqli_fetch_assoc($query_run_refund_payments);

        $total_sales = $row_payments['total_payments'] + $row_other_payments['total_other_payments'] + $row_refund_payments['total_refund_payments'];
        return $total_sales;
    } else {
        return "Error fetching monthly sales";
    }
}



function redirect($url, $message)
{
    $_SESSION['message'] = $message;
    header('Location: '.$url);
    exit();
}


?>