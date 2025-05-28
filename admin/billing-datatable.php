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
                <a href="billing-report.php" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">Go back</a>
            </div>
        </div>

        <div class="card card-body shadow border-0 table-wrapper table-responsive">
            <div class="d-flex mb-3 gap-2">
                <input type="type" id="min" name="min" class="form-control fmxw-200" placeholder="Minimum Date">
                <input type="type" id="max" name="max" class="form-control fmxw-200 " placeholder="Maximum Date">
                <select id="categoryFilter" class="form-control fmxw-200">
                    <option value="">All Categories</option>
                    <option value="Residential">Residential</option>
                    <option value="Commercial B">Commercial B</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Government">Government</option>
                </select>
                <select id="statusFilter" class="form-control fmxw-200">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>

                <select id="zoneFilter" class="form-control fmxw-200">
                    <option value="">All Zones</option>
                    <option value="1A">1A</option>
                    <option value="1B">1B</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4A">4A</option>
                    <option value="4B">4B</option>
                    <option value="5">5</option>
                    <option value="6A">6A</option>
                    <option value="6B">6B</option>
                    <option value="7A">7A</option>
                    <option value="7B">7B</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <button id="clear_calendar" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">Clear</button>
            </div>
            <table id="billingReportTable" class="table table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Acct No</th>
                        <th>Acct Name</th>
                        <th>Category</th>
                        <th>Status</th>
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

                        if ($billing && count($billing) > 0) {
                            foreach ($billing as $row) {
                                $account_num = htmlspecialchars($row['account_num']);
                                $name = htmlspecialchars($row['account_name']);
                                $billing_num = htmlspecialchars($row['billing_num']);
                                $status = htmlspecialchars($row['client_status'] == 1 ? 'Active' : 'Inactive');
                                switch ($row['account_type']) {
                                    case 1:
                                        $category = 'Residential';
                                        break;
                                    case 2:
                                        $category = 'Commercial B';
                                        break;
                                    case 3:
                                        $category = 'Commercial';
                                        break;
                                    case 4:
                                        $category = 'Government';
                                        break;
                                    default:
                                        $category = 'Unknown';
                                }
                                $zone = htmlspecialchars($row['zone']);
                                $reading_date = htmlspecialchars(date('M d Y', strtotime($row['reading_date'])));
                                $consumption = htmlspecialchars($row['consumption']);
                                $billing_amount = $row['discounted_billing'] ?? null;
                                $discount_type = htmlspecialchars($row['discount_type']);
                                $discount_amount = htmlspecialchars($row['discount_amount']);
                                $arrears = htmlspecialchars($row['arrears']);
                                $surcharge = htmlspecialchars($row['surcharge']);
                                $tax = htmlspecialchars($row['tax']);
                                $wqi_fee = htmlspecialchars($row['wqi_fee']);
                                $wm_fee = htmlspecialchars($row['wm_fee']);
                                $installation_fee = htmlspecialchars($row['installation_fee']);
                                $materials_fee = htmlspecialchars($row['materials_fee']);
                                $total = htmlspecialchars($row['discounted_total']);
                    ?>
                    <tr>
                        <td><?= $account_num; ?></td>
                        <td><?= $name; ?></td>
                        <td><?= $category; ?></td>
                        <td><?= $status; ?></td>
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
                            echo '<tr><td colspan="19">No data found.</td></tr>';
                        }
                    ?>
                    
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7">Total</th>
                        <th></th> <!-- Cons -->
                        <th></th> <!-- Current -->
                        <th></th>
                        <th></th> <!-- Discount -->
                        <th></th> <!-- Arrears -->
                        <th></th> <!-- Tax -->
                        <th></th> <!-- WQI -->
                        <th></th> <!-- WMM -->
                        <th></th> <!-- Installation -->
                        <th></th> <!-- Materials -->
                        <th></th> <!-- Tax -->
                        <th></th> <!-- Total -->
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

    <script>
        $(document).ready(function () {
            let minDate, maxDate;

            
            minDate = new DateTime('#min', {
                format: 'MMM DD YYYY'
            });

            maxDate = new DateTime('#max', {
                format: 'MMM DD YYYY'
            });

            
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    const min = minDate.val();
                    const max = maxDate.val();
                    const dateStr = data[6]; 
                    const date = moment(dateStr, 'MMM DD YYYY'); 

                    if (!min && !max) {
                        return true;
                    }

                    if (min && !max) {
                        return date.isSameOrAfter(moment(min));
                    }

                    if (!min && max) {
                        return date.isSameOrBefore(moment(max));
                    }

                    return date.isBetween(moment(min).subtract(1, 'days'), moment(max).add(1, 'days'), null, '[]');
                }
            );

        
            const table = new DataTable('#billingReportTable', {
                dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'csvHtml5',
                    'excelHtml5',
                    {
                        extend: 'pdfHtml5',
                        title: 'SIWD Billing Report',
                        message: 'San Isidro Water District',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'current'
                            }
                        }
                    },
                    {
                        extend: 'print',
                        title: 'SIWD Billing Report',
                        message: 'San Isidro Water District',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'current'
                            }
                        }
                    }
                ],
                scrollX: true,
                autoWidth: true,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    
                    let intVal = function(i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };
                    let totalColumnIndexes = [7, 8, 10, 11, 12, 13, 14, 15, 16, 17, 18];

                    totalColumnIndexes.forEach(index => {
                        let total = api
                            .column(index)
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        let pageTotal = api
                            .column(index, { page: 'current' })
                            .data()
                            .reduce((a, b) => intVal(a) + intVal(b), 0);

                        
                        $(api.column(index).footer()).html(
                            index === 7 ? pageTotal : pageTotal.toFixed(2));
                    });
                }
                });

            
            $('#categoryFilter').on('change', function () {
                let selectedCategory = $(this).val();

                if (selectedCategory) {
                    table.column(2).search('^' + selectedCategory + '$', true, false).draw();
                } else {
                    table.column(2).search('', true, false).draw();
                }
            });

            
            $('#statusFilter').on('change', function () {
                let selected = $(this).val();
                table.column(3).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            
            $('#zoneFilter').on('change', function () {
                let selected = $(this).val();
                table.column(4).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });

            
            $('#min, #max').on('change', function () {
                table.draw();
            });

            
            $('#clear_calendar').click(function() {
                
                minDate.val(null);
                maxDate.val(null);

                
                $('#categoryFilter').val('');
                $('#statusFilter').val('');
                $('#zoneFilter').val('');

                
                table
                    .column(2).search('', true, false) 
                    .column(3).search('', true, false) 
                    .column(4).search('', true, false) 
                    .column(6).search('').draw();     
            });


        });
    </script>



</body>
</html>