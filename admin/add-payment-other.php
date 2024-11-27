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
                    <a href="../admin/payments.php">Transactions</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Other Payment</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card px-3">
            <div class="row">
                <div class="col-lg-9 card-body">
                    <h4 class="mb-2">Add Payment</h4>
                    
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


                    <form action="code.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-9 mb-3">
                                <div>
                                    <label for="account_name">Account Name</label>
                                    <input type="text" class="form-control" name="account_name" id="account_name">
                                    <input type="hidden" id="client_id" name="client_id">
                                </div>
                                <div id="search-results"></div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="account_num">Acct No.</label>
                                <input type="text" class="form-control" name="account_num" id="account_num">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="payment_method">Payment Purpose</label>
                                <select name="payment_purpose" id="payment_purpose" class="form-select">
                                    <option value="Installation">Installation</option>
                                    <option value="Reconnection">Reconnection</option>
                                    <option value="Transfer Meter">Transfer Meter</option>
                                    <option value="Change Name">Change Name</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row g-3">
                            <div class="col-md-6 mb-4">
                                <label for="or_num">Receipt No.</label>
                                <input type="text" class="form-control" name="or_num" id="or_num" value="<?php echo $receipt_number; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label for="amount_due">Total Amount Due</label>
                                <input type="number" class="form-control" name="amount_due" id="amount_due">
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
                                <button type="button" class="btn btn-primary" id="previewButton" data-bs-toggle="modal" data-bs-target="#paymentPreviewModal">
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
                                                    <th>Payment Purpose</th>
                                                    <td id="paymentPurposePreview"></td>
                                                </tr>  
                                                <tr>
                                                    <th>Total Amount Due:</th>
                                                    <td id="totalAmountDuePreview"></td>
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
                                        <button type="submit" class="btn btn-primary" name="add_otherpayment_btn">Submit Payment</button>
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

    <script>
    $(document).ready(function(){
        $('#account_name').on('input', function(){
            var searchQuery = $(this).val();
            
            if(searchQuery.trim() !== '') {
                $.ajax({
                    url: 'search-2.php', 
                    method: 'GET',
                    data: { query: searchQuery },
                    success: function(response){
                        $('#search-results').html(response);
                    },
                    error: function(xhr, status, error){
                        console.error('Error:', error);
                    }
                });
            } else {
                $('#search-results').html('');
            }
        });

        $(document).on('click', '.search-result', function(){
            var clientId = $(this).data('client-id');
            var accountNum = $(this).data('account-num');
            var accountName = $(this).data('account-name');
            
            $('#account_name').val(accountName);
            $('#account_num').val(accountNum);
            $('#client_id').val(clientId);
            $('#search-results').html('');
        });
    });
</script>


<script>
    $(document).ready(function() {
        function calculateChange() {
            var amountDue = parseFloat($('#amount_due').val());
            var paymentAmount = parseFloat($('#payment_amount').val());
            var change = paymentAmount - amountDue;
            return change.toFixed(2);
        }

        $('#payment_amount').on('input', function() {
            var amountDue = parseFloat($('#amount_due').val());
            var paymentAmount = parseFloat($('#payment_amount').val());
            change = calculateChange();

            if (paymentAmount < amountDue) {
                $('#payment_amount').addClass('is-invalid');
            } else {
                $('#payment_amount').removeClass('is-invalid');
                $('#change').val(change);
            }
        });
    })
</script>

<script>
    $(document).ready(function() {
        $('#previewButton').click(function() {
            
            $('#paymentPurposePreview').text($('#payment_purpose option:selected').val()); //sample for dropdown
            $('#totalAmountDuePreview').text($('#amount_due').val());
            $('#paymentAmountPreview').text($('#payment_amount').val());
            $('#changePreview').text($('#change').val());
            $('#paymentNotePreview').text($('#payment_note').val());
        });
    });
</script>




<?php include('includes/footer.php') ?>