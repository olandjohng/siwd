<?php
include('../middleware/adminMiddleware.php');
?>
<html>
<head>
    <title>Billing Report Table</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" href="assets/css/volt.css">
</head>
<?php
$billing = getBilling();
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
                <h2>Generate Billing Report</h2>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="billing-report.php" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">Go Back</a>
            </div>
        </div>

        <div class="card card-body shadow border-0 table-wrapper table-responsive">
            <div class="d-flex mb-3">
                <input type="type" id="min" name="min" class="form-control fmxw-200" placeholder="Minimum Date">
                <input type="type" id="max" name="max" class="form-control fmxw-200 ms-3" placeholder="Maximum Date">
            </div>
            <!-- <table border="0" cellspacing="5" cellpadding="5">
                <tbody>
                    <tr>
                        <td>Minimum date:</td>
                        <td><input type="type" id="min" name="min"></td>
                    </tr>
                    <tr>
                        <td>Maximum date:</td>
                        <td><input type="type" id="max" name="max"></td>
                    </tr>
                </tbody>
            </table> -->
            <table id="billingReportTable" class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Acct No</th>
                        <th>Account Name</th>
                        <th>Zone</th>
                        <th>Billing No.</th>
                        <th>Date</th>
                        <th>Cons</th>
                        <th>Current</th>
                        <th>Disc Type</th>
                        <th>Discount</th>
                        <th>Arrears</th>
                        <th>10%</th>
                        <th>Tax</th>
                        <th>WQI</th>
                        <th>WMM</th>
                        <th>Installation</th>
                        <th>Materials</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($billing && count($billing) > 0) {
                            foreach($billing as $row){
                                $account_num = $row['account_num'];
                                $name = $row['account_name'];
                                $billing_num = $row['billing_num'];
                                $zone = $row['zone'];
                                $reading_date = date('Y-m-d', strtotime($row['reading_date']));
                                $consumption = $row['consumption'];
                                $billing_amount = $row['discounted_billing'];
                                $discount_type = $row['discount_type'];
                                $discount_amount = $row['discount_amount'];
                                $arrears = $row['arrears'];
                                $surcharge = $row['surcharge'];
                                $tax = $row['tax'];
                                $wqi_fee = $row['wqi_fee'];
                                $wm_fee = $row['wm_fee'];
                                $installation_fee = $row['installation_fee'];
                                $materials_fee = $row['materials_fee'];
                                $total = $row['discounted_total'];
                    ?>
                    <tr>
                        <td><?= $account_num; ?></td>
                        <td><?= $name; ?></td>
                        <td><?= $zone; ?></td>
                        <td><?= $billing_num; ?></td>
                        <td><?= $reading_date; ?></td>
                        <td><?= $consumption; ?></td>
                        <td><?= $billing_amount; ?></td>
                        <td><?= $discount_type; ?></td>
                        <td><?= $discount_amount; ?></td>
                        <td><?= $arrears; ?></td>
                        <td><?= $surcharge; ?></td>
                        <td><?= $tax; ?></td>
                        <td><?= $wqi_fee; ?></td>
                        <td><?= $wm_fee; ?></td>
                        <td><?= $installation_fee; ?></td>
                        <td><?= $materials_fee; ?></td>
                        <td><?= $total; ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">No data found.</td></tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">Total</th>
                        <th></th><!-- Current -->
                        <th></th>
                        <th></th><!-- Discount -->
                        <th></th><!-- Arrears -->
                        <th></th><!-- 10% -->
                        <th></th><!-- tax -->
                        <th></th><!-- WQI -->
                        <th></th><!-- WMM -->
                        <th></th><!-- Installation -->
                        <th></th><!-- Materials -->
                        <th></th><!-- Total -->
                    </tr>
                </tfoot>
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
    <!-- <script>
        let table = new DataTable('#billingReportTable', {
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
                        title: 'San Isidro Water District Billing Report',
                        message: 'San Isidro Water District',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .append(
                                    '<div style="margin-top: 20px; display: flex; justify-content: space-between;">' +
                                    '<div style="margin-bottom: 20px;">' +
                                    'Prepared by:<br>' +
                                    '<br>'+
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">Billing Clerk</span>' +
                                    '</div>' +
                                    '<div>' +
                                    'Checked by:<br>' +
                                    '<br>' +
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">Accounting Processor</span>' +
                                    '</div>' +
                                    '<div>' +
                                    'Approved by:<br>' +
                                    '<br>' +
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">General Manager</span>' +
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
                    let date = new Date(data[4]);

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

    </script> -->

    <script>
        let table = new DataTable('#billingReportTable', {
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
                        exportOptions: {
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
                        title: 'San Isidro Water District Billing Report',
                        message: 'San Isidro Water District',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .append(
                                    '<div style="margin-top: 20px; display: flex; justify-content: space-between;">' +
                                    '<div style="margin-bottom: 20px;">' +
                                    'Prepared by:<br>' +
                                    '<br>'+
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">Billing Clerk</span>' +
                                    '</div>' +
                                    '<div>' +
                                    'Checked by:<br>' +
                                    '<br>' +
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">Accounting Processor</span>' +
                                    '</div>' +
                                    '<div>' +
                                    'Approved by:<br>' +
                                    '<br>' +
                                    '<span style="border-bottom: 1px solid #000; width: 200px; display: inline-block; margin-top: 5px;"></span><br>' +
                                    '<span style="font-size: 8pt; display: inline-block; width: 200px; text-align: center;">General Manager</span>' +
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
            
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();

                // Remove the formatting to get integer data for summation
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };

                // Total for each column
                let totalColumnIndexes = [6, 8, 9, 10, 11, 12, 13, 14, 15, 16];
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

            initComplete: function () {
                let minDate, maxDate;

                DataTable.ext.search.push(function (settings, data, dataIndex) {
                    let min = minDate.val();
                    let max = maxDate.val();
                    let date = new Date(data[4]);

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