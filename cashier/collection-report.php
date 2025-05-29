<?php
include('../middleware/cashierMiddleware.php');
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
                    <th class="border-bottom" scope="col">Method</th>
                    <th class="border-bottom" scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($payments && count($payments) > 0) {
                        foreach($payments as $row){
                            $or_num = $row['or_num'];
                            $account_num = $row['account_num'];
                            $name = $row['account_name'];
                            $payment_date = date('M d Y', strtotime($row['payment_date']));
                            $amount = $row['discounted_total'];
                            $payment_method = $row['payment_method'];
                            $arrears = $row['arrears'];
                            $surcharge = $row['surcharge'];
                            $wqi_fee = $row['wqi_fee'];
                            $wm_fee = $row['wm_fee'];
                            $installation_fee = $row['installation_fee'];
                            $materials_fee = $row['materials_fee'];
                            $tax = $row['tax'];
                            
                ?>
                <tr>
                    <td><?= $or_num; ?></td>
                    <td><?= $name; ?></td>
                    <td><?= $payment_date; ?></td>
                    <td><?= $payment_method; ?></td>
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
