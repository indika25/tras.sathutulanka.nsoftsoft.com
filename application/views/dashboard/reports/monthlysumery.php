<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Rent Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>


    <style>
    .slide-panel {
        position: fixed;
        top: 0;
        right: -320px;
        /* Start hidden */
        width: 300px;
        height: 100%;
        background-color: #fff;
        border-left: 1px solid #ccc;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
        z-index: 1050;
        transition: right 0.3s ease-in-out;
        overflow-y: auto;
    }

    .slide-panel.open {
        right: 0;
    }

    .slide-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: #f1f1f1;
        border-bottom: 1px solid #ddd;
    }

    .slide-panel-header h5 {
        margin: 0;
    }

    .slide-panel-header button {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .slide-panel-body {
        padding: 15px;
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }

    #installedPartsTable th,
    #installedPartsTable td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 6px 10px;
        font-size: 14px;
    }

    .filter-section {
        background-color: #f8f9fa;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
    }

    .text-right {
        text-align: right !important;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid mt-4">


        <!-- 🔍 Filter Section -->
        <div class="card filter-section">
            <h5 class="mb-3">Summary Report</h5>
            <form id="reportFilterForm" class="form-inline flex-wrap">
                <div class="form-group mr-3 mb-2">
                    <label for="vehicleNumber" class="mr-2">Vehicle Number:</label>
                    <select id="vehicle_num" name="vehicle_num" class="form-control">
                        <option value="">- Select All -</option>
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="startDate" class="mr-2">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" class="form-control">
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="endDate" class="mr-2">End Date:</label>
                    <input type="date" id="endDate" name="endDate" class="form-control">
                </div>

                <button type="button" id="filterBtn" class="btn btn-primary mb-2">Search</button>
                <button type="button" id="printBtn" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-print"></i> Print
                </button>

            </form>
        </div>


        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="installedPartsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr id="dynamicHeader"></tr>
                        </thead>

                        <tbody></tbody>

                        <tfoot>
                            <tr id="dynamicFooter"></tr>
                        </tfoot>
                    </table>







                </div>
            </div>
        </div>

    </div>
    <!-- Slide-in Panel -->
    <div id="slidePanel" class="slide-panel">
        <div class="slide-panel-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Hire Details</h5>
            <div class="button-group">
                <button id="printSlidePanel" class="btn btn-sm printSlidePanel">
                    <i class="fa fa-print"></i> Print
                </button>
                <button id="closeSlidePanel" class="btn btn-sm btn-close" aria-label="Close">&times;</button>
            </div>
        </div>

        <div class="slide-panel-body" id="slidePanelContent">
            <section id="hireMaterials">
                <h6 class="section-title">Materials</h6>
                <div class="section-content">Loading materials...</div>
            </section>
            <hr />
            <section id="hireExpenses">
                <h6 class="section-title">Expenses</h6>
                <div class="section-content">Loading expenses...</div>
            </section>
        </div>
    </div>





    <script>
    var $j = jQuery.noConflict();

    $j(document).ready(function() {

        var table;
        loadDateWiseTable();

        function loadDateWiseTable() {

            $j.ajax({
                url: '<?php echo base_url("VehicleParts/getDateWiseDetails"); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    vehicleNum: $j('#vehicle_num').val(),
                    startDate: $j('#startDate').val(),
                    endDate: $j('#endDate').val()
                },
                success: function(response) {

                    // ---------- NO DATA ----------
                    if (!response.data || response.data.length === 0) {

                        Swal.fire({
                            icon: 'info',
                            title: 'No data found',
                            text: 'No rental records found for the selected filters.'
                        });

                        if ($j.fn.DataTable.isDataTable('#installedPartsTable')) {
                            $j('#installedPartsTable').DataTable().clear().destroy();
                        }

                        $j('#dynamicHeader').empty();
                        $j('#dynamicFooter').empty();
                        $j('#installedPartsTable tbody').empty();
                        return;
                    }

                    console.log(response.data);

                    // ---------- 1️⃣ COLLECT ALL EXPENSE NAMES ----------
                    let expenseNames = [];

                    response.data.forEach(row => {

                        if (Array.isArray(row.expenses)) {
                            row.expenses.forEach(exp => {
                                if (exp.name && !expenseNames.includes(exp.name)) {
                                    expenseNames.push(exp.name);
                                }
                            });
                        } else if (typeof row.expenses === 'object' && row.expenses !==
                            null) {
                            Object.keys(row.expenses).forEach(name => {
                                if (!expenseNames.includes(name)) {
                                    expenseNames.push(name);
                                }
                            });
                        }
                    });

                    // ---------- 2️⃣ NORMALIZE EXPENSES (CRITICAL FIX) ----------
                    response.data.forEach(row => {

                        let expMap = {};
                        expenseNames.forEach(name => expMap[name] = 0);

                        if (Array.isArray(row.expenses)) {
                            row.expenses.forEach(exp => {
                                if (exp.name) {
                                    expMap[exp.name] = parseFloat(exp.amount) || 0;
                                }
                            });
                        } else if (typeof row.expenses === 'object' && row.expenses !==
                            null) {
                            Object.entries(row.expenses).forEach(([name, amount]) => {
                                expMap[name] = parseFloat(amount) || 0;
                            });
                        }

                        row.expenses = expMap; // ✅ ALL rows now have same columns
                    });

                    // ---------- 3️⃣ BUILD HEADER ----------
                    let headerHtml = '<th>Month</th><th>Total Hires</th>';

                    expenseNames.forEach(name => {
                        headerHtml += `<th>${name}</th>`;
                    });

                    headerHtml += `
                <th>Total Hire Amount</th>
                <th>Total Expense Amount</th>
                <th>Profit</th>
            `;

                    $j('#dynamicHeader').html(headerHtml);

                    // ---------- 4️⃣ BUILD COLUMNS ----------
                    let columns = [{
                            data: 'month'
                        },
                        {
                            data: 'total_hires',
                            className: 'text-right'
                        }
                    ];

                    expenseNames.forEach(name => {
                        columns.push({
                            data: null,
                            className: 'text-right',
                            render: d => {
                                let val = parseFloat(d.expenses[name] ?? 0);
                                return val.toLocaleString(undefined, {
                                    minimumFractionDigits: 2
                                });
                            }
                        });
                    });

                    columns.push({
                        data: 'total_hire_amount',
                        className: 'text-right',
                        render: d =>
                            parseFloat(d || 0).toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            })
                    }, {
                        data: null,
                        className: 'text-right',
                        render: d => {
                            let total = Object.values(d.expenses)
                                .reduce((a, b) => a + parseFloat(b || 0), 0);

                            return total.toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                        }
                    }, {
                        data: null,
                        className: 'text-right',
                        render: d => {
                            let hire = parseFloat(d.total_hire_amount || 0);
                            let exp = Object.values(d.expenses)
                                .reduce((a, b) => a + parseFloat(b || 0), 0);

                            return (hire - exp).toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                        }
                    });

                    // ---------- 5️⃣ DESTROY OLD TABLE ----------
                    if ($j.fn.DataTable.isDataTable('#installedPartsTable')) {
                        $j('#installedPartsTable').DataTable().clear().destroy();
                    }

                    // ---------- 6️⃣ INIT DATATABLE ----------
                    $j('#installedPartsTable').DataTable({
                        data: response.data,
                        columns: columns,
                        responsive: true,
                        searching: true,

                        footerCallback: function(row, data) {

                            let footer = $j('#dynamicFooter').empty();

                            let totalHires = 0,
                                totalHire = 0,
                                totalExpense = 0,
                                totalProfit = 0;

                            let expenseTotals = {};
                            expenseNames.forEach(n => expenseTotals[n] = 0);

                            data.forEach(r => {

                                totalHires += parseInt(r.total_hires || 0);
                                totalHire += parseFloat(r.total_hire_amount ||
                                    0);

                                let rowExp = 0;

                                expenseNames.forEach(n => {
                                    let v = parseFloat(r.expenses[n] ??
                                        0);
                                    expenseTotals[n] += v;
                                    rowExp += v;
                                });

                                totalExpense += rowExp;
                                totalProfit += parseFloat(r.total_hire_amount ||
                                    0) - rowExp;
                            });

                            footer.append(`<th>Total</th>`);
                            footer.append(`<th class="text-right">${totalHires}</th>`);

                            expenseNames.forEach(n => {
                                footer.append(
                                    `<th class="text-right">${expenseTotals[n].toLocaleString(undefined,{minimumFractionDigits:2})}</th>`
                                );
                            });

                            footer.append(
                                `<th class="text-right">${totalHire.toLocaleString(undefined,{minimumFractionDigits:2})}</th>`
                            );
                            footer.append(
                                `<th class="text-right">${totalExpense.toLocaleString(undefined,{minimumFractionDigits:2})}</th>`
                            );
                            footer.append(
                                `<th class="text-right">${totalProfit.toLocaleString(undefined,{minimumFractionDigits:2})}</th>`
                            );
                        }
                    });
                }
            });
        }


        // Print functionality
        $j('#printBtn').on('click', function() {
            var tableHtml = $j('#installedPartsTable').clone();

            tableHtml.find('thead th, tbody td').filter(function() {
                return $j(this).css('display') === 'none';
            }).remove();

            var buttonColumns = [];
            tableHtml.find('thead th').each(function(index) {
                if (tableHtml.find('tbody tr td').eq(index).find('button').length > 0 ||
                    $j(this).find('button').length > 0) {
                    buttonColumns.push(index);
                }
            });

            buttonColumns.forEach(function(colIndex) {
                tableHtml.find('thead th').eq(colIndex).remove();
                tableHtml.find('tbody tr').each(function() {
                    $j(this).find('td').eq(colIndex).remove();
                });
            });

           var style = `
    <style>
        @media print {
            @page {
                margin: 20mm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }

            th {
                background-color: #343a40;
                color: #fff;
            }
        }
    </style>`;

            var newWin = window.open('', '', 'width=1200,height=700');
            newWin.document.write('<html><head><title>Summary Report</title>' + style +
                '</head><body>');
            newWin.document.write('<h3>Summary Report</h3>');
            newWin.document.write(tableHtml.prop('outerHTML'));
            newWin.document.write('</body></html>');
            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        });

        // Filter button
        $j('#filterBtn').on('click', function() {
            const vehicle = $j('#vehicle_num').val();
            const start = $j('#startDate').val();
            const end = $j('#endDate').val();

            if (!vehicle && !start && !end) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No filters selected',
                    text: 'Please select at least one filter to search.'
                });
                return;
            }

            loadDateWiseTable();
        });

        // Load vehicle dropdown
        $j.ajax({
            url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.data && Array.isArray(response.data)) {
                    let select = $j('select[name="vehicle_num"]');
                    select.find('option:not(:first)').remove();

                    let addedIds = new Set();
                    $j.each(response.data, function(index, vehicle) {
                        let id = vehicle[0];
                        let vehicleNum = vehicle[4];

                        if (!addedIds.has(id)) {
                            select.append(`<option value="${id}">${vehicleNum}</option>`);
                            addedIds.add(id);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading vehicles:', error);
            }
        });

    });
    </script>




</body>

</html>