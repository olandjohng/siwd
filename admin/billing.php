<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$billing = getBilling();
// print_r($billing);

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Billing</li>
            </ol>
        </nav>
        <h2 class="h4">All Bills</h2>
        <p class="mb-0">A complete listing of all San Isidro Water District bills.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add-billing.php" type="button" class="btn btn-block btn-gray-800 d-inline-flex align-items-center">
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
                <table class="table table-flush dataTable-table data-table" id="billingTable">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Bill For</th>
                            <th>Zone</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($billing && count($billing) > 0) {
                                
                                // Sort the billing array by billing number in descending order
                                usort($billing, function ($a, $b) {
                                    return $b['billing_num'] > $a['billing_num'];
                                });

                                $current_date = date('Y-m-d'); // Get the current date in 'Y-m-d' format

                                foreach ($billing as $row) {
                                    $name = $row['account_name'];
                                    $billing_num = $row['billing_num'];
                                    $reading_date = date('M d, Y', strtotime($row['reading_date']));
                                    $due_date = date('Y-m-d', strtotime($row['due_date'])); // Convert due date to 'Y-m-d' format for comparison
                                    $total = $row['discounted_total'];
                                    $r_balance = (float)$row['r_balance'];
                                    $zone = $row['zone']; 
                                    if ($due_date < $current_date && $row['status'] !== 'Paid' && $row['status'] !== 'Rolled Over') {
                                        $status = 'Due';
                                        $id = $row['billing_id'];
                                        $update_billing_query = "UPDATE billing SET status = '$status' WHERE billing_id = '$id'";
                                        mysqli_query($conn, $update_billing_query);
                                    } else {
                                        $status = $row['status'];
                                    }

                                    $due_date_display = date('M d, Y', strtotime($row['due_date'])); // Convert due date back to display format
                                    $id = $row['billing_id'];
                                
                        ?>
                        <tr>
                            <td><?= $billing_num; ?></td>
                            <td><?= $name; ?></td>
                            <td><?= $zone; ?></td>
                            <td><?= $reading_date; ?></td>
                            <td><?= $due_date_display; ?></td>
                            <td>₱ <?= $total; ?></td>
                            <td>₱ <?= $r_balance; ?></td>
                            <?php
                                $status_color = '';
                                if ($status === 'Pending') {
                                    $status_color = 'text-warning';
                                } elseif ($status === 'Paid') {
                                    $status_color = 'text-success';
                                } elseif ($status === 'Due') {
                                    $status_color = 'text-danger';
                                } elseif ($status === 'Rolled Over') {
                                    $status_color = 'text-purple';
                                }
                            ?>
                            <td><span class="fw-bold <?= $status_color; ?>"><?= $status; ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 000 4z"></path>
                                        </svg>
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                        <a class="dropdown-item d-flex align-items-center" href="update-billing.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 11-2 0 1 1 12 0zm0-7a1 1 10-2 0v3a1 1 102 0V7z" clip-rule="evenodd"></path>
                                            </svg>
                                            Edit Billing
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="view-billing.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 12a2 2 100-4 2 2 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 111-8 0 4 4 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Billing Details
                                        </a>
                                        
                                        <?php if ($status !== 'Paid' && $status !== 'Rolled Over') { ?>
                                        <a class="dropdown-item d-flex align-items-center" href="add-payment.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-success me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"></path>
                                            </svg> 
                                            Add Payment
                                        </a>
                                        <?php } else { ?>
                                        <a class="dropdown-item d-flex align-items-center disabled" href="#">
                                            <svg class="dropdown-icon me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"></path>
                                            </svg> 
                                            Add Payment
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <button class="btn btn-link text-dark" type="button" name="delete_billing_btn" onclick="confirmDelete(<?= $id; ?>)">
                                    <svg class="icon icon-xs text-danger ms-1" title="" data-bs-toggle="tooltip" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-bs-original-title="Delete" aria-label="Delete">
                                        <path fill-rule="evenodd" d="M10 18a8 8 100-16 8 8 000 16zM8.707 7.293a1 1 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 101.414 1.414L10 11.414l1.293 1.293a1 1 001.414-1.414L11.414 10l1.293-1.293a1 1 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo '<tr><td colspan="7">No billing data found.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
    function confirmDelete(billingId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not able to recover this billing.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deleteBilling(billingId);
            }
        });
    }

    function deleteBilling(billingId) {
        $.ajax({
            url: 'code.php',
            type: 'POST',
            data: { billing_id: billingId },
            success: function(response) {
                if (response === '200') {
                    swal ("Success", "Billing deleted successfully", "success");
                    $("#billingTable").load(location.href + " #billingTable");
                } else if (response === '500') {
                    swal ("Error", "Failed to delete billing", "error");
                }
            },
            error: function() {
                swal("Error", "Failed to delete billing", "error");
            }
        })
    }
</script>

<?php include('includes/footer.php') ?>