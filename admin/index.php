<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$total_clients = getTotalClients();
$total_pending = getTotalPending();
$total_consumption = getTotalConsumption();
$total_active = getTotalActiveClients();
$total_inactive = getTotalInactiveClients();
$total_todaySales = getTodaySales();
$total_monthlySales = getMonthlySales();

$users = getAll('users');
$payments = getPayment();


?>
    

<div class="py-4">
    <div class="dropdown">
        <button class="btn btn-gray-800 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
            New Task
        </button>
        <ul class="dropdown-menu" aria-labelledby="drop">
            <li><a class="dropdown-item" href="add-client.php">Add Client</a></li>
            <li><a class="dropdown-item" href="add-billing.php">Add Billing</a></li>
            <!-- <li><a class="dropdown-item" href="#">Add User</a></li> -->
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Clients</h2>
                        <h3 class="fw-extrabold mb-1"><?= $total_clients; ?></h3>
                        <small class="d-flex align-items-center">Total clients of SIDO Water District</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Unpaid</h2>
                        <h3 class="fw-extrabold mb-1">₱ <?= number_format($total_pending, 2); ?></h3>
                        <small class="d-flex align-items-center">Total unpaid bills.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Active Clients</h2>
                        <h3 class="fw-extrabold mb-1"><?= $total_active; ?></h3>
                        <small class="d-flex align-items-center">Total number of active clients.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Inactive Clients</h2>
                        <h3 class="fw-extrabold mb-1"><?= $total_inactive; ?></h3>
                        <small class="d-flex align-items-center">Total number of inactive clients.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Daily Collection</h2>
                        <h3 class="fw-extrabold mb-1">₱ <?= number_format($total_todaySales, 2); ?></h3>
                        <small class="d-flex align-items-center">Total cash in for today.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xxl-flex align-items-center">
                    <div class="col-12 col-xxl-6 ps-xxl-4 pe-xxl-0">
                        <h2 class="fs-6 fw-normal mb-1 text-gray-400">Monthly Collection</h2>
                        <h3 class="fw-extrabold mb-1">₱ <?= number_format($total_monthlySales, 2); ?></h3>
                        <small class="d-flex align-items-center">Total cash in for the month.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row justify-content-lg-center">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header border-bottom">
                <h2 class="fs-5 fw-bold mb-1">Monthly water consumption by zone</h2>
            </div>
            <div class="card-body text-center py-4 py-xl-5">
                <h3 class="display-3 fw-extrabold mb-0"><?= $total_consumption; ?> m<span>&#179; </h3>
                <p>Total Water Consumption for the month</p>
                <a href="billing-report.php" class="btn btn-primary d-inline-flex align-items-center">
                    <svg class="icon icon-xxs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                    </svg>
                    Go To Reports
                </a>
            </div>
            <div class="card-footer border-0 px-3 py-4" style="position: relative;">
                <div id="water-consumption-chart"></div>
            </div>
        </div>
    </div>
</div>

<!--Latest transactions table-->
<div class="row">
    <div class="col-12 col-md-6 col-xxl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header border-bottom">
                <h2 class="fs-5 fs-bold mb-0">
                    Team Members
                </h2>
            </div>
            <?php
                if($users && mysqli_num_rows($users) > 0) {
                    while ($row = mysqli_fetch_assoc($users)) {
                        $name = $row['firstname'] . ' ' . $row['lastname'];
                        $designation = $row['designation'];
                        $id = $row['user_id'];
            ?>
            <div class="card-body py-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent border-bottom py-3 px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <h4 class="fs-6 text-dark mb-0"><?= $name; ?></h4>
                                <span class="small"><?= $designation; ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <?php
                    }
                } else {
                    echo "No users found.";
                }
            ?>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xxl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Latest Transactions</h2>
                    </div>
                    <div class="col text-end">
                        <a href="payments.php" class="btn btn-sm btn-primary">See all</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-bottom" scope="col">#</th>
                            <th class="border-bottom" scope="col">Payment By</th>
                            <th class="border-bottom" scope="col">Payment For</th>
                            <th class="border-bottom" scope="col">Amount</th>
                            <!-- <th class="border-bottom" scope="col"></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $row_count = 0;
                            if($payments && count($payments) > 0) {
                                // $payments = array_reverse($payments);
                                foreach($payments as $row) {
                                    $name = $row['account_name'];
                                    $or_num = $row['or_num'];
                                    $id = $row['payment_id'];
                                    $purpose = $row['payment_purpose'];

                                    if ($row['status'] && $row['status'] === 'Partially Paid') {
                                        $amount = htmlspecialchars($row['amount_received']);
                                    } else {
                                        $amount = htmlspecialchars($row['amount']);
                                    }
                        ?>
                        <tr>
                            <td class="fw-bolder text-gray-500"><?= $or_num; ?></td>
                            <td class="fw-bolder text-gray-500"><?= $name; ?></td>
                            <td class="fw-bolder text-gray-500"><?= $purpose; ?></td>
                            <td class="fw-bolder text-gray-500">₱ <?= $amount; ?></td>
                            <!-- <td>
                                <a href="view-payments.php?id=<?= $id; ?>&source=<?= $row['source']; ?>">
                                    <svg class="icon icon-xs text-gray-400 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </td> -->
                        </tr>
                        <?php
                                    $row_count++;
                                    if ($row_count >= 9) {
                                    break;
                                    }
                                }
                            } else {
                                echo '<tr><td colspan="3">No payment found.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php') ?>
