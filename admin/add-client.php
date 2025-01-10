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
                    <a href="../admin/clients.php">Clients List</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Add Client</li>
            </ol>
        </nav>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">General information</h2>
            <form action="code.php" method="post">
                <div class="row">
                    <div class="col-md-9 mb-3">
                        <div>
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Enter account name" required>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="account_num">Account No.</label>
                            <input type="text" class="form-control" id="account_num" name="account_num" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3">
                        <label for="account_type">Account Type</label>
                        <select id="account_type" name="account_type" class="form-select mb-0" aria-label="Account type select">
                            <option selected="selected">Account type</option>
                            <option value="1">Residential</option>
                            <option value="2">Semi-Commercial</option>
                            <option value="3">Commercial</option>
                            <option value="4">Government</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" id="phone" name="phone" class="form-control" placeholder="+63 919 123 4567">
                        </div>
                    </div>
                </div>
                <h2 class="h5 my-4">Location</h2>
                <div class="row">
                    <div class="col-sm-9 mb-3">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required>
                        </div>
                    </div>
                    <div class="col-sm-3 mb3">
                        <div class="form-group">
                            <label for="meter_num">Meter No.</label>
                            <input type="text" class="form-control" id="meter_num" name="meter_num" placeholder="Meter No." required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <label for="zone">Zone</label>
                        <select id="zone" name="zone" class="form-select mb-0" aria-label="Zone select">
                            <option selected="selected">Select zone</option>
                            <option value="1A">1A</option>
                            <option value="1B">1B</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4A">4A</option>
                            <option value="4B">4B</option>
                            <option value="5">5</option>
                            <option value="6A">6A</option>
                            <option value="6B">6B</option>
                            <option value="7A">7A</option>
                            <option value="7B">7B</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay" class="form-select mb-0" aria-label="Barangay select">
                            <option selected="selected">Select Barangay</option>
                            <option value="Batobato">Batobato (Pob.)</option>
                            <option value="Baon">Baon</option>
                            <option value="Manikling">Manikling</option>
                            <option value="San Roque">San Roque</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select mb-0" aria-label="Status select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" name="add_client_btn" class="btn btn-gray-800 d-inline-flex align-items-center">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function capitalizeFirstLetter(input) {
        return input.replace(/\b\w/g, function (char) {
            return char.toUpperCase();
        });
    }

    document.querySelectorAll('input[type="text"]').forEach(function(input) {
        input.addEventListener('input', function(event) {

            var cursorPosition = this.selectionStart;

            this.value = capitalizeFirstLetter(this.value.toLowerCase());

            this.setSelectionRange(cursorPosition, cursorPosition);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var zoneInput = document.getElementById('zone');
        var meterNumInput = document.getElementById('meter_num');
        var accountNumInput = document.getElementById('account_num');

        function updateAccountNum() {
            var zoneValue = zoneInput.value;
            var meterNumValue = meterNumInput.value;
            accountNumInput.value = zoneValue + '-' + meterNumValue;
        }

        zoneInput.addEventListener('input', updateAccountNum);
        meterNumInput.addEventListener('input', updateAccountNum);
    });
</script>




<?php include('includes/footer.php') ?>