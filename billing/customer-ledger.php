<?php
include('../middleware/billingMiddleware.php');
include('includes/header.php');

$clients = getAllClientsAsc();

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>
        <h2 class="h4">Customer Ledger Report</h2>
        <p class="mb-0">Comprehensive insights and analysis on transactions and invoices of customers of SIWD.</p>
    </div>
</div>

<div class="card border-0 mb-4">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="fs-5 fw-bold mb-0">List of SIWD Clients</h2>
            </div>
        </div>
    </div>
    <div class="table-responsive py-4">    
        <table class="table align-items-center table-flush data-table" id="ledgerTable">
            <thead class="thead-light">
                <tr>
                    <th>Customer Name</th>
                    <th>Acct No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($clients && mysqli_num_rows($clients) > 0) {
                    while ($row = mysqli_fetch_assoc($clients)) {
                        $account_name = $row['account_name'];
                        $account_num = $row['account_num'];
                        $id = $row['client_id'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($account_name); ?></td>
                    <td><?= htmlspecialchars($account_num); ?></td>
                    <td>
                        <a href="customer-ledger-datatable.php?id=<?= $id; ?>">
                            <svg class="icon icon-xs text-gray-400 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="2">No customers found</td></tr>';
                    }
                    ?>
            </tbody>
        </table>        
    </div>
</div>

<?php include('includes/footer.php') ?>
