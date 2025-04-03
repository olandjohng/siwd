
<?php

require_once '../config/db-con.php';


$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// $sql = "SELECT clients.*, billing.billing_amount, billing.wqi_fee, billing.wm_fee, billing.present_reading, billing.status
//         FROM clients
//         LEFT JOIN (
//             -- SELECT client_id, MAX(reading_date) AS max_reading_date
//             SELECT client_id, MAX(due_date) AS max_reading_date
//             FROM billing
//             GROUP BY client_id
//         ) AS latest_billing ON clients.client_id = latest_billing.client_id
//         LEFT JOIN billing ON billing.client_id = clients.client_id AND billing.due_date = latest_billing.max_reading_date
//         WHERE clients.account_name LIKE '%" . $conn->real_escape_string($searchQuery) ."%' OR clients.account_num LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";


$sql = "SELECT DISTINCT c.*, (b.billing_amount + b.arrears) as billing_amount, b.wqi_fee, b.wm_fee, b.present_reading, b.status,
        (b.total - coalesce(p.balance, 0)) as balance
        FROM clients c
        LEFT JOIN billing b on b.client_id = c.client_id 
        and b.due_date = (
            SELECT max(due_date) FROM billing d WHERE d.client_id = b.client_id 
        )
        LEFT JOIN (
            SELECT billing_id, sum(amount_received) as balance from payments group by billing_id 
        ) p on p.billing_id = b.billing_id 
        WHERE c.account_name LIKE '%" . $conn->real_escape_string($searchQuery) ."%' OR c.account_num LIKE '%". $conn->real_escape_string($searchQuery) ."%'
        ";


$result = $conn->query($sql);

if(mysqli_num_rows($result) > 0){?>
    <table class="table user-table table-hover align-items-center">
        <tbody>
            <?php
            while($row = mysqli_fetch_assoc($result)){
                $client_id = $row['client_id'];
                $account_num = $row['account_num'];
                $account_name = $row['account_name'];
                $account_type = $row['account_type'];
                $billing_amount = $row['billing_amount'];
                $wqi_fee = $row['wqi_fee'];
                $wm_fee = $row['wm_fee'];
                $status = $row['status'];
                $present_reading = $row['present_reading'];
                $balance = $row['balance'];
                switch($account_type) {
                    case '1':
                        $accountTypeValue = 'Residential';
                        break;
                    case '2':
                        $accountTypeValue = 'Semi-commercial';
                        break;
                    case '3':
                        $accountTypeValue = 'Commercial';
                        break;
                    case '4':
                        $accountTypeValue = 'Government';
                        break;
                    default:
                        $accountTypeValue = '';
                }

                $dueMessage = "";
                if ($status == 'Due') {
                    $dueMessage = "Selected client has an unsettled bill. Previous billing will be added to this billing.";
                }

                if (empty($present_reading) || is_null($present_reading)) {
                    $present_reading = '';
                }
                ?>

                <tr class="search-result" data-client-id="<?= $client_id; ?>" data-account-num="<?= $account_num; ?>" data-account-name="<?= $account_name; ?>" data-account-type="<?= $accountTypeValue; ?>" data-billing-amount="<?= $billing_amount; ?>" data-wqi-fee="<?= $wqi_fee; ?>" data-wm-fee="<?= $wm_fee; ?>" data-balance="<?= $balance ?>" data-present-reading="<?= $present_reading; ?>" data-status="<?= $status; ?>">
                    <td><?= $account_num; ?></td>
                    <td><?= $account_name; ?></td>
                    <td><?= $accountTypeValue; ?></td>
                </tr>
                <tr class="due-message" style="display: none;">
                    <td colspan="3"><?= $dueMessage; ?></td>
                </tr>

                <?php
            }
            ?>
        </tbody>
    </table>
    <?php
} else {
    echo "<h6>No results found.</h6>";
}

$conn->close();

?>
