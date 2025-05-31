<?php

require_once '../config/db-con.php';

$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("
    SELECT clients.client_id, clients.account_num, clients.account_name, clients.account_type
    FROM clients
    WHERE clients.account_name LIKE ? OR clients.account_num LIKE ?
");

$searchQueryParam = '%' . $searchQuery . '%';
$stmt->bind_param('ss', $searchQueryParam, $searchQueryParam);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<table class="table user-table table-hover align-items-center">
        <tbody>';
    while ($row = $result->fetch_assoc()) {
        $client_id = $row['client_id'];
        $account_num = $row['account_num'];
        $account_name = $row['account_name'];
        $account_type = $row['account_type'];

        $accountTypeValue = '';
        switch ($account_type) {
            case '1':
                $accountTypeValue = 'Residential';
                break;
            case '2':
                $accountTypeValue = 'Semi-commercial';
                break;
            case '3':
                $accountTypeValue = 'Commercial';
                break;
        }

        echo "<tr class='search-result' data-client-id='$client_id' data-account-num='$account_num' data-account-name='$account_name' data-account-type='$accountTypeValue'>
            <td>$account_num</td>
            <td>$account_name</td>
            <td>$accountTypeValue</td>
        </tr>";
    }
    echo '</tbody></table>';
} else {
    echo "<h6>No results found.</h6>";
}

$stmt->close();
$conn->close();
?>
