<?php
include('../middleware/adminMiddleware.php');
include('includes/header.php');

$users = getAll('users');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="../admin/index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Users List</li>
            </ol>
        </nav>
        <h2 class="h4">Users List</h2>
        <p class="mb-0">List of users of SIWD System</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-block btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-form-add-new">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New
        </button>
        <div class="modal fade" id="modal-form-add-new" tabindex="-1" aria-labelledby="modal-form-add-new" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card p-3 p-lg-4">
                            <button class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-0 h4">Create New Account</h1>
                            </div>
                            <form action="code.php" method="post" class="mt-4">
                                <div class="form-group mb-4">
                                    <select class="form-select d-none d-md-inline" name="role" aria-label="role" required>
                                        <option selected="selected">Select role</option>
                                        <option value="1">Staff</option>
                                        <option value="2">Cashier</option>
                                        <option value="3">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" autofocus required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" autofocus required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" id="designation" name="designation" autofocus required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" autofocus required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" autofocus required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="add_user_btn" class="btn btn-gray-800">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="table-settings mb-4">
    <div class="row justify-content-between align-items-center">
        <div class="col-9 col-lg-8 d-md-flex">
            <div class="input-group me-2 me-lg-3 fmxw-300">
                <span class="input-group-text">
                    <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                </span>
                <input type="text" class="form-control" placeholder="Search users">
            </div>
            <select class="form-select fmxw-200 d-none d-md-inline" aria-label="Message select example 2">
                <option selected="selected">All</option>
                <option value="1">Active</option>
                <option value="2">Suspended</option>
            </select>
        </div>
    </div>
</div> -->

<div class="card card-body shadow border=0 table-wrapper table-responsive" id="users_table">
    <table class="table user-table table-hover align-items-center">
        <thead>
            <tr>
                <th class="border-bottom">Name</th>
                <th class="border-bottom">Date Created</th>
                <th class="border-bottom">Username</th>
                <th class="border-bottom">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($users && mysqli_num_rows($users) > 0) {
                while ($row = mysqli_fetch_assoc($users)) {
                    $name = $row['firstname'] . ' ' . $row['lastname'];
                    $designation = $row['designation'];
                    $username = $row['username'];
                    $dateCreated = date('M d Y', strtotime($row['date_created']));
                    $id = $row['user_id'];
                    ?>
            <tr>
                <td>
                    <a href="#" class="d-flex align-items-center">
                        <div class="d-block">
                            <span class="fw-bold"><?= $name; ?></span>
                            <div class="small text-gray"><?= $designation; ?></div>
                        </div>
                    </a>
                </td>
                <td>
                    <span class="fw-normal"><?= $dateCreated; ?></span>
                </td>
                <td>
                    <span class="fw-normal"><?= $username; ?></span>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"></path>
                                </svg>
                                 Reset Pass
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                                 View Details
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 6a3 3 0 11-6 0 3 3 0 016 0zM14 17a6 6 0 00-12 0h12zM13 8a1 1 0 100 2h4a1 1 0 100-2h-4z"></path>
                                </svg> 
                                Suspend
                            </a>
                        </div>
                    </div>
                    <button class="btn btn-link text-dark" type="button" name="delete_user_btn" onclick="confirmDelete(<?= $id; ?>)">
                        <svg class="icon icon-xs text-danger ms-1" title="" data-bs-toggle="tooltip" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-bs-original-title="Delete" aria-label="Delete">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="4">No users found</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(userId) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this user.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deleteUser(userId);
            }
        });
    }

    function deleteUser(userId) {
        $.ajax({
            url: 'code.php',
            type: 'POST',
            data: { user_id: userId },
            success: function(response) {
                if (response === '200') {
                    swal ("Success!", "User deleted successfully", "success");
                    $("#users_table").load(location.href + " #users_table");
                } else if (response === '500') {
                    swal ("Error!", "Failed to delete user", "error");
                }
            },
            error: function() {
                swal("Error!", "Failed to delete user", "error");
            }
        })
    }
</script>
<?php include('includes/footer.php') ?>