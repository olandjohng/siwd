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
                <li class="breadcrumb-item active" aria-current="page">Billing Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
        <?php
            if(isset($_GET['id'])) {
                $billing_id = $_GET['id'];

                $billing = getBillingId($billing_id);

                if($billing) {        
                ?>
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                    <div class="mb-xl-0 mb-4">
                        <div class="d-flex mb-3 gap-2">
                            <span class="fw-extrabold">SAN ISIDRO WATER DISTRICT</span>
                        </div>
                        <p class="mb-1">LGU Avenue, Brgy. Batobato</p>
                        <p class="mb-1">San Isidro, Davao Oriental</p>
                        <p class="mb-1">+63 978 565 5346</p>
                    </div>
                    <div>
                        <h4>Billing #<?= $billing['billing_num']; ?></h4>
                        <div class="mb-2">
                            <span class="me-1">Date Issued:</span>
                            <span class="fw-normal"><?= date('m/d/Y', strtotime($billing['reading_date'])); ?></span>
                        </div>
                        <div class="mb-2">
                            <span class="me-1">Date Due:</span>
                            <span class="fw-normal"><?= date('m/d/Y', strtotime($billing['due_date'])); ?></span>
                        </div>
                        <div class="d-block d-sm-flex">
                            <span class="me-1">Status:</span>
                            <div class="ms-sm-3">
                                <?php
                                    $status = $billing['status'];
                                    $badgeClass = '';

                                    switch ($status) {
                                        case 'Pending':
                                            $badgeClass = 'bg-warning';
                                            break;
                                        case 'Rolled Over':
                                            $badgeClass = 'bg-purple';
                                            break;
                                        case 'Due':
                                            $badgeClass = 'bg-danger';
                                        case 'Paid':
                                            $badgeClass = 'bg-success';
                                        default:
                                    }
                                ?>
                                <span class="badge super-badge <?= $badgeClass; ?>"><?= $billing['status']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <div class="row p-sm-3 p-0">
                    <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                        <h6 class="pb-2">Bill To:</h6>
                        <p class="mb-1"><?= $billing['account_name']; ?></p>
                        <p class="mb-1"><?= $billing['account_num']; ?></p>
                        <p class="mb-1"><?= $billing['address'] . ', Brgy. ' . $billing['barangay']; ?></p>
                        <p class="mb-1">San Isidro, Davao Oriental</p>
                    </div>
                    <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                        <h6 class="pb-2">Billing Details</h6>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="pe-3">Covered From:</td>
                                    <td><?= date('m/d/Y', strtotime($billing['covered_from'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="pe-3">Covered To:</td>
                                    <td><?= date('m/d/Y', strtotime($billing['covered_to'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="pe-3">Previous Reading:</td>
                                    <td><?= $billing['previous_reading']; ?></td>
                                </tr>
                                <tr>
                                    <td class="pe-3">Present Reading:</td>
                                    <td><?= $billing['present_reading']; ?></td>
                                </tr>
                                <tr>
                                    <td class="pe-3">Consumption:</td>
                                    <td><?= $billing['consumption']; ?>&#13221</td>
                                </tr>
                                <tr>
                                    <td class="pe-3">Discount Type:</td>
                                    <td><?= $billing['discount_type']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table border-top m-0">
                    <tbody>
                        <tr>
                            <td class="text-nowrap">Billing Amount</td>
                            <td><?= $billing['billing_amount']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Arrears</td>
                            <td><?= $billing['arrears']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Surcharge</td>
                            <td><?= $billing['surcharge']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Water Quality Improvement</td>
                            <td><?= $billing['wqi_fee']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Water Management</td>
                            <td><?= $billing['wm_fee']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Materials Fee</td>
                            <td><?= $billing['materials_fee']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-nowrap">Installation Fee</td>
                            <td><?= $billing['installation_fee']; ?></td>
                        </tr>
                        <tr>
                            <td class="text-end px-4 py-5">
                                <p class="mb-2">Subtotal:</p>
                                <p class="mb-2">Tax:</p>
                                <p class="mb-2">Billing Total:</p>
                                <p class="mb-2">Discount:</p>
                                <p class="mb-2 fw-bolder">TOTAL:</p>
                            </td>
                            <td class="px-4 py-5 text-end">
                                <p class="fw-normal mb-2"><?= $billing['subtotal']; ?></p>
                                <p class="fw-normal mb-2"><?= $billing['tax']; ?></p>
                                <p class="fw-normal mb-2"><?= $billing['total']; ?></p>
                                <p class="fw-normal mb-2">-<?= $billing['discount_amount']; ?></p>
                                <p class="fw-bolder mb-2">₱ <?= $billing['discounted_total']; ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <small>
                            <span class="fw-bold">Note:</span>
                            <span>A penalty charge is added to the bills paid after due date. Service may
                            be discontinued without further notice if payment of bill is not made to the office
                            bill collector by due date. If payment is made by
                            checks please make the check payable to the Water District. Returned
                            Checks that are not made good is cash by due date may likewise result in
                            discontinuance of services.</span>
                        </small>
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
    </div>
    <!-- <div class="col-xl-3 col-md-4 col-12">
        <div class="card shadow border-0">
            <div class="card-body">
                <button class="btn btn-secondary d-grid w-100 mb-3 export-pdf" onclick="exportBilling('pdf')"> Export </button>
                <a class="btn btn-secondary d-grid w-100 mb-3" href="pos-billing.php?id=<?= $billing['billing_id']; ?>"> Print </a>
                <a class="btn btn-primary d-grid w-100 mb-3" href="update-billing.php?id=<?= $billing['billing_id']; ?>"> Edit Billing </a>
            </div>
        </div>
    </div> -->
</div>




<?php include('includes/footer.php') ?>