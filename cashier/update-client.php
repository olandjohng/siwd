<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    $query = "SELECT * FROM clients WHERE client_id = $client_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $client_data = mysqli_fetch_assoc($result);
    } else {
        echo "Client not found";
        exit();
    }
} else {
    echo "Client ID not provided";
    exit();
}
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
                <li class="breadcrumb-item active" aria-current="page">Update Client Profile</li>
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
                        <input type="hidden" name="client_id" value="<?= $client_data['client_id']; ?>">
                        <div>
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" value="<?= $client_data['account_name']; ?>" placeholder="Enter account name" required>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div>
                            <label for="account_num">Account No.</label>
                            <input type="text" class="form-control" id="account_num" name="account_num" value="<?= $client_data['account_num']; ?>" readonly required>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3">
                        <label for="account_type">Account Type</label>
                        <select id="account_type" name="account_type" class="form-select mb-0" aria-label="Account type select">
                            <option selected="selected">Account type</option>
                            <option value="1"<?= $client_data['account_type'] == '1' ? "selected":"" ?>>Residential</option>
                            <option value="2"<?= $client_data['account_type'] == '2' ? "selected":"" ?>>Semi-Commercial</option>
                            <option value="3"<?= $client_data['account_type'] == '3' ? "selected":"" ?>>Commercial</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" id="phone" name="phone" value="<?= $client_data['phone']; ?>" class="form-control" placeholder="+63 919 123 4567">
                        </div>
                    </div>
                </div>
                <h2 class="h5 my-4">Location</h2>
                <div class="row">
                    <div class="col-sm-9 mb-3">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?= $client_data['address']; ?>" placeholder="Enter address" required>
                        </div>
                    </div>
                    <div class="col-sm-3 mb3">
                        <div class="form-group">
                            <label for="meter_num">Meter No.</label>
                            <input type="text" class="form-control" id="meter_num" name="meter_num" value="<?= $client_data['meter_num']; ?>" placeholder="Meter No." required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <label for="zone">Zone</label>
                        <select id="zone" name="zone" class="form-select mb-0" aria-label="Zone select">
                            <option selected="selected">Select zone</option>
                            <option value="1A"<?= $client_data['zone'] == '1A' ? "selected":"" ?>>1A</option>
                            <option value="1B"<?= $client_data['zone'] == '1B' ? "selected":"" ?>>1B</option>
                            <option value="2"<?= $client_data['zone'] == '2' ? "selected":"" ?>>2</option>
                            <option value="3"<?= $client_data['zone'] == '3' ? "selected":"" ?>>3</option>
                            <option value="4A"<?= $client_data['zone'] == '4A' ? "selected":"" ?>>4A</option>
                            <option value="4B"<?= $client_data['zone'] == '4B' ? "selected":"" ?>>4B</option>
                            <option value="5"<?= $client_data['zone'] == '5' ? "selected":"" ?>>5</option>
                            <option value="6A"<?= $client_data['zone'] == '6A' ? "selected":"" ?>>6A</option>
                            <option value="6B"<?= $client_data['zone'] == '6B' ? "selected":"" ?>>6B</option>
                            <option value="7"<?= $client_data['zone'] == '7' ? "selected":"" ?>>7</option>
                            <option value="8"<?= $client_data['zone'] == '8' ? "selected":"" ?>>8</option>
                            <option value="9"<?= $client_data['zone'] == '9' ? "selected":"" ?>>9</option>
                            <option value="10"<?= $client_data['zone'] == '10' ? "selected":"" ?>>10</option>
                            <option value="11"<?= $client_data['zone'] == '11' ? "selected":"" ?>>11</option>
                            <option value="12"<?= $client_data['zone'] == '12' ? "selected":"" ?>>12</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay" class="form-select mb-0" aria-label="Barangay select">
                            <option selected="selected">Select Barangay</option>
                            <option value="Batobato"<?= $client_data['barangay'] == 'Batobato' ? "selected":"" ?>>Batobato (Pob.)</option>
                            <option value="Baon"<?= $client_data['barangay'] == 'Baon' ? "selected":"" ?>>Baon</option>
                            <option value="Manikling"<?= $client_data['barangay'] == 'Manikling' ? "selected":"" ?>>Manikling</option>
                            <option value="San Roque"<?= $client_data['barangay'] == 'San Roque' ? "selected":"" ?>>San Roque</option>
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select mb-0" aria-label="Status select">
                            <option value="1"<?= $client_data['status'] == '1' ? "selected":"" ?>>Active</option>
                            <option value="0"<?= $client_data['status'] == '0' ? "selected":"" ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" name="update_client_btn" class="btn btn-gray-800 d-inline-flex align-items-center">Update</button>
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