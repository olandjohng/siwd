<footer class="bg-white rounded shadow p-5 mb-4 mt-4">
    <div class="row">
        <div class="col-12 col-md-8 col-xl-6 text-lg-start">
            <ul class="list-inline list-group-flush list-group-borderless mb-0">
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="../about-us/about.php">About</a>
                </li>
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="#">Terms and conditions</a>
                </li>
                <li class="list-inline-item px-0 px-sm-2">
                    <a href="#">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</footer>
</main>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<script src="other/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script src="other/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="other/vanillajs-datepicker/dist/js/datepicker.min.js"></script>
<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
<script src="other/apexcharts-bundle/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>




<!--Billing Report Table -->
<script>
    $(document).ready(function() {
        let table = $('#billingReportTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'csvHtml5', 'excelHtml5', {
                text: 'PDF',
                extend: 'pdfHtml5',
                title: 'Report Collection and Deposits',
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
            }, 'print'],
            initComplete: function () {
                let minDate, maxDate;

                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
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
    });
</script>

<!--Collection Report Table -->
<script>
    $(document).ready(function() {
        const table = $('#collectionReportTable').DataTable({
            "pagingType": "simple_numbers",
            "lengthChange": false,
            "order": [],
            "scrollX": true
        });       

        $('#searchInput').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#entriesPerPage').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        $('#startDate, #endDate').on('change', function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                let rowDate = data[4];

                if ((!startDate && !endDate) ||
                    (!startDate && rowDate <= endDate) ||
                    (!endDate && rowDate >= startDate) ||
                    (startDate <= rowDate && rowDate <= endDate)
                ) {
                    return true;
                }

                return false;
            });

            table.draw();
        });


        $('#exportCSV').on('click', function() {
            const headers = ['Acct No.', 'Account Name', 'Zone', 'Billing No', 'Date', 'Cons', 'Current', 'Arrears', '10%', 'Tax', 'WQI', 'WMM', 'Installation', 'Materials', 'Total'];

            let tableData = [];
            $('#collectionReportTable').find('tbody tr').each(function() {
                let rowData = [];
                $(this).find('td').each(function() {
                    rowData.push($(this).text());
                });
                tableData.push(rowData);
            });

            let csvContent = headers.join(',') + '\n';
            tableData.forEach(function(row) {
                csvContent += row.join(',') + '\n';
            });

            let encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);

            let link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', 'collection_report.csv');
            document.body.appendChild(link);

            link.click();

            document.body.removeChild(link);
        });   
    });

</script>

<!--Other Tables-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tables = document.querySelectorAll(".data-table");

        tables.forEach(table => {
            new simpleDatatables.DataTable(table, {
                searchable: true,
                fixedHeight: false,
            })
        })
    })
</script>


<script src="../other/simplebar/dist/simblebar.min.js"></script> 
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<script>
    <?php
        if(isset($_SESSION['message'])) { ?>
            alertify.set('notifier', 'position', 'top-right');
            alertify.success('<?= $_SESSION['message']; ?>');
        <?php
            unset($_SESSION['message']);
        }
    ?>
</script>

<script>
    var consumptionByZone = <?php echo json_encode(getConsumptionByZone()) ?>;    

    var zoneLabels = ['1A', '1B', '2', '3', '4A', '4B', '5', '6A', '6B', '7','7A','7B', '8', '9', '10', '11', '12'];
    const zone_categories = zoneLabels.map(function(zoneLabel) { return 'Z ' + zoneLabel.toUpperCase();})
    console.log(zone_categories)
    var dataByZone = zoneLabels.map(function(zoneLabel) {
        return consumptionByZone[zoneLabel] || 0;
    });
    var optionsWaterConsumptionChart = {
        series: [{
            name: 'Water Consumption',
            data: dataByZone
        }],
        chart: {
            type: 'bar',
            width: "100%",
            height: 260,
            // sparkline: {
            //     enabled: true
            // }
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#31316A',
            }
        },
        dataLabels: {
            enabled: true
        },
        plotOptions: {
            bar: {
                columnWidth: '25%',
                borderRadius: 5,
                radiusOnLastStackedBar: true,
                horizontal: false,
                distributed: false,
                endindShape: 'rounded',
                colors: {
                    backgroundBarColors: ['#F2F4F6', '#F2F4F6', '#F2F4F6', '#F2F4F6'],
                    backgroundBarRadius: 5,
                },
            }
        },
        // labels: zoneLabels,
        xaxis: {
            categories: zone_categories,
            // crosshairs: {
            //     width: 1
            // },
        },
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Inter',
            },
            y: {
                formatter: function (val) {
                    return val
                }
            }
        },
    };

    var waterConsumptionChartEl = document.getElementById('water-consumption-chart');
    if (waterConsumptionChartEl) {
        var waterConsumptionChart = new ApexCharts(waterConsumptionChartEl, optionsWaterConsumptionChart);
        waterConsumptionChart.render();
    }
</script>

<script src="assets/js/custom.js"></script>
</body>

</html>
