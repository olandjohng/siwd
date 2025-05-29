<?php
include('../middleware/cashierMiddleware.php');
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
                <li class="breadcrumb-item active" aria-current="page">Update Billing Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12">
        <?php 
            if(isset($_GET['id']))
            {
                $billing_id = $_GET['id'];
                $billing = getBillingID($billing_id);

                if($billing){
            
        ?>
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">Update Billing</h2>
            <form action="code.php" method="POST">
                <div class="row">
                    <div class="col-md-9 mb-3">
                        <input type="hidden" name="billing_id" value="<?=$billing['billing_id']; ?>">
                        <div>
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control" id="client_search" name="client_search" placeholder="Enter account name" value="<?= $billing['account_name']; ?>" required>
                        </div>
                        <div id="search-results"></div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="billing_num">Billing No.</label>
                            <input type="text" class="form-control" id="billing_num" name="billing_num" value="<?= $billing['billing_num']; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="account_type">Account Type</label>
                            <input type="text" id="account_type" name="account_type" class="form-control" value="<?= getAccountTypeText($billing['account_type']); ?>" readonly>
                            <input type="hidden" id="client_id" name="client_id" value="<?= $billing['client_id']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="account_num">Account Number</label>
                            <input type="text" id="account_num" name="account_num" class="form-control" value="<?= $billing['account_num']; ?>" readonly>
                        </div>
                    </div>             
                </div>
                <h2 class="h5 my-4">Billing Details</h2>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="covered_from">Period Covered From</label>
                            <input type="date" class="form-control" id="covered_from" name="covered_from" value="<?= $billing['covered_from']; ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="covered_to">Period Covered To</label>
                            <input type="date" class="form-control" id="covered_to" name="covered_to" value="<?= $billing['covered_to']; ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="reading_date">Reading Date</label>
                            <input type="date" class="form-control" id="reading_date" name="reading_date" value="<?= $billing['reading_date']; ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" value="<?= $billing['due_date']; ?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="previous">Previous Reading</label>
                            <input type="text" class="form-control" id="previous_reading" name="previous_reading" value="<?= $billing['previous_reading']; ?>" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="present">Present Reading</label>
                            <input type="text" class="form-control" id="present_reading" name="present_reading" value="<?= $billing['present_reading']; ?>" required pattern="[0-9]+" title="Please enter only numbers">
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="consumption">Consumption (&#13221)</label>
                            <input type="text" class="form-control" id="consumption" name="consumption" value="<?= $billing['consumption']; ?>"  readonly required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="billing_amount">Billing Amount</label>
                            <input type="text" class="form-control" id="billing_amount" name="billing_amount" value="<?= $billing['billing_amount']; ?>"  readonly required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="arrears">Arrears</label>
                            <input type="text" class="form-control" id="arrears" name="arrears" value="<?= $billing['arrears']; ?>" >
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="form-group">
                            <label for="surcharge">Surcharge (10%)</label>
                            <input type="text" class="form-control" id="surcharge" name="surcharge" value="<?= $billing['surcharge']; ?>" >
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
                            <input type="currency" class="form-control" id="materials_fee" value="<?= $billing['materials_fee']; ?>" name="materials_fee">
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="installation">Installation Fee</label>
                            <input type="currency" class="form-control" id="installation_fee" value="<?= $billing['installation_fee']; ?>" name="installation_fee">
                        </div>
                    </div>
                </div>

                <h2 class="h5 my-4">Tax & Total</h2>
                <div class="row">
                    <input type="hidden" id="subtotal">
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="tax">Franchise Tax</label>
                            <input type="text" class="form-control" id="tax" name="tax" value="<?= $billing['tax']; ?>"  readonly required>
                        </div>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input type="text" class="form-control" id="total" name="total" value="<?= $billing['total']; ?>"  readonly required>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3" id="status-container">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select mb-0" aria-label="Status select">
                            <option value="Pending"<?= $billing['status'] == 'Pending' ? "selected":"" ?>>Pending</option>
                            <option value="Paid"<?= $billing['status'] == 'Paid' ? "selected":"" ?>>Paid</option>
                        </select>
                    </div>
                </div>

                <h2 class="h5 my-4">Apply Discount</h2>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="discount_type">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-select" aria-label="Discount type select">
                            <option value="N/A"<?= $billing['discount_type'] == 'N/A' ? "selected":"" ?>>Select Discount</option>
                            <option value="PWD"<?= $billing['discount_type'] == 'PWD' ? "selected":"" ?>>Person w/ disability</option>
                            <option value="Senior"<?= $billing['discount_type'] == 'Senior' ? "selected":"" ?>>Senior</option>
                            <option value="Employee"<?= $billing['discount_type'] == 'Employee' ? "selected":"" ?>>SIWD Employee</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <div class="form-group">
                            <label for="discount_amount">Discount Amount</label>
                            <input type="text" class="form-control" id="discount_amount" name="discount_amount" value="<?= $billing['discount_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="discounted_total">Discounted Total</label>
                            <input type="text" class="form-control" id="discounted_total" name="discounted_total" value="<?= $billing['discounted_total']; ?>" readonly>
                        </div>
                    </div>
                </div>
                    
                <div class="mt-3">
                    <button type="submit" class="btn btn-gray-800 d-inline-flex align-items-center" name="update_billing_btn">Update</button>
                </div>
            </form>
        </div>
        <?php
                } else {
                    echo "Billing not found";
                }
            } else {
                echo "ID missing from URL";
            }
        ?>
    </div>
</div>

<?php
function getAccountTypeText($accountType) {
    switch($accountType) {
        case 1:
            return "Residential";
        case 2:
            return "Semi-commercial";
        case 3:
            return "Commercial";
        default:
            return "Unknown";
    }
}
?>

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

            $('#client_search').val(accountName);
            $('#account_type').val(accountType);
            $('#account_num').val(accountNum);
            $('#client_id').val(clientId);

            $('#search-results').html('');
        })
    });
</script>

<script>
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
        document.getElementById('tax').value = tax.toFixed(2);

        calculateTotal();
    }

    function calculateTotal() {
        var subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        var tax = parseFloat(document.getElementById('tax').value) || 0;

        var total = subtotal + tax;

        document.getElementById('total').value = total.toFixed(2);
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
                default:
                    console.error('Invalid account type');
                    return;
            }

            $.ajax({
                url: 'fetch_price.php',
                method: 'POST',
                data: { tableName: tableName, consumption: consumption },
                success: function(response){
                    document.getElementById('billing_amount').value = response;
                    calculateSubtotal();
                    calculateTax();
                    calculateTotal();
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
            fetchPrice();
        } else {
            document.getElementById('consumption').value = '';
        }
    }

    document.getElementById('consumption').addEventListener('input', fetchPrice);
    document.getElementById('present_reading').addEventListener('input', updateConsumption);
    document.getElementById('previous_reading').addEventListener('input', updateConsumption);
    document.querySelectorAll('#billing_amount, #arrears, #surcharge, #wqi_fee, #wm_fee, #materials_fee, #installation_fee').forEach(input => {
        input.addEventListener('input', calculateSubtotal);
    });

    updateConsumption();
</script>

<!-- <script>
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
                    default:
                        console.error('Invalid account type');
                        return;
                }

                $.ajax({
                    url: 'fetch_price.php',
                    method: 'POST',
                    data: { tableName: tableName, consumption: consumption },
                    success: function(response){
                        document.getElementById('billing_amount').value = response;
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
                fetchPrice();
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
            var totalAmount = parseFloat(document.getElementById('total').value);
            var discountType = document.getElementById('discount_type').value;
            var discountAmountInput = document.getElementById('discount_amount');
            var discountedTotalInput = document.getElementById('discounted_total');

            let discount = 0;
            let discountedTotal = totalAmount;

            if (discountType === "Senior" || discountType === "PWD") {
                discount = totalAmount * 0.05;
            } else if (discountType === "Employee") {
                discount = 337;
            } else {
                discount = 0;
            }

            discountedTotal = totalAmount - discount;

            discountAmountInput.value = discount.toFixed(2);
            discountedTotalInput.value = discountedTotal.toFixed(2);
        }

        document.getElementById('consumption').addEventListener('input', fetchPrice);
        document.getElementById('present_reading').addEventListener('input', updateConsumption);
        document.getElementById('previous_reading').addEventListener('input', updateConsumption);
        document.getElementById('arrears').addEventListener('input', calculateSurcharge);
        document.getElementById('discount_type').addEventListener('change', calculateDiscount);

        calculateTotal();
    });
</script> -->

<?php include('includes/footer.php') ?>