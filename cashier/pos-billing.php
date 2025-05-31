<?php
include('../middleware/cashierMiddleware.php');
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/pos.css">
</head>
<body>
    <div class="container">
        <?php
            if(isset($_GET['id'])) {
                $billing_id = $_GET['id'];

                $billing = getBillingId($billing_id);

                if($billing) {
        ?>
        <div class="receipt_header">
            <h1>San Isidro Water District</h1>
            <h2>Address: Gov Ave. Batobato, Poblacion
                <span>Tel: +63990933455</span>
            </h2>
            <br>
            <h1>Billing #<?= $billing['billing_num']; ?></h1>
            <h2>Account Name: <?= $billing['account_name']; ?></h2>
            <h2>Account Number: <?= $billing['account_num']; ?></h2>
            <h2>Address: <?= $billing['address'] . ', Brgy. ' . $billing['barangay']; ?></h2>
        </div>
        <div class="receipt_body">
            <div class="date_time_con">
                <span>Covered From: <?= date('m/d/Y', strtotime($billing['covered_from'])); ?></span>
                <span>Covered To: <?= date('m/d/Y', strtotime($billing['covered_to'])); ?></span>
            </div>
            <br>
            <div class="date_time_con">
                <span>Reading Date: <?= date('m/d/Y', strtotime($billing['reading_date'])); ?></span>
                <span>Due Date: <?= date('m/d/Y', strtotime($billing['due_date'])); ?></span>
            </div>
            <br>
            <div class="date_time_con">
                <span>Previous: <?= $billing['previous_reading']; ?></span>
                <span>Present: <?= $billing['present_reading']; ?></span>
                <span>Consumption: <?= $billing['consumption']; ?>&#13221</span>
            </div>
            <div class="items">
                <table>
                    <thead>
                        <th>DESC</th>
                        <th>AMT</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Billing Amount</td>
                            <td><?= $billing['billing_amount']; ?></td>
                        </tr>
                        <tr>
                            <td>Arrears</td>
                            <td><?= $billing['arrears']; ?></td>
                        </tr>
                        <tr>
                            <td>Surcharge</td>
                            <td><?= $billing['surcharge']; ?></td>
                        </tr>
                        <tr>
                            <td>WQI Fee</td>
                            <td><?= $billing['wqi_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>WM Fee</td>
                            <td><?= $billing['wm_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>Materials Fee</td>
                            <td><?= $billing['materials_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>Installation</td>
                            <td><?= $billing['installation_fee']; ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Subtotal</td>
                            <td><?= $billing['subtotal']; ?></td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td><?= $billing['tax']; ?></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td><?= $billing['total']; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <h6>
                <small>
                    <strong>Note:</strong>
                    <span>A penalty charge is added to the bills paid after due date. Service may
                    be discontinued without further notice if payment of bill is not made to the office
                    bill collector by due date. If payment is made by
                    checks please make the check payable to the Water District. Returned
                    Checks that are not made good is cash by due date may likewise result in
                    discontinuance of services.</span>
                </small>
            </h6>
        </div>
        <?php
                } else {
                    echo "Billing details not found on given URL";
                }
            } else {
                echo "ID missing from URL";
            }
        ?>
    </div>    
</body>
</html>