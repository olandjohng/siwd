<?php
include('../middleware/cashierMiddleware.php');
include('includes/header.php');
?>

<div class="vh-100 d-flex justify-content-center align-items-center">
   <div class="card col-md-8 bg-white shadow-md p-5">
        <div class="mb-4 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
                fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
            </svg>
        </div>
        <div class="text-center">
            <h1>Payment Successful!</h1>
            <p>The payment has been done successfully.</p>
            <a href="payments.php" class="btn btn-outline-success">Go to Payments</a>
        </div>
   </div>
</div>




<?php include('includes/footer.php') ?>