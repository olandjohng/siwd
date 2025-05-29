<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

// ALTER TABLE siwd.payments ADD tax DECIMAL(10,2) DEFAULT 0 NULL;
?>
<style>
    #receiptContent {
        display: none;
    }
        @page {
            size: 10cm 20cm; 
            margin: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #receiptContent, #receiptContent * {
                visibility: visible;
            }
            #receiptContent {
                display: block;
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 10cm;
                height: 20cm;
                padding: 20px; /* Adjust as needed */
                box-sizing: border-box;
            }
            .text-center {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: none;
                padding: 5px;
                text-align: left;
            }
            td {
                height: 0.5cm;
                font-size: 0.3cm;
            }
            .text-end {
                text-align: end;
            }
        }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="../admin/payments.php">Transactions</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Payment Details</li>
            </ol>
        </nav>
    </div>
</div>

<?php
if (isset($_GET['id']) && isset($_GET['source'])) {
    $payment_id = $_GET['id'];
    $source = $_GET['source'];

    $paymentId = getPaymentId($payment_id, $source);
    // echo "<pre>";
    // print_r($paymentId);
    // echo "</pre>";

    if ($paymentId) {
?>
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="fs-5 fw-bold mb-0">OR #<?= htmlspecialchars($paymentId['or_num']); ?></h2>
            </div>
            <div class="col text-end">
            <button class="btn btn-sm btn-primary" id="printButton">
                Print
            </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="mb-3">Payment Details:</h6>
                <div><strong><?= htmlspecialchars($paymentId['account_name']); ?></strong></div>
                <div><?= htmlspecialchars($paymentId['address'] . ', Brgy. ' . $paymentId['barangay']); ?></div>
                <div>San Isidro, Davao Oriental</div>
                <?php if ($source === 'payment') { ?>
                    <div>Discount Type: <?= htmlspecialchars($paymentId['discount_type']); ?> </div>
                <?php } elseif ($source === 'other_payment') { ?>
                    <div></div>
                <?php } ?>
                <div>Payment Date: <?= htmlspecialchars(date('d M Y', strtotime($paymentId['payment_date']))); ?></div>
                <?php if ($source === 'payment') { ?>
                    <div>Payment Method: <?= htmlspecialchars($source === 'payment' ? $paymentId['payment_method'] : 'N/A'); ?></div>
                <?php } else if ($source === 'other_payment') { ?>
                    <div>Payment purpose: <?= htmlspecialchars($paymentId['payment_purpose']); ?> </div>
                <?php } ?>
            </div>

            <?php if ($source === 'payment') { ?>
            <div class="col-sm-6">
                <h6 class="mb-3">Billing Details:</h6>
                <div>Previous Reading: <?= htmlspecialchars($paymentId['previous_reading']); ?></div>
                <div>Present Reading: <?= htmlspecialchars($paymentId['present_reading']); ?></div>
                <div>Consumption: <?= htmlspecialchars($paymentId['consumption']); ?>&#13221</div>
                <div>Covered From: <?= htmlspecialchars(date('d M Y', strtotime($paymentId['covered_from']))); ?></div>
                <div>Covered To: <?= htmlspecialchars(date('d M Y', strtotime($paymentId['covered_to']))); ?></div>
            </div>
            <?php } ?>
        </div>

        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($source === 'payment') { ?>
                    <tr>
                        <td class="left strong">Bill Amount</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['bill_amount']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Arrears</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['arrears']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Surcharge</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['surcharge']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Water Quality Improvement</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['water_qty_improvement']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Water Management</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['water_management']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Materials Fee</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['materials_fee']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Installation Fee</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['installation_fee']); ?></td>
                    </tr>
                    <?php } else if ($source === 'other_payment') { ?>
                    <tr>
                        <td class="left strong">Amount Due</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['amount_due']); ?></td>
                    </tr>
                    <!-- <tr>
                        <td class="left strong">Amount Received</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['amount_received']); ?></td>
                    </tr>
                    <tr>
                        <td class="left strong">Change Amount</td>
                        <td class="right">₱ <?= htmlspecialchars($paymentId['change_amount']); ?></td>
                    </tr> -->
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($source === 'payment') { ?>
        <div class="row">
            <div class="col-lg-4 col-sm-5">
            </div>

            <div class="col-lg-4 col-sm-5 ml-auto">
                <table class="table table-clear">
                    <tbody>
                        <tr>
                            <td class="left">
                                <strong>Subtotal</strong>
                            </td>
                            <td class="right text-end">₱ <?= htmlspecialchars($paymentId['subtotal']); ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <strong>Discount</strong>
                            </td>
                            <td class="right text-end">- ₱ <?= htmlspecialchars($paymentId['discount_amount']); ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <strong>Tax (2%)</strong>
                            </td>
                            <td class="right text-end">₱ <?= htmlspecialchars($paymentId['tax']); ?></td>
                        </tr>
                        <tr>
                            <td class="left">
                                <strong>Total</strong>
                            </td>
                            <td class="right text-end">
                                <strong>₱ <?= htmlspecialchars($paymentId['subtotal'] + $paymentId['tax']); ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php } ?>

    </div>
</div>

<div id="receiptContent">
    <table>
        <?php if ($source === 'payment') { ?>
            <tr>
                <td></td>
                <td class="fw-bolder" colspan="2"><?= htmlspecialchars($paymentId['account_name']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Current</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['bill_amount']); ?></td>
            </tr>
            <tr>
                <td colspan="2">WQI Fee:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['water_qty_improvement']); ?></td>
            </tr>
            <tr>
                <td colspan="2">WM Fee:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['water_management']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Arrears:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['arrears']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Surcharge:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['surcharge']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Installation Fee:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['installation_fee']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Materials Fee:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['materials_fee']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Tax:</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['tax']); ?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-end fw-bolder"><?= htmlspecialchars($paymentId['subtotal'] + $paymentId['tax']); ?></td>
            </tr>
        <?php } else if ($source === 'other_payment') { ?>
            <tr>
                <td></td>
                <td class="fw-bolder" colspan="2"><?= htmlspecialchars($paymentId['account_name']); ?></td>
            </tr>
            <tr>
                <td colspan="2">Amount Due</td>
                <td class="text-end"><?= htmlspecialchars($paymentId['amount_due']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php
    } else {
        echo "Payment details not found for the given ID.";
    }
} else {
    echo "ID or source missing from URL.";
}
?>

<script>
    $(document).ready(function() {
        $('#printButton').on('click', function(e) {
            e.preventDefault();
            window.print();
        });
    });
</script>
<?php include('includes/footer.php'); ?>
