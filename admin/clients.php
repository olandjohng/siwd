<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$clients = getAll('clients');
?>
<style>
    th:hover {
        cursor: pointer;
    }
</style>

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
        <h2 class="h4">Clients List</h2>
        <p class="mb-0">List of all clients of San Isidro Water District.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add-client.php" type="button" class="btn btn-block btn-gray-800 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive py-4">
        <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
            <!-- <div class="dataTable-top">
                <div class="dataTable-dropdown">
                    <label>
                        <select id="table-selector" class="dataTable-selector">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                        </select>
                            entries per page
                    </label>
                </div>
                <div class="dataTable-search">
                    <input type="text" id=searchInput class="dataTable-input" placeholder="Search..."> 
                </div>
            </div> -->
            <div class="dataTable-container">
                <table class="table table-flush dataTable-table data-table" id="clientTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Account No.</th>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Status</th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($clients && mysqli_num_rows($clients) > 0){
                            while ($row = mysqli_fetch_assoc($clients)) {
                                $account_num = $row['account_num'];
                                $account_name = $row['account_name'];
                                $account_type = $row['account_type'];
                                $status = $row['status'];
                                $id = $row['client_id'];

                                $account_type_display = '';
                                switch ($account_type) {
                                    case 1:
                                        $account_type_display = 'Residential';
                                        break;
                                    case 2:
                                        $account_type_display = 'Semi-Commercial';
                                        break;
                                    case 3:
                                        $account_type_display = 'Commercial';
                                        break;
                                    default:
                                        $account_type_display = 'Unknown';
                                        break;  
                                }
                                
                                $status_display = '';
                                switch ($status) {
                                    case 1:
                                        $status_display = 'Active';
                                        break;
                                    case 0:
                                        $status_display = 'Inactive';
                                        break;
                                    default:
                                        $status_display = 'Unknown';
                                        break;
                                }
                        ?> 
                        <tr>
                            <td><?= $account_num; ?></td>
                            <td><?= $account_name; ?></td>
                            <td><?= $account_type_display; ?></td>
                            <?php
                                $status_color = '';
                                if ($status === '1') {
                                    $status_color = 'text-success';
                                } else if ($status === '0') {
                                    $status_color = 'text-danger';
                                } else {

                                }
                            ?>
                            <td class="<?= $status_color; ?>"><?= $status_display; ?></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                        <a class="dropdown-item d-flex align-items-center" href="update-client.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"></path>
                                            </svg>
                                            Edit Client
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="view-client.php?id=<?= $id; ?>">
                                            <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            View Details
                                        </a>
                                        <?php if ($status === '1') { ?>
                                            <button class="dropdown-item d-flex align-items-center" type="button" onclick="confirmSuspend(<?= $id; ?>)">
                                                <svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11 6a3 3 0 11-6 0 3 3 0 016 0zM14 17a6 6 0 00-12 0h12zM13 8a1 1 0 100 2h4a1 1 0 100-2h-4z"></path>
                                                </svg> 
                                                Suspend
                                            </button>
                                        <?php } else { ?>
                                            <button class="dropdown-item d-flex align-items-center" type="button" onclick="unsuspendClient(<?= $id; ?>)">
                                                <svg class="dropdown-icon text-success me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z M16 12a2 2 0 11-4 0 2 2 0 014 0zM14 17a6 6 0 00-12 0h12zM13 8a1 1 0 100 2h4a1 1 0 100-2h-4z"></path>
                                                </svg> 
                                                Unsuspend
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                                <button class="btn btn-link text-dark" type="button" name="delete_client_btn" onclick="confirmDelete(<?= $id; ?>)">
                                    <svg class="icon icon-xs text-danger ms-1" title="" data-bs-toggle="tooltip" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-bs-original-title="Delete" aria-label="Delete">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo '<tr><td colspan="4">No clients found</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- <div class="dataTable-bottom">
                <div class="dataTable-info" id="dataTable-info">
                </div>
                <nav class="dataTable-pagination">
                    <ul id="pagination-list" class="dataTable-pagination-list">
                        <li class="pager">
                            <a href="#" data-page="1">‹</a>
                        </li>
                        <li class="active">
                            <a href="#" data-page="1">1</a>
                        </li>
                        <li class>
                            <a href="#" data-page="2">2</a>
                        </li>  
                        <li class>
                            <a href="#" data-page="3">3</a>
                        </li>
                        <li class="pager">
                            <a href="#" data-page="2">›</a>
                        </li>
                    </ul>
                </nav>
            </div> -->
        </div>
    </div>
</div>

<script>
    function confirmDelete(clientId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not able to recover this client.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deleteClient(clientId);
            }
        });
    }

    function deleteClient(clientId) {
        $.ajax({
            url: 'code.php',
            type: 'POST',
            data: { client_id: clientId },
            success: function(response) {
                if (response === '200') {
                    swal ("Success", "Client deleted successfully", "success");
                    $("#clientTable").load(location.href + " #clientTable");
                } else if (response === '500') {
                    swal ("Error!", "Failed to delete client", "error");
                }
            },
            error: function() {
                swal("Error", "Failed to delete client", "error");
            }
        })
    }
</script>

<script>
    function confirmSuspend(clientId) {
        swal({
            title: "Are you sure?",
            text: "The client will be listed as inactive.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willSuspend) => {
            if (willSuspend) {
                suspendClient(clientId);
            }
        });
    }

    function suspendClient(clientId) {
        $.ajax({
            url: 'suspend-client.php',
            type: 'POST',
            data: { client_id: clientId },
            success: function(response) {
                if (response === '200') {
                    swal("Success", "Client has suspended successfully", "success")
                    .then(() => {
                        location.reload();
                    })
                } else if (response === '500'){
                    swal("Error!", "Failed to suspend client", "error");
                }
            },
            error: function() {
                swal("Error", "Failed to suspend client", "error");
            }

        });
    }
</script>

<script>
    function unsuspendClient(clientId) {
        swal({
            title: "Activating Client",
            text: "The client will be listed as active.",
            icon: "info",
            buttons: true,
            dangerMode: true,
        }).then((willUnsuspend) => {
            if (willUnsuspend) {
                activateClient(clientId);
            }
        });
    }

    function activateClient(clientId) {
        $.ajax({
            url: 'unsuspend-client.php',
            type: 'POST',
            data: { client_id: clientId },
            success: function(response) {
                if (response === '200') {
                    swal("Success", "Client has been unsuspended successfully", "success")
                    .then(() => {
                        location.reload();
                    })
                } else if (response === '500') {
                    swal("Error!", "Failed to unsuspend client", "error");
                }
            },
            error: function() {
                swal("Error", "Something went wrong", "error");
            }
        });
    }
</script>
<?php include('includes/footer.php') ?>