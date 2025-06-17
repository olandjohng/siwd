<?php
include('../middleware/cashierMiddleware.php');
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/pos.css">
</head>
<body onload="window.print();">
    <div class="container">
        <?php
            if(isset($_GET['id'])) {
                $payment_id = $_GET['id'];

                $paymentId = getPaymentId($payment_id);

                if($paymentId) {
        ?>
        <div class="receipt_header">
            <h1>San Isidro Water District
            </h1>
            <h2>Address: Gov Ave. Batobato, Poblacion
                <span>Tel: +63990933455</span>
            </h2>
            <br>
            <h1>OR #<?= $paymentId['or_num']; ?></h1>
            <h2>Billing No: <?= $paymentId['billing_num']; ?></h2>
            <h2>Account Name: <?= $paymentId['account_name']; ?></h2>
            <h2>Account Number: <?= $paymentId['account_num']; ?></h2>
            <h2>Discount Type: <?= $paymentId['discount_type']; ?></h2>
            <h2>Payment Method: <?= $paymentId['payment_method']; ?></h2>
        </div>
        <div class="receipt_body">
            <div class="date_time_con">
                <div class="date">Payment Date: <?= date('m/d/Y', strtotime($paymentId['payment_date'])) ?></div>
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
                            <td><?= $paymentId['billing_amount']; ?></td>
                        </tr>
                        <tr>
                            <td>Arrears</td>
                            <td><?= $paymentId['arrears']; ?></td>
                        </tr>
                        <tr>
                            <td>Surcharge</td>
                            <td><?= $paymentId['surcharge']; ?></td>
                        </tr>
                        <tr>
                            <td>WQI Fee</td>
                            <td><?= $paymentId['wqi_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>WM Fee</td>
                            <td><?= $paymentId['wm_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>Materials Fee</td>
                            <td><?= $paymentId['materials_fee']; ?></td>
                        </tr>
                        <tr>
                            <td>Installation</td>
                            <td><?= $paymentId['installation_fee']; ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Subtotal</td>
                            <td><?= $paymentId['subtotal']; ?></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>- <?= $paymentId['discount_amount']; ?></td>
                        </tr>
                        <tr>
                            <td>Tax (2%)</td>
                            <td><?= $paymentId['tax']; ?></td>
                        </tr>
                        <tr>
                            <td><span style="font-weight: bold; font-size: 20px;">Total</span></td>
                            <td><span style="font-weight: bold; font-size: 20px;"><?= $paymentId['discounted_total']; ?></span></td>
                        </tr>
                        <tr>
                            <td>Amount Received</td>
                            <td><?= $paymentId['amount_received']; ?></td>
                        </tr>
                        <tr>
                            <td>Change</td>
                            <td><?= $paymentId['change_amount']; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <h3>
                Have a nice day! Thank you.
            </h3>
        </div>
        <?php
                } else {
                    echo "Payment details not found on given URL";
                }
            } else {
                echo "ID missing from URL";
            }
        ?>
    </div>    
</body>
</html>