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
                <li class="breadcrumb-item active" aria-current="page">Add New Bill</li>
            </ol>
        </nav>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12">
        <div class="card card-body border-0 shadow mb-4">
            <?php
                $new_billing_query = "SELECT billing_num FROM billing ORDER BY billing_num DESC";
                $new_billing_query_result = mysqli_query($conn, $new_billing_query);
                $row = mysqli_fetch_array($new_billing_query_result);
                // print_r($row);
                if(empty($row)) {
                    $number = "SI-000001";
                } else {
                    $lastid = $row['billing_num'];
                    $idd = str_replace("SI-","",$lastid);
                    $id = str_pad($idd + 1, 6, 0, STR_PAD_LEFT);
                    $number = 'SI-' .$id;
                }
            ?>
            <h2 class="h5 mb-4">Create Billing</h2>
            <form action="code.php" method="POST">
                <div class="row">
                    <div class="col-md-9 mb-3">
                        <div>
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control" id="client_search" name="client_search" placeholder="Enter account name" required>
                        </div>
                        <div id="search-results"></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="billing_num">Billing No.</label>
                            <input type="text" class="form-control" id="billing_num" name="billing_num" value="<?php echo $number; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="account_type">Account Type</label>
                            <input type="text" id="account_type" name="account_type" class="form-control" readonly>
                            <input type="hidden" id="client_id" name="client_id">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="account_num">Account Number</label>
                            <input type="text" id="account_num" name="account_num" class="form-control" readonly>
                        </div>
                    </div>             
                </div>
                <h2 class="h5 my-4">Billing Details</h2>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="covered_from">Period Covered From</label>
                            <input type="date" class="form-control" id="covered_from" name="covered_from" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="covered_to">Period Covered To</label>
                            <input type="date" class="form-control" id="covered_to" name="covered_to" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="reading_date">Reading Date</label>
                            <input type="date" class="form-control" id="reading_date" name="reading_date" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="previous">Previous Reading</label>
                            <input type="text" class="form-control" id="previous_reading" name="previous_reading" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="present">Present Reading</label>
                            <input type="text" class="form-control" id="present_reading" name="present_reading" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="consumption">Consumption (&#13221)</label>
                            <input type="text" class="form-control" id="consumption" name="consumption" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="billing_amount">Billing Amount</label>
                            <input type="text" class="form-control" id="billing_amount" name="billing_amount" readonly required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="discounted_billing">Discounted Billing</label>
                            <input type="text" class="form-control" id="discounted_billing" name="discounted_billing" readonly required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="arrears">Arrears</label>
                            <input type="text" class="form-control" id="arrears" name="arrears">
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="surcharge">Surcharge (10%)</label>
                            <input type="text" class="form-control" id="surcharge" name="surcharge">
                        </div>
                    </div>
                </div>
                
                <h2 class="h5 my-4">Additional Fees</h2>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="wqi_fee">Water Quality Improvement</label>
                            <input type="text" class="form-control" id="wqi_fee" name="wqi_fee" value="10.00" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="wm_fee">Water Management</label>
                            <input type="text" class="form-control" id="wm_fee" name="wm_fee" value="5.00" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="materials">Materials Fee</label>
                            <input type="currency" class="form-control" id="materials_fee" name="materials_fee">
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="installation">Installation Fee</label>
                            <input type="currency" class="form-control" id="installation_fee" name="installation_fee">
                        </div>
                    </div>
                </div>

                <h2 class="h5 my-4">Tax & Total</h2>
                <div class="row">
                    <input type="hidden" id="subtotal" name="subtotal">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="tax">Franchise Tax</label>
                            <input type="text" class="form-control" id="tax" name="tax" readonly required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" id="total" name="total" readonly required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3" id="status-container">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select mb-0" aria-label="Status select">
                            <option value="Pending" selected>Pending</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>

                <h2 class="h5 my-4">Apply Discount</h2>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="discount_type">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-select" aria-label="Discount type select">
                            <option value="N/A" selected>Select Discount</option>
                            <option value="PWD">Person w/ disability</option>
                            <option value="Senior">Senior</option>
                            <option value="Employee">SIWD Employee</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="discount_amount">Discount Amount</label>
                            <input type="text" class="form-control" id="discount_amount" name="discount_amount" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="discounted_total">Discounted Total</label>
                            <input type="text" class="form-control" id="discounted_total" name="discounted_total" readonly>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center" name="add_billing_btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("status-container").style.display = "none";
</script>

<script>
    $(document).ready(function(){
        $('#client_search').on('input', function(){
            var searchQuery = $(this).val();
            if(searchQuery.trim() !== '') {
                $.ajax({
                    url: 'search.php', 
                    method: 'GET',
                    data: {query: searchQuery},
                    success: function(response){
                       
                        $('#search-results').html(response);
                    },
                    error: function(xhr, status, error){
                        console.error(error);
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
            var accountType = $(this).data('account-type');
            var billingAmount = parseFloat($(this).data('billing-amount'));
            var wqiFee = parseFloat($(this).data('wqi-fee'));
            var wmFee = parseFloat($(this).data('wm-fee'));
            var status = $(this).data('status');
            var presentReading = $(this).data('present-reading');
            if (presentReading !== '') {
                $('#previous_reading').val(parseFloat(presentReading));
            } else {
                $('#previous_reading').val('');
            }

            $('#client_search').val(accountName);
            $('#account_type').val(accountType);
            $('#account_num').val(accountNum);
            $('#client_id').val(clientId);
            
            if (status === 'Due') {
                alert("Selected client has an unsettled bill. Previous billing will be added in this billing.");
                const balance = $(this).data('balance');
                
                if($(this).data('balance')){
                    $('#arrears').val(balance.toFixed(2));
                    $("#surcharge").val((parseFloat(balance.toFixed(2)) * 0.10).toFixed(2))
                    $('#search-results').html('');
                    return

                }


                $('#wqi_fee').val(parseFloat($('#wqi_fee').val()) + wqiFee);
                $('#wm_fee').val(parseFloat($('#wm_fee').val()) + wmFee);
                $('#arrears').val(billingAmount.toFixed(2));
                $("#surcharge").val((parseFloat(billingAmount.toFixed(2)) * 0.10).toFixed(2));

            }

            if(status === 'Partially Paid' || status === 'Rolled Over') {
                alert("Selected client has an unsettled bill. Previous billing will be added in this billing.");
                
                const balance = $(this).data('balance');
                
                $('#arrears').val(balance.toFixed(2));
                $("#surcharge").val((parseFloat(balance.toFixed(2)) * 0.10).toFixed(2))
            }
            $('#search-results').html('');
        })
    });
</script>

<script>

    let fecth_delay = null;

    document.addEventListener('DOMContentLoaded', function() {
        function calculateSubtotal() {
            var fields = ['billing_amount', 'arrears', 'surcharge', 'wqi_fee', 'wm_fee', 'materials_fee', 'installation_fee'];
            var subtotal = fields.reduce((acc, field) => {
                var value = parseFloat(document.getElementById(field).value) || 0;
                return acc + value;
            }, 0);
            document.getElementById('subtotal').value = subtotal.toFixed(2);

            calculateTotal();
        }

        function calculateTax() {
            var billingAmount = parseFloat(document.getElementById('billing_amount').value) || 0;

            var tax = billingAmount * 0.02;
            if($('#arrears').val()) {
                const arrears_tax = parseFloat($('#arrears').val()) * 0.02;
                tax += arrears_tax
            }

            document.getElementById('tax').value = tax.toFixed(2);

            calculateTotal();
        }

        function calculateTotal() {
            var subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            var tax = parseFloat(document.getElementById('tax').value) || 0;

            var total = subtotal + tax;

            document.getElementById('total').value = total.toFixed(2);

            calculateDiscount();
        }

        function fetchPrice() {
            var accountType = document.getElementById('account_type').value;
            var consumption = parseFloat(document.getElementById('consumption').value);
            if (accountType && !isNaN(consumption)) {
                var tableName = '';
                switch (accountType) {
                    case 'Residential':
                        tableName = 'pricing_residential';
                        break;
                    case 'Semi-commercial':
                        tableName = 'pricing_semicom';
                        break;
                    case 'Commercial':
                        tableName = 'pricing_commercial';
                        break;
                    case 'Government':
                        tableName = 'pricing_semicom';
                        break;
                    default:
                        console.error('Invalid account type');
                        return;
                }
                
               
                $.ajax({
                    url: 'fetch_price.php',
                    method: 'POST',
                    data: { tableName: tableName, consumption: consumption },
                    success: function(response){
                        
                        document.getElementById('billing_amount').value = Number(response).toFixed(2);
                        calculateSubtotal();
                        calculateTax();
                    },
                    error: function(xhr, status, error){
                        console.error(error);
                    }
                });
             
            }
        }

        function updateConsumption() {
            var previousReading = parseFloat(document.getElementById('previous_reading').value);
            var presentReading = parseFloat(document.getElementById('present_reading').value);

            if (!isNaN(previousReading) && !isNaN(presentReading)) {
                var consumption = presentReading - previousReading;
                document.getElementById('consumption').value = consumption;
                
                if(fecth_delay) { clearTimeout(fecth_delay) }
            
                calculateSubtotal();
                calculateTax();
                fecth_delay = setTimeout(() => fetchPrice() , 1000)
            
            } else {
                document.getElementById('consumption').value = '';
            }
        }

        function calculateSurcharge() {
            var arrears = parseFloat(document.getElementById('arrears').value);
            if (!isNaN(arrears)) {
                var surcharge = arrears * 0.10;

                document.getElementById('surcharge').value = surcharge.toFixed(2);
            } else {
                document.getElementById('surcharge').value = '';
            }

            calculateSubtotal();
        }

        function calculateDiscount() {
            var totalBilling = parseFloat(document.getElementById('billing_amount').value)
            var totalAmount = parseFloat(document.getElementById('total').value);
            var discountType = document.getElementById('discount_type').value;
            var discountAmountInput = document.getElementById('discount_amount');
            var discountedTotalInput = document.getElementById('discounted_total');
            var discountedBillingInput = document.getElementById('discounted_billing');

            let discount = 0;
            let discountedTotal = totalAmount;
            let discountedBilling = totalBilling;

            if (discountType === "Senior" || discountType === "PWD") {
                discount = totalAmount * 0.05;
            } else if (discountType === "Employee") {
                discount = 337;
            } else {
                discount = 0;
            }

            discountedTotal = totalAmount - discount;
            discountedBilling = totalBilling - discount;

            discountAmountInput.value = discount.toFixed(2);
            discountedTotalInput.value = discountedTotal.toFixed(2);
            discountedBillingInput.value = discountedBilling.toFixed(2);
        }

        // document.getElementById('consumption').addEventListener('input', fetchPrice);
        document.getElementById('present_reading').addEventListener('input', updateConsumption);
        document.getElementById('previous_reading').addEventListener('input', updateConsumption);
        document.getElementById('arrears').addEventListener('input', calculateSurcharge);
        document.getElementById('discount_type').addEventListener('change', calculateDiscount);

        calculateTotal();
    });
</script>









<?php include('includes/footer.php') ?>