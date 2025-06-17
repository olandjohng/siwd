<?php
include('../middleware/cashierMiddleware.php');
include('includes/header.php');

// if (isset($_GET['id'])) {
//     $client_id = $_GET['id'];

//     $query = "SELECT * FROM clients WHERE client_id = $client_id";
//     $result = mysqli_query($conn, $query);

//     if ($result && mysqli_num_rows($result) > 0) {
//         $client_data = mysqli_fetch_assoc($result);
//     } else {
//         echo "Client not found";
//         exit();
//     }
// } else {
//     echo "Client ID not provided";
//     exit();
// }

if (!isset($_GET['id'])) {
    echo "Client ID not provided";
    exit();
}

$client_id = $_GET['id'];

$query = "SELECT * FROM clients WHERE client_id = $client_id";
$result = mysqli_query($conn, $query);

if(!$result || mysqli_num_rows($result) === 0) {
    echo "Client not found";
    exit();
}

$client_data = mysqli_fetch_assoc($result);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="../admin/clients.php">Clients List</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Client Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow border-0 text-center p-0">
        <div class="profile-cover rounded-top" data-background="../../assets/img/profile-bg.jpg" style="background: url('../admin/assets/img/profile-bg.jpg');"></div>
            <div class="card-body pb-5">
                <img id="avatarImg" class="avatar-xl rounded-circle mx-auto mt-n7 mb-4">
            </div>
            <h4 class="h3"><?= $client_data['account_name']; ?></h4>
            <h5 class="fw-bold"><?= $client_data['account_num'] ?></h5>
            <h5 class="fw-normal">
                <?php
                    switch ($client_data['account_type']) {
                        case 1:
                            echo 'Residential';
                            break;
                        case 2:
                            echo 'Semi-Commercial';
                            break;
                        case 3:
                            echo 'Commercial';
                            break;
                        case 4:
                            echo 'Government';
                            break;
                        default:
                            echo 'Unknown';
                            break;
                    }
                ?>
            </h5>
            <p class="text-gray">
                Address: 
                <?= $client_data['address'] . ', ' . $client_data['barangay']; ?>
            </p>
            <p class="text-gray">
                Phone: 
                <?= $client_data['phone']; ?>
            </p>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">Billing History</h2>
            <div class="table-wrapper table-responsive" id="billingHistoryTable">
                <table class="table user-table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom">Date</th>
                            <th class="border-bottom">Billing #</th>
                            <th class="border-bottom">Amount Due</th>
                            <th class="border-bottom">Status</th>
                            <th class="border-bottom"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $data = getBillingHistory($client_id);

                            if ($data) {
                                foreach ($data as $billing) {
                            ?>
                        <tr>
                            <td><?= date('M d,Y', strtotime($billing['reading_date'])); ?></td>
                            <td><?= $billing['billing_num']; ?></td>
                            <td>₱ <?= $billing['discounted_total']; ?></td>
                            <td><?= $billing['status']; ?></td>
                            <td>
                                <a href="view-billing.php?id=<?= $billing['billing_id']; ?>" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                            </td>
                        </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='5'>No billing found for given client ID.</></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">Payment History</h2>
            <div class="table-wrapper table-responsive" id="paymentHistoryTable">
                <table class="table user-table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom">OR #</th>
                            <th class="border-bottom">Payment Purpose</th>
                            <th class="border-bottom">Amount</th>
                            <th class="border-bottom">Payment Date</th>
                            <th class="border-bottom"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $data = getPaymentHistory($client_id);

                            if ($data) {
                                foreach ($data as $payment) {
                                    // Determine if the payment is partial based on amount_received and amount_due
                                    if (
                                        isset($payment['payment_type']) &&
                                        $payment['payment_type'] === 'payments' &&
                                        isset($payment['amount_received'], $payment['amount_due']) &&
                                        floatval($payment['amount_received']) < floatval($payment['amount_due'])
                                    ) {
                                        $amount = htmlspecialchars($payment['amount_received']);
                                    } else {
                                        $amount = isset($payment['amount_due']) ? htmlspecialchars($payment['amount_due']) : '';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($payment['or_num']); ?></td>
                                        <td><?= htmlspecialchars($payment['payment_purpose']); ?></td>
                                        <td><?= $amount; ?></td>
                                        <td><?= date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4'>No billing found for given client ID.</td></tr>";
                            }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
    <div class="card card-body border-0 shadow mb-4">
        <h2 class="h5 mb-4">Disconnection History</h2>
        <div class="table-wrapper table-responsive" id="statusHistoryTable">
            <table class="table user-table align-items-center">
                <thead class="thead-light">
                    <tr>
                        <th class="border-bottom">Status</th>
                        <th class="border-bottom">Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $statusHistory = getStatusHistory($client_id);

                    if ($statusHistory) {
                        foreach ($statusHistory as $status) {
                            ?>
                            <tr>
                                <td><?= $status['status']; ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($status['date_updated'])); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='2'>No suspension history found.</td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<script>
    function generateAvatar(initials, size = 100, bgColor = '#111827', textColor = '#ffffff') {

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const fontSize = size / 4;

        canvas.width = size;
        canvas.height = size;

        context.fillStyle = bgColor;
        context.fillRect(0, 0, size, size);

        context.fillStyle = textColor;
        context.font = `${fontSize}px Arial`;
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.fillText(initials, size / 2, size / 2);

        return canvas.toDataURL();
    }

    function getInitials(name) {
        const words = name.split(' ');

        const initials = words.map(word => word.charAt(0).toUpperCase());

        return initials.join('');
    }

    const fullName = '<?= $client_data['account_name']?>';
    const initials = getInitials(fullName);
    const avatarDataURL = generateAvatar(initials);
    document.getElementById('avatarImg').src = avatarDataURL;
</script>


<?php include('includes/footer.php') ?>