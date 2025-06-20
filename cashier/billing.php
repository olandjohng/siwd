<?php
include('../middleware/cashierMiddleware.php');
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
        <!-- <a href="add-billing.php" type="button" class="btn btn-block btn-gray-800 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New
        </a> -->
    </div>
</div>

<div class="card">
    <div class="table-responsive py-4">
        <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="zoneFilter" class="form-label">Filter by Zone:</label>
                    <select id="zoneFilter" class="form-select">
                        <option value="">All Zones</option>
                        <option value="1A">1A</option>
                        <option value="1B">1B</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4A">4A</option>
                        <option value="4B">4B</option>
                        <option value="5">5</option>
                        <option value="6A">6A</option>
                        <option value="6B">6B</option>
                        <option value="7A">7A</option>
                        <option value="7B">7B</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Filter by Status:</label>
                    <select id="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                        <option value="Due">Due</option>
                        <option value="Rolled Over">Rolled Over</option>
                        <option value="Partially Paid">Partially Paid</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button id="clearFilters" class="btn btn-outline-primary w-100">Clear Filters</button>
                </div>
            </div>

            


            <div class="dataTable-container">   
                <table id="billingTable" class="table table-hover align-items-center">
                    <thead>
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
                                
                                $current_date = date('Y-m-d');

                                foreach ($billing as $row) {

                                    $name = $row['account_name'];
                                    $billing_num = $row['billing_num'];
                                    $reading_date = date('M d, Y', strtotime($row['reading_date']));
                                    $due_date = date('Y-m-d', strtotime($row['due_date']));
                                    $r_balance = (float)$row['r_balance'];
                                    $zone = $row['zone']; 
                                    $total = $row['discounted_total'];

                                    $current_status = $row['billing_status'];

                                    if ($due_date < $current_date && $current_status !== 'Paid' && $current_status !== 'Rolled Over') {
                                        $status = 'Due';
                                        $id = $row['billing_id'];
                                        $update_billing_query = "UPDATE billing SET status = '$status' WHERE billing_id = '$id'";
                                        mysqli_query($conn, $update_billing_query);
                                    } else {
                                        $status = $current_status;
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
                                        <!-- <a class="dropdown-item d-flex align-items-center" href="update-billing.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 11-2 0 1 1 12 0zm0-7a1 1 10-2 0v3a1 1 102 0V7z" clip-rule="evenodd"></path>
                                            </svg>
                                            Edit Billing
                                        </a> -->
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
                                <!-- <button class="btn btn-link text-dark" type="button" name="delete_billing_btn" onclick="confirmDelete(<?= $id; ?>)">
                                    <svg class="icon icon-xs text-danger ms-1" title="" data-bs-toggle="tooltip" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-bs-original-title="Delete" aria-label="Delete">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </button> -->
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

<script>
$(document).ready(function () {
    var table = $('#billingTable').DataTable({
        order: [[0, 'desc']]
    });

   
    $('#zoneFilter').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(2).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    
    $('#statusFilter').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(7).search(val ? '^' + val + '$' : '', true, false).draw();
    });

    
    $('#clearFilters').on('click', function () {
        $('#zoneFilter').val('');
        $('#statusFilter').val('');
        table.column(2).search('').column(7).search('').draw();
    });
});


</script>
