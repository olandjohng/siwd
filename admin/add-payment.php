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
                <li class="breadcrumb-item">
                    <a href="../admin/billing.php">Billing</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Payment</li>
            </ol>
        </nav>
    </div>
</div>

<?php
    if(isset($_GET['id'])) {
        $billing_id = $_GET['id'];

        $billing = getBillingId($billing_id);
        echo '<pre>';
        print_r($billing);
        echo '</pre>';
        $partial_paid_amount = (int) getPartiaPaid($billing_id);

        $get_partial_list = getPartialPaidList($billing_id); 
        
        $isDue = new DateTime($billing['due_date']) < new DateTime();

        if($billing) { 
?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card px-3">
            <div class="row">
                <div class="col-lg-9 card-body">
                    <h4 class="mb-4">Add Payment</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label for="account_name">Account Name</label>
                                <input type="text" class="form-control" name="account_name" id="account_name" value="<?= $billing['account_name']; ?>" readonly>
                            </div>
                            <div class="col-12 mb-4">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-select">
                                    <option value="Cash">Cash</option>
                                        <?php if(!$isDue) : ?>
                                            <option value="Partial">Partial</option>
                                        <?php endif ?>
                                    <option value="Check">Bank Check</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <hr>
                    
                    <h4 class="mt-4 pt-2">Payment Details</h4>

                    <?php
                        $latest_payment_query = "SELECT or_num FROM payments ORDER BY or_num DESC LIMIT 1";
                        $latest_payment_result = mysqli_query($conn, $latest_payment_query);
                        $latest_payment_number = 0;

                        if (mysqli_num_rows($latest_payment_result) > 0) {
                            $row = mysqli_fetch_array($latest_payment_result);
                            $latest_payment_number = (int) $row['or_num'];
                        }

                        $latest_other_payment_query = "SELECT or_num FROM other_payments ORDER BY or_num DESC LIMIT 1";
                        $latest_other_payment_result = mysqli_query($conn, $latest_other_payment_query);
                        $latest_other_payment_number = 0;

                        if (mysqli_num_rows($latest_other_payment_result) > 0) {
                            $row = mysqli_fetch_array($latest_other_payment_result);
                            $latest_other_payment_number = (int) $row['or_num'];
                        }

                        $latest_refund_payment_query = "SELECT or_num FROM refund_payments ORDER BY or_num DESC LIMIT 1";
                        $latest_refund_payment_result = mysqli_query($conn, $latest_refund_payment_query);
                        $latest_refund_payment_number = 0;

                        if (mysqli_num_rows($latest_refund_payment_result) > 0) {
                            $row = mysqli_fetch_array($latest_refund_payment_result);
                            $latest_refund_payment_number = (int) $row['or_num'];
                        }

                        $last_receipt_number = max($latest_payment_number, $latest_other_payment_number, $latest_refund_payment_number);

                        $new_number = str_pad($last_receipt_number + 1, 6, '0', STR_PAD_LEFT);
                        
                        $receipt_number = $new_number;
                    ?>


                    <!--Cash-->
                    <div class="form-group">
                        <form action="code.php" method="POST" id="fullPaymentForm" style="display: block;">
                            <div class="row g-3">
                                <div class="col-md-6 mb-4">
                                    <label for="or_num">Receipt No.</label>
                                    <input type="text" class="form-control" name="or_num" id="or_num" value="<?php echo $receipt_number; ?>" readonly>
                                    <input type="hidden" name="payment_method" id="payment_method" value="Cash">
                                    <input type="hidden" name="payment_purpose" id="payment_purpose" value="Bills Payment">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date">Payment Date</label>
                                    <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label for="total_amount_due">Total Amount Due</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="total_amount_due" id="total_amount_due" value="<?= $billing['discounted_total']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none;">
                                    <label for="billing_id">Billing ID</label>
                                    <input type="text" class="form-control" name="billing_id" id="billing_id" value="<?= $billing['billing_id']; ?>" readonly>
                                </div>
                                <div class="col-md-3" style="display: none;">
                                    <label for="client_id">Client ID</label>
                                    <input type="text" class="form-control" name="client_id" id="client_id" value="<?= $billing['client_id']; ?>" readonly>
                                </div>
                                <div class="col-md-3" style="display: none;">
                                    <label for="billing_num">Billing #</label>
                                    <input type="text" class="form-control" name="billing_num" id="billing_num" value="<?= $billing['billing_num']; ?>" readonly>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="payment_amount">Payment Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="payment_amount" id="payment_amount" inputmode="decimal" step="0.01" min="0" pattern="^\d+(?:\.\d{0,2})?$" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="change">Change</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="change" id="change" inputmode="decimal" step="0.01" min="0" pattern="^\d+(?:\.\d{0,2})?$" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="payment_note">Payment Note</label>
                                    <textarea name="payment_note" id="payment_note" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" id="previewCashButton" data-bs-toggle="modal" data-bs-target="#paymentPreviewModal">
                                        Proceed
                                    </button>
                                </div>
                            </div>

                            <!--Preview modal-->
                            <div class="modal fade" id="paymentPreviewModal" tabindex="-1" aria-labelledby="paymentPreviewModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentPreviewModalLabel">Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Total Amount Due:</th>
                                                        <td id="totalAmountPreview"></td>
                                                    </tr> 
                                                    <tr>
                                                        <th>Payment Amount:</th>
                                                        <td id="paymentAmountPreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Amount Change:</th>
                                                        <td id="changePreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Payment Notes:</th>
                                                        <td id="paymentNotePreview"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="add_cashpayment_btn">Submit Payment</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!--Partial-->
                    <div class="form-group">
                        <input type="hidden" value="<?php ?>"/>
                        <form action="code.php" method="POST" id="partialPaymentForm" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6 mb-4">
                                    <label for="or_num">Receipt No.</label>
                                    <input type="text" class="form-control" name="or_num" id="or_num" value="<?php echo $receipt_number; ?>" readonly>
                                    <input type="hidden" name="payment_method" id="payment_method" value="Partial">
                                    <input type="hidden" name="payment_purpose" id="payment_purpose" value="Bills Payment">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date">Payment Date</label>
                                    <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                    <label for="total_amount_due">Total Amount Due</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="total_amount_due2" id="total_amount_due2" value="<?= ( (int)$billing['discounted_total'] - $partial_paid_amount); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none;">
                                    <label for="billing_id">Billing ID</label>
                                    <input type="text" class="form-control" name="billing_id" id="billing_id" value="<?= $billing['billing_id']; ?>" readonly>
                                </div>
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label for="partial_amount">Partial Payment Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="partial_amount" id="partial_amount" inputmode="decimal" step="0.01" min="0" pattern="^\d+(?:\.\d{0,2})?$" required>
                                        <button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#partial-payment-list-modal">
                                            <svg class="w-[17px] h-[17px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="remaining_balance">Remaining Balance</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="remaining_balance" id="remaining_balance" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="note">Payment Note</label>
                                    <textarea name="payment_note2" id="payment_note2" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" id="previewPartialButton" data-bs-toggle="modal" data-bs-target="#partialPaymentPreviewModal">Proceed</button>
                                </div>
                            </div>
                            <!--Preview Modal -->
                            <div class="modal fade" id="partialPaymentPreviewModal" tabindex="-1" aria-labelledby="partialPaymentPreviewModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="partialPaymentPreviewModalLabel">Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Total Amount Due:</th>
                                                        <td id="totalAmountPreviewB"></td>
                                                    </tr> 
                                                    <tr>
                                                        <th>Partial Payment Amount:</th>
                                                        <td id="partialPaymentAmountPreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Remaining Balance:</th>
                                                        <td id="remainingBalancePreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Payment Notes:</th>
                                                        <td id="paymentNotePreviewB"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="add_partial_btn">Submit Payment</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Partial Payment List Modal -->
                            <div class="modal fade" id="partial-payment-list-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Payment History</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-striped table-sm">
                                                <?php if(!$get_partial_list) { ?>
                                                    <thead>
                                                        <th>No Available Partial Payment</th>
                                                    </thead>
                                                <?php } else { ?>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Payment Amount</th>
                                                            <th scope="col">Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($get_partial_list as $partial) { ?>
                                                            <tr>
                                                                <td><?= $partial['amount_received']; ?></td>
                                                                <td><?= $partial['payment_date']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    
                                                <?php } ?>

                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!--Bank Check-->
                    <div class="form-group">
                        <form action="code.php" method="POST" id="bankCheckForm" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6 mb-4">
                                    <label for="or_num">Receipt No.</label>
                                    <input type="text" class="form-control" name="or_num" id="or_num" value="<?php echo $receipt_number; ?>" readonly>
                                    <input type="hidden" name="payment_method" id="payment_method" value="Bank Check">
                                    <input type="hidden" name="payment_purpose" id="payment_purpose" value="Bills Payment">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date">Payment Date</label>
                                    <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                    <label for="total_amount_due3">Total Amount Due</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="total_amount_due3" id="total_amount_due3" value="<?= $billing['discounted_total']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3" style="display: none;">
                                    <label for="billing_id">Billing ID</label>
                                    <input type="text" class="form-control" name="billing_id" id="billing_id" value="<?= $billing['billing_id']; ?>" readonly>
                                </div>
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">             
                                    <div class="mb-3">
                                        <label for="check_number">Check Number</label>
                                        <input type="text" id="check_number" name="check_number" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank_name">Bank Name</label>
                                        <input type="text" id="bank_name" name="bank_name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="check_date">Check Date</label>
                                        <input type="date" id="check_date" name="check_date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="check_amount">Check Amount</label>
                                        <input type="number" id="check_amount" name="check_amount" class="form-control" inputmode="decimal" step="0.01" min="0" pattern="^\d+(?:\.\d{0,2})?$" required>
                                    </div>  
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="note">Payment Note</label>
                                    <textarea name="payment_note3" id="payment_note3" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" id="previewBankCheckButton" data-bs-toggle="modal" data-bs-target="#bankCheckPreviewModal">Proceed</button>
                                </div>
                            </div>

                            <div class="modal fade" id="bankCheckPreviewModal" tabindex="-1" aria-labelledby="bankCheckPreviewModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bankCheckPreviewModalLabel">Preview</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Total Amount Due:</th>
                                                        <td id="totalAmountPreviewC"></td>
                                                    </tr> 
                                                    <tr>
                                                        <th>Bank Name:</th>
                                                        <td id="bankNamePreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Check Number:</th>
                                                        <td id="checkNumberPreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Check Date:</th>
                                                        <td id="checkDatePreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Check Amount:</th>
                                                        <td id="checkAmountPreview"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Note:</th>
                                                        <td id="paymentNotePreviewC"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="add_bankcheck_btn">Submit Payment</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <?php
        } else {
            echo "Billing not found for given ID.";
        }
    } else {
        echo "ID missing from URL";
    }
?>

<script>
    $(document).ready(function() {
        function calculateChange() {
            var totalAmountDue = parseFloat($('#total_amount_due').val());
            var paymentAmount = parseFloat($('#payment_amount').val());
            var change = paymentAmount - totalAmountDue;
            return change.toFixed(2);
        }

        function calculateRemainingBalance() {
            var totalAmountDue = parseFloat($('#total_amount_due2').val());
            var partialAmount = parseFloat($('#partial_amount').val());
            var remainingBalance = totalAmountDue - partialAmount;
            return remainingBalance.toFixed(2);
        }

        $('#payment_amount').on('input', function() {
            var totalAmountDue = parseFloat($('#total_amount_due').val());
            var paymentAmount = parseFloat($('#payment_amount').val());
            var change = calculateChange();

            if (paymentAmount < totalAmountDue) {
                $('#payment_amount').addClass('is-invalid');
            } else {
                $('#payment_amount').removeClass('is-invalid');
                $('#change').val(change);
            }
            
        });

        $('#partial_amount').on('input', function() {
            var remainingBalance = calculateRemainingBalance();
            var partialAmount = parseFloat($(this).val());
            var totalAmountDue = parseFloat($('#total_amount_due2').val());

            if (partialAmount <= totalAmountDue) {
                $('#partial_amount').removeClass('is-invalid');
                $('#remaining_balance').val(remainingBalance);
            } else {
                $('#partial_amount').addClass('is-invalid');
            }
        });

        $('#check_amount').on('input', function() {
            var checkAmount = parseFloat($('#check_amount').val());
            var totalAmountDue = parseFloat($('#total_amount_due3').val());

            if (checkAmount < totalAmountDue) {
                $('#check_amount').addClass('is-invalid');
            } else {
                $('#check_amount').removeClass('is-invalid');
            }
        })
    });
</script>

<script>
    $(document).ready(function() {
        $("#payment_method").change(function() {
            var selectedPaymentMethod = $(this).val();

            if (selectedPaymentMethod === "Cash") {
                $("#fullPaymentForm").show();
                $("#partialPaymentForm").hide();
                $("#bankCheckForm").hide();
            } else if (selectedPaymentMethod === "Partial") {
                $("#fullPaymentForm").hide();
                $("#partialPaymentForm").show();
                $("#bankCheckForm").hide();
            } else if (selectedPaymentMethod === "Check") {
                $("#fullPaymentForm").hide();
                $("#partialPaymentForm").hide();
                $("#bankCheckForm").show();
            }
        });
    });
</script>

<!--Preview Scripts-->
<script>
    $(document).ready(function() {
        $('#previewCashButton').click(function() {
            
            $('#totalAmountPreview').text($('#total_amount_due').val());
            $('#paymentAmountPreview').text($('#payment_amount').val());
            $('#changePreview').text($('#change').val());
            $('#paymentNotePreview').text($('#payment_note').val());
        });
    });

    $(document).ready(function() {
        $('#previewPartialButton').click(function() {

            $('#totalAmountPreviewB').text($('#total_amount_due2').val());
            $('#partialPaymentAmountPreview').text($('#partial_amount').val());
            $('#remainingBalancePreview').text($('#remaining_balance').val());
            $('#paymentNotePreviewB').text($('#payment_note2').val());
        })
    })

    $(document).ready(function() {
        $('#previewBankCheckButton').click(function() {

            $('#totalAmountPreviewC').text($('#total_amount_due3').val());
            $('#bankNamePreview').text($('#bank_name').val());
            $('#checkNumberPreview').text($('#check_number').val());
            $('#checkDatePreview').text($('#check_date').val());
            $('#checkAmountPreview').text($('#check_amount').val());
            $('#paymentNotePreviewC').text($('#payment_note3').val());
        });
    });
</script>




<?php include('includes/footer.php') ?>