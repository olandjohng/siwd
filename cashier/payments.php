<?php
include('../functions/functions.php');
include('includes/header.php');

$payments = getPayment();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>
        <h2 class="h4">All Payments</h2>
        <p class="mb-0">List of all payments made in San Isidro Water District.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add-payment-other.php" type="button" class="btn btn-block btn-gray-800 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive py-4">
        <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
            
            <div class="dataTable-container">
                <table class="table table-flush dataTable-table data-table" id="paymentsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>OR #</th>
                            <th>Payment By</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Payment For</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $payments = getPayment();

                            if ($payments && count($payments) > 0) {
                                foreach ($payments as $row) {
                                    $name = htmlspecialchars($row['account_name']);
                                    $or_num = htmlspecialchars($row['or_num']);
                                    $payment_date = htmlspecialchars(date('M d Y', strtotime($row['payment_date'])));
                                    
                                    // Check the source to determine which table the data came from
                                    if ($row['source'] === 'payment') {
                                        $id = htmlspecialchars($row['payment_id']); 
                                        $payment_method = htmlspecialchars($row['payment_method']); // Example field from payments table
                                        $payment_purpose = htmlspecialchars($row['payment_purpose']); // Example field from payments table
                                        $amount = htmlspecialchars($row['amount']); // Example field from payments table
                                    } else if ($row['source'] === 'other_payment') {
                                        $id = htmlspecialchars($row['payment_id']); 
                                        $payment_method = 'N/A'; // Assuming other_payments does not have payment_method
                                        $payment_purpose = htmlspecialchars($row['payment_purpose']); // Example field from other_payments table
                                        $amount = htmlspecialchars($row['amount']); // Assuming amount_received is used in other_payments
                                    }
                        ?>
                        <tr>
                            <td><?= $or_num; ?></td>
                            <td><?= $name; ?></td>
                            <td><?= $payment_date; ?></td>
                            <td><?= $payment_method; ?></td>
                            <td><?= $payment_purpose; ?></td>
                            <td>₱ <?= $amount; ?></td>
                            <td>
                                <a href="view-payments.php?id=<?= $id; ?>&source=<?= $row['source']; ?>">
                                    <svg class="icon icon-xs text-gray-400 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <!-- <button class="btn btn-link text-dark" type="button" name="delete_payment_btn" onclick="confirmDelete(<?= $id; ?>)">
                                    <svg class="icon icon-xs text-danger ms-1" title="" data-bs-toggle="tooltip" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-bs-original-title="Delete" aria-label="Delete">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </button> -->
                            </td>
                        </tr>
                        <?php
                            }
                            } else {
                                echo '<tr><td colspan="6">No payment data found.</td></tr>';
                            }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(paymentId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not able to recover the payment details.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deletePayment(paymentId);
            }
        });
    }

    function deletePayment(paymentId) {
        $.ajax({
            url: 'code.php',
            type: 'POST',
            data: { payment_id: paymentId },
            success: function(response) {
                if (response === '200') {
                    swal ("Success", "Payment deleted successfully", "success");
                    $("#paymentsTable").load(location.href + " #paymentsTable");
                } else if (response === '500') {
                    swal ("Error", "Failed to delete billing", "error");
                }
            },
            error: function() {
                swal("Error", "Something went wrong", "error");
            }
        })
    }
</script>
<?php include('includes/footer.php') ?>