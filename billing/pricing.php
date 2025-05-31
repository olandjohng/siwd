<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$residential = getAll('pricing_residential');
$semicom = getAll('pricing_semicom');
$commercial = getAll('pricing_commercial');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Clients List</li>
            </ol>
        </nav>
        <h2 class="h4">Pricing</h2>
        <p class="mb-0">Pricing table for your reference</p>
    </div>
</div>

<div class="row">
    <div class="col-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <h2 class="h5">Residential</h2>
                <div class="table-responsive" style="height: 500px; overflow-y: auto;">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light" style="position: sticky; top: 0;">
                            <tr>
                                <th class="border-0 rounded-start">Cubic Meters</th>
                                <th class="border-0 rounded-start">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($residential && mysqli_num_rows($residential) > 0) {
                                while ($row = mysqli_fetch_assoc($residential)) {
                                    $cubic_meter = $row['cubic_meter'];
                                    $price = $row['price'];
                                    ?>
                            <tr>
                                <td class="border-0"><?= $cubic_meter ?></td>
                                <td class="border-0 fw-bold"><?= $price ?></td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td>No price data found</td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <h2 class="h5">Semi-commercial</h2>
                <div class="table-responsive" style="height: 500px; overflow-y: auto;">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light" style="position: sticky; top: 0;">
                            <tr>
                                <th class="border-0 rounded-start">Cubic Meters</th>
                                <th class="border-0 rounded-start">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($semicom && mysqli_num_rows($semicom) > 0) {
                                while ($row = mysqli_fetch_assoc($semicom)) {
                                    $cubic_meter = $row['cubic_meter'];
                                    $price = $row['price'];
                                    ?>
                            <tr>
                                <td class="border-0"><?= $cubic_meter ?></td>
                                <td class="border-0 fw-bold"><?= $price ?></td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td>No price data found</td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <h2 class="h5">Commercial</h2>
                <div class="table-responsive" style="height: 500px; overflow-y: auto;">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light" style="position: sticky; top: 0;">
                            <tr>
                                <th class="border-0 rounded-start">Cubic Meters</th>
                                <th class="border-0 rounded-start">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($commercial && mysqli_num_rows($commercial) > 0) {
                                while ($row = mysqli_fetch_assoc($commercial)) {
                                    $cubic_meter = $row['cubic_meter'];
                                    $price = $row['price'];
                                    ?>
                            <tr>
                                <td class="border-0"><?= $cubic_meter ?></td>
                                <td class="border-0 fw-bold"><?= $price ?></td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td>No price data found</td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('includes/footer.php') ?>