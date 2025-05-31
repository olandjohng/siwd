<?php
include('../middleware/billingMiddleware.php');

?>
<html>
<head>
    <title>Customer Ledger Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="assets/css/volt.css">
</head>
<?php
    $client_id = $_GET['id'];

    $query = "SELECT * FROM clients WHERE client_id = $client_id";
    $result = mysqli_query($conn, $query);
    
    if(!$result || mysqli_num_rows($result) === 0) {
        echo "Client not found";
        exit();
    }
    
    $client_data = mysqli_fetch_assoc($result);
    $client_name = htmlspecialchars($client_data['account_name']);
?>
<style>
    .contentt {
    overflow: hidden;
    padding: 0 1rem 0 1rem;
}
</style>
<body>
    <main class="contentt">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <h2>Customer Ledger Card</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="customer-ledger.php" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">Go back</a>
            </div>
        </div>

        <div class="card card-body shadow border-0 table-wrapper table-responsive">
            <div class="mb-3">
                <h6 class="fw-normal">Name: <?= $client_data['account_name']; ?></h6>
                <h6 class="fw-normal">Acct #: <?= $client_data['account_num']; ?></h6>
                <h6 class="fw-normal">Address: <?= $client_data['address'] . ', ' . $client_data['barangay']; ?></h6>
            </div>
            <table id="customerLedgerTable" class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>OB No</th>
                        <th>Prev</th>
                        <th>Pres</th>
                        <th>Cons</th>
                        <th>Current</th>
                        <th>WQI</th>
                        <th>WMM</th>
                        <th>Discount</th>
                        <th>Bal</th>
                        <th>OR No</th>
                        <th>Purpose</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // $data = getCombinedHistory($client_id);

                        // if ($data && count($data) > 0) {
                        //     // Sort the data by date
                        //     usort($data, function($a, $b) {
                        //         $dateA = isset($a['reading_date']) ? strtotime($a['reading_date']) : strtotime($a['payment_date']);
                        //         $dateB = isset($b['reading_date']) ? strtotime($b['reading_date']) : strtotime($b['payment_date']);
                        //         return $dateA - $dateB;
                        //     });

                        $data = getCombinedHistory($client_id);

                        if (is_array($data) && count($data) > 0) {
                            // Sort the $data array
                            usort($data, function ($a, $b) {
                                $dateA = isset($a['reading_date']) 
                                    ? strtotime($a['reading_date']) 
                                    : (isset($a['payment_date']) ? strtotime($a['payment_date']) : 0);

                                $dateB = isset($b['reading_date']) 
                                    ? strtotime($b['reading_date']) 
                                    : (isset($b['payment_date']) ? strtotime($b['payment_date']) : 0);

                                return $dateA <=> $dateB;
                            });


                            foreach ($data as $row) {
                                $or_num = isset($row['or_num']) ? htmlspecialchars($row['or_num']) : '';
                                $billing_num = isset($row['billing_num']) ? htmlspecialchars($row['billing_num']) : '';
                                $account_num = isset($row['account_num']) ? htmlspecialchars($row['account_num']) : '';
                                $name = isset($row['account_name']) ? htmlspecialchars($row['account_name']) : '';

                                $date = isset($row['reading_date']) 
                                    ? htmlspecialchars(date('M d Y', strtotime($row['reading_date']))) 
                                    : (isset($row['payment_date']) ? htmlspecialchars(date('M d Y', strtotime($row['payment_date']))) : '');

                                $purpose = isset($row['purpose']) ? htmlspecialchars($row['purpose']) : '';
                                $previous = isset($row['previous_reading']) ? htmlspecialchars($row['previous_reading']) : '';
                                $present = isset($row['present_reading']) ? htmlspecialchars($row['present_reading']) : '';
                                $consumption = isset($row['consumption']) ? $row['consumption'] : '';
                                $billing_amount = isset($row['billing_amount']) ? $row['billing_amount'] : '';
                                $wqi_fee = isset($row['wqi_fee']) ? $row['wqi_fee'] : '';
                                $wm_fee = isset($row['wm_fee']) ? $row['wm_fee'] : '';
                                $discount_amount = isset($row['discount_amount']) ? $row['discount_amount'] : '';
                                $total = isset($row['discounted_total']) ? $row['discounted_total'] : '';
                                $payment_purpose = isset($row['payment_purpose']) ? htmlspecialchars($row['payment_purpose']) : '';
                                
                                // echo '<pre>'; print_r($row); echo '</pre>';
                                if (
                                    isset($row['payment_type']) && 
                                    $row['payment_type'] === 'payments' &&
                                    isset($row['amount_received'], $row['amount_due']) &&
                                    floatval($row['amount_received']) < floatval($row['amount_due'])
                                ) {
                                    $amount = htmlspecialchars($row['amount_received']);
                                } else {
                                    $amount = isset($row['amount_due']) ? htmlspecialchars($row['amount_due']) : '';
                                }



                    ?>
                    <tr>
                        <td><?= $date; ?></td>
                        <td><?= $billing_num; ?></td>
                        <td><?= $previous; ?></td>
                        <td><?= $present; ?></td>
                        <td><?= $consumption; ?></td>
                        <td><?= $billing_amount; ?></td>
                        <td><?= $wqi_fee; ?></td>
                        <td><?= $wm_fee; ?></td>
                        <td><?= $discount_amount; ?></td>
                        <td><?= $total; ?></td>
                        <td><?= $or_num; ?></td>
                        <td><?= $payment_purpose; ?></td>
                        <td><?= $amount; ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="13">No data found.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

    <script>
        const clientName = '<?= $client_name ?>';
        $(document).ready(function() {
            let table = new DataTable('#customerLedgerTable', {
                dom: 'Brtip', 
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'excelHtml5',
                    {
                        text: 'PDF',
                        extend: 'pdfHtml5',
                        title: clientName,
                        message: 'San Isidro Water District - Customer Ledger',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc['footer'] = function(currentPage, pageCount) {
                                return {
                                    columns: [
                                        'Customer Ledger Report',
                                        {
                                            alignment: 'right',
                                            text: ['page ', { text: currentPage.toString() }, ' of ', { text: pageCount.toString() }]
                                        }
                                    ],
                                    margin: [10, 0]
                                };
                            };

                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) { return 0.5; };
                            objLayout['vLineWidth'] = function(i) { return 0.5; };
                            objLayout['hLineColor'] = function(i) { return '#aaa'; };
                            objLayout['vLineColor'] = function(i) { return '#aaa'; };
                            objLayout['paddingLeft'] = function(i) { return 4; };
                            objLayout['paddingRight'] = function(i) { return 4; };
                            doc.content[1].layout = objLayout;
                        }
                    },
                    {
                        text: 'Print',
                        extend: 'print',
                        title: clientName,
                        message: 'San Isidro Water District - Customer Ledger',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .append(
                                    '<div style="text-align: center; border: 1px solid black; padding: 10px; margin-top: 20px;">' +
                                    '<strong>CERTIFICATION</strong><br>' +
                                    '<br>' +
                                    '<br>' +
                                    '<div style="margin-top: 10px;">' +
                                    'I hereby certify on my official oath that the above is a true statement of all collections and deposits made by me during the period<br>' +
                                    'stated above, for which the official receipts issued by me reflect the amounts shown. I also certify that I have not received any money<br>' +
                                    'from any source without issuing the necessary official receipts in acknowledgment thereof. Collections received by sub-collectors are recorded<br>' +
                                    'above as lump sums opposite their respective collection report numbers. I further certify that the balance appearing in the cash receipts record is accurate.<br>' +
                                    '<br>' +
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">Name and signature of the Collecting Officer</span>' +
                                    '</div>' +
                                    '</div>'
                                );
                            
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    }
                ],
                order: [[0, 'asc']],  // Sort by the first column (Date) in ascending order
                columnDefs: [
                    {
                        targets: 0,  // Target the first column (Date)
                        type: 'date',  // Specify the type as date
                        render: function(data, type, row) {
                            // If type is 'display', format the date to be human-readable, otherwise return the original data for sorting
                            if (type === 'display' || type === 'filter') {
                                return moment(data).format('MMM D, YYYY');  // Adjust format as needed
                            }
                            return data;
                        }
                    }
                ],
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ], // Define length menu options
                layout: {
                    scrollX: true,
                    autoWidth: true
                },
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    // Remove the formatting to get integer data for summation
                    let intVal = function(i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    // Total for each column
                    let totalColumnIndexes = [4, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
                    totalColumnIndexes.forEach(index => {
                        let total = api
                            .column(index)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        let pageTotal = api
                            .column(index, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        // Update footer
                        $(api.column(index).footer()).html(pageTotal.toFixed(2));
                    });
                },
                initComplete: function() {
                    let minDate, maxDate;

                    DataTable.ext.search.push(function(settings, data, dataIndex) {
                        let min = minDate.val();
                        let max = maxDate.val();
                        let date = new Date(data[6]);

                        if (
                            (min === null && max === null) ||
                            (min === null && date <= max) ||
                            (min <= date && max === null) ||
                            (min <= date && date <= max)
                        ) {
                            return true;
                        }
                        return false;
                    });

                    minDate = new DateTime('#min', {
                        format: 'MMMM Do YYYY'
                    });
                    maxDate = new DateTime('#max', {
                        format: 'MMMM Do YYYY'
                    });

                    document.querySelectorAll('#min, #max').forEach((el) => {
                        el.addEventListener('change', () => table.draw());
                    });
                }
            });
        });
    </script>
</body>
</html>