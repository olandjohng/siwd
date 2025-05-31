<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>
        <h2 class="h4">Collection Report</h2>
        <p class="mb-0">Comprehensive insights and analysis on transactions, invoices and payment status.</p>
    </div>
</div>

<div class="card border-0 mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="fs-5 fw-bold mb-0">Collection Report Overview</h2>
            </div>
            <div class="col text-end">
                <a href="collection-datatable.php" class="btn btn-sm btn-primary">See full details</a>
            </div>
        </div>
    </div>
    <div class="table-responsive py-4">    
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th class="border-bottom" scope="col">OR No.</th>
                    <th class="border-bottom" scope="col">Payor</th>
                    <th class="border-bottom" scope="col">Payment Date</th>
                    <th class="border-bottom" scope="col">Payment For</th>
                    <th class="border-bottom" scope="col">Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $payments = getPayment();

                    if ($payments && count($payments) > 0) {
                        // Sort by payment_date descending (latest first)
                        usort($payments, function($a, $b) {
                            return strtotime($b['payment_date']) <=> strtotime($a['payment_date']);
                        });

                        // Limit to latest 10
                        foreach (array_slice($payments, 0, 10) as $row) {
                            $name = htmlspecialchars($row['account_name']);
                            $or_num = htmlspecialchars($row['or_num']);
                            $payment_date = htmlspecialchars(date('M d Y', strtotime($row['payment_date'])));
                            
                            // Check the source to determine which table the data came from
                            if ($row['source'] === 'payment') {
                                $id = htmlspecialchars($row['payment_id']); 
                                $payment_method = htmlspecialchars($row['payment_method']);
                                $payment_purpose = htmlspecialchars($row['payment_purpose']);
                                $amount = ($row['status'] === 'Partially Paid')
                                    ? htmlspecialchars($row['amount_received'])
                                    : htmlspecialchars($row['amount']);
                            } else if ($row['source'] === 'other_payment') {
                                $id = htmlspecialchars($row['payment_id']); 
                                $payment_method = 'N/A';
                                $payment_purpose = htmlspecialchars($row['payment_purpose']);
                                $amount = htmlspecialchars($row['amount']);
                            } else if ($row['source'] === 'refund_payment') {
                                $id = htmlspecialchars($row['payment_id']);
                                $payment_method = 'N/A';
                                $payment_purpose = htmlspecialchars($row['payment_purpose']);
                                $amount = htmlspecialchars($row['amount']);
                            }
                ?>
                <tr>
                    <td><?= $or_num; ?></td>
                    <td><?= $name; ?></td>
                    <td><?= $payment_date; ?></td>
                    <td><?= $payment_purpose; ?></td>
                    <td><?= $amount; ?></td>
                </tr>
                <?php
                        }
                    } else {
                        echo '<tr><td colspan="5">No data found.</td></tr>';
                    }
                ?>
            </tbody>

        </table>        
    </div>
</div>

<?php include('includes/footer.php') ?>
