<?php
include('../middleware/adminMiddleware.php');
?>
<html>
<head>
    <title>Collection Report Table</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="assets/css/volt.css">
</head>
<?php
$payments = getPayment();
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
                <h2>Generate Collection Report</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="collection-report.php" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">Go back</a>
            </div>
        </div>

        <div class="card card-body shadow border-0 table-wrapper table-responsive">
            <div class="d-flex mb-3">
                <input type="type" id="min" name="min" class="form-control fmxw-200" placeholder="Minimum Date">
                <input type="type" id="max" name="max" class="form-control fmxw-200 ms-3" placeholder="Maximum Date">
            </div>
            <table id="collectionReportTable" class="table table-hover align-items-center">
                <thead>
                    <th>OR No.</th>
                    <th>Billing No.</th>
                    <th>Payor</th>
                    <th>Account No.</th>
                    <th>Total</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Purpose</th>
                    <th>Current</th>
                    <th>Arrears</th>
                    <th>10%</th>
                    <th>WQI</th>
                    <th>WMM</th>
                    <th>Installation</th>
                    <th>Materials</th>
                    <th>Tax</th>
                </thead>
                <tbody>
                    <?php
                        if($payments && count($payments) > 0) {
                            foreach($payments as $row){
                                $or_num = htmlspecialchars($row['or_num']);
                                $billing_num = htmlspecialchars($row['billing_num']);
                                $account_num = htmlspecialchars($row['account_num']);
                                $name = htmlspecialchars($row['account_name']);
                                $payment_date = htmlspecialchars(date('M d Y', strtotime($row['payment_date'])));
                                $payment_purpose = htmlspecialchars($row['payment_purpose']);
                                $amount = htmlspecialchars($row['amount']);
                                $payment_method = htmlspecialchars($row['payment_method']);
                                $arrears = htmlspecialchars($row['arrears']);
                                $surcharge = htmlspecialchars($row['surcharge']);
                                $wqi_fee = htmlspecialchars($row['wqi_fee']);
                                $wm_fee = htmlspecialchars($row['wm_fee']);
                                $installation_fee = htmlspecialchars($row['installation_fee']);
                                $materials_fee = htmlspecialchars($row['materials_fee']);
                                $tax = htmlspecialchars($row['tax']);
                    ?>
                    <tr>
                        <td><?= $or_num; ?></td>
                        <td><?= $billing_num; ?></td>
                        <td><?= $name; ?></td>
                        <td><?= $account_num; ?></td>
                        <td><?= $amount; ?></td>
                        <td><?= $payment_method; ?></td>
                        <td><?= $payment_date; ?></td>
                        <td><?= $payment_purpose; ?></td>
                        <?php if ($row['source'] === 'payment') { ?>
                            <td><?= $amount; ?></td>
                            <td><?= $arrears; ?></td>
                            <td><?= $surcharge; ?></td>
                            <td><?= $wqi_fee; ?></td>
                            <td><?= $wm_fee; ?></td>
                            <td><?= $installation_fee; ?></td>
                            <td><?= $materials_fee; ?></td>
                            <td><?= $tax; ?></td>
                        <?php } else { ?>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                        <?php } ?>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="16">No data found.</td></tr>';
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
        let table = new DataTable('#collectionReportTable', {
            layout: {
                scrollX: true,
                autoWidth: true,
                topStart: {
                    buttons: ['copyHtml5', 'csvHtml5', 'excelHtml5', 
                    {
                        text: 'PDF',
                        extend: 'pdfHtml5',
                        title: 'SIWD Billing Report',
                        message: 'San Isidro Water District',
                        orientation: 'landscape',
                        exportOprtions: {
                            columns: ':visible'
                        },
                        customize: function (doc) {
                            doc['footer'] = function (currentPage, pageCount) {
                                return {
                                    columns: [
                                        'Report',
                                        {
                                            alignment: 'right',
                                            text: ['page ', { text: currentPage.toString() }, ' of ', { text: pageCount.toString() }]
                                        }
                                    ],
                                    margin: [10, 0]
                                };
                            };

                            var objLayout = {};
                            objLayout['hLineWidth'] = function (i) { return 0.5; };
                            objLayout['vLineWidth'] = function (i) { return 0.5; };
                            objLayout['hLineColor'] = function (i) { return '#aaa'; };
                            objLayout['vLineColor'] = function (i) { return '#aaa'; };
                            objLayout['paddingLeft'] = function (i) { return 4; };
                            objLayout['paddingRight'] = function (i) { return 4; };
                            doc.content[1].layout = objLayout;
                        }
                    },

                    {
                        text: 'Print',
                        extend: 'print',
                        title: 'Report Collection and Deposits',
                        message: 'San Isidro Water District',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (win) {
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
                    }],
                }
            },
            
            initComplete: function () {
                let minDate, maxDate;

                DataTable.ext.search.push(function (settings, data, dataIndex) {
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

    </script>
</body>
</html>