<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$billing = getBilling();
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
        <h2 class="h4">Billing Report</h2>
        <p class="mb-0">Comprehensive insights and analysis on issued bills.</p>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="fs-5 fw-bold mb-0">Billing Report Overview</h2>
            </div>
            <div class="col text-end">
                <a href="billing-datatable.php" class="btn btn-sm btn-primary">See full details</a>
            </div>
        </div>
    </div>
    <div class="table-responsive py-4">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th class="border-bottom" scope="col">Acct No.</th>
                    <th class="border-bottom" scope="col">Account Name</th>
                    <th class="border-bottom" scope="col">Billing No.</th>
                    <th class="border-bottom" scope="col">Current</th>
                    <th class="border-bottom" scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($billing && count($billing) > 0) {

                        $latest_billing = array_reverse(array_slice($billing, -10));

                        foreach($latest_billing as $row){
                            $account_num = $row['account_num'];
                            $name = $row['account_name'];
                            $billing_num = $row['billing_num'];
                            $billing_amount = $row['billing_amount'];
                            $total = $row['discounted_total'];
                ?>
                <tr>
                    <td><?= $account_num; ?></td>
                    <td><?= $name; ?></td>
                    <td><?= $billing_num; ?></td>
                    <td><?= $billing_amount; ?></td>
                    <td><?= $total; ?></td>
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

<div class="card border-0 shadow">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="fs-5 fw-bold mb-0">Monthly water consumption by zone</h2>
            </div>
            <div class="col text-end">
                <button type="button" class="btn btn-sm btn-primary">
                    See full report
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th class="border-bottom" scope="col">Zone</th>
                    <th class="border-bottom" scope="col">Service Connections</th>
                    <th class="border-bottom" scope="col">Consumption</th>
                    <th class="border-bottom" scope="col">Billing Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $consumptionData = getConsumptionAndBillingByZone();

                    foreach ($consumptionData as $zone => $data) {
                        $clients = $data['clients']; 
                        $consumption = $data['consumption'];
                        $discountedTotal = $data['discounted_total'];

                        
                        $consumption = ($consumption === '' || $consumption === null) ? 0 : $consumption;
                        
                        $discountedTotal = ($discountedTotal === '' || $discountedTotal === null) ? 0 : $discountedTotal;
                ?>
                <tr>
                    <th class="text-gray-900" scope="row">Zone <?= $zone; ?></th>
                    <td class="fw-bolder text-gray-500"><?= $clients ?></td>
                    <td class="fw-bolder text-gray-500"><?= $consumption ?> &#13221</td>
                    <td class="fw-bolder text-gray-500"><?= $discountedTotal; ?></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
