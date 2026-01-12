<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Rent Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <!-- Bootstrap & DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ ✅ jQuery UI (REQUIRED for autocomplete) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap, SweetAlert, DataTables -->
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
            <h5 class="mb-3">Vehicle Hire Report</h5>
            <form id="reportFilterForm" class="form-inline flex-wrap">
                <div class="form-group mr-3 mb-2">
                    <label for="vehicleNumber" class="mr-2">Vehicle Number:</label>
                    <select id="vehicle_num" name="vehicle_num" class="form-control">

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

        <!-- 📊 Report Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="installedPartsTable" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>

                                <th>Date</th>
                                <th>Hire No</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Milage (KM)</th>
                                <th>Total Cost</th>
                                <th>Hire Payment</th>
                                <th>Driver Salary</th>
                                <th>Profit</th>
                                <th>Brocker</th>
                                <th>View</th>



                            </tr>
                        </thead>
                        <tfoot>
                            <tr>

                            </tr>
                        </tfoot>
                        <tbody></tbody>
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
        // LOAD TABLE
        var table = $j('#installedPartsTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            searchable: true,
            
            ajax: {
                url: '<?php echo base_url("VehicleParts/getRentedVehicles"); ?>',
                type: 'POST',
                data: function(d) {
                    return {
                        vehicleNum: $j('#vehicle_num').val(),
                        startDate: $j('#startDate').val(),
                        endDate: $j('#endDate').val()
                    };
                },
                dataSrc: function(json) {
                    console.log("Full AJAX response:", json);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error loading rentals',
                        text: 'Please try again later.'
                    });
                }
            },
            columns: [

                // September (custom)
                {
                    data: 'rent_start_date',
                    className: 'text-right'
                }, // Date
                {
                    data: 'HireNo',
                    className: 'text-right'
                },
                {
                    data: 'hire_location',
                    className: 'text-right'
                },
                {
                    data: 'end_location',
                    className: 'text-right'
                },
                {
                    data: 'total_mileage',
                    className: 'text-right'
                }, // Total Mileage
                {
                    data: 'total_expenses',
                    className: 'text-right',
                    render: function(data, type, row) {

                        const rentAmount = parseFloat(row.rent_amount) || 0;
                        console.log('rent amount', rentAmount);

                        const hightwayboker = parseFloat(row.parking_highway_expenses) || 0;
                        console.log('hightwayboker', hightwayboker);

                        const percent = parseFloat(row.salary_percent) || 0;
                        console.log('percent', percent);

                        const netAmount = (rentAmount - hightwayboker) * (percent / 100);
                        console.log('netAmount', rentAmount);

                        let value = parseFloat(data) || 0;
                        value += netAmount;

                        return value.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }

                },
                {
                    data: 'rent_amount',
                    className: 'text-right',
                    render: function(data, type, row) {
                        const value = parseFloat(data) || 0;
                        return value.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                }

                ,
                {
                    data: null,
                    className: 'text-right',
                    render: function(data, type, row) {
                        const rentAmount = parseFloat(row.rent_amount) || 0;
                        const parkingCost = parseFloat(row.parking_highway_expenses) || 0;
                        const percent = parseFloat(row.salary_percent) || 0;

                        let netAmount = (rentAmount - parkingCost) * (percent / 100);
                        return netAmount.toFixed(2);
                    },


                },

                {
                    data: null,
                    className: 'text-right',
                    render: function(data, type, row) {
                        const rentAmount = parseFloat(row.rent_amount) || 0;
                        const expenses = parseFloat(row.total_expenses) || 0;
                        const percent = parseFloat(row.salary_percent) || 0;
                        const parkingCost = parseFloat(row.parking_highway_expenses) || 0;
                        let netAmount = (rentAmount - parkingCost) * (percent / 100);
                        const result = rentAmount - expenses - netAmount;
                        return result.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },

                },


                {
                    data: 'renter_name',
                    className: 'text-right'
                },
                {
                    data: 'HireNo',
                    className: 'text-right',
                    render: function(data, type, row) {
                        return `<button class="view-btn btn btn-primary btn-sm" data-id="${data}">View</button>`;
                    },
                    orderable: false,
                    searchable: false
                },


            ],



        });

        let selectedHireRow = null;
        $j('#installedPartsTable').on('click', '.view-btn', function() {

            const table = $j('#installedPartsTable').DataTable();

            selectedHireRow = table
                .row($j(this).closest('tr'))
                .data();

            const hireNo = selectedHireRow.HireNo;

            $j('#slidePanel').addClass('open');

            $j('#hireMaterials').html('<p>Loading materials...</p>');
            $j('#hireExpenses').html('<p>Loading expenses...</p>');

            $j.ajax({
                url: '<?php echo base_url("VehicleParts/getHireDetails"); ?>',
                type: 'POST',
                data: {
                    hireNo: hireNo
                },
                dataType: 'json',
                success: function(response) {


                    let materials = response.materials || [];
                    let expenses = response.expenses || [];

                    // ✅ HIRE INFO BLOCK
                    let hireInfoHtml = `
                <div class="mb-3">
              
                    <div class="border p-2 bg-light rounded">
                        <div><strong>Hire No:</strong> ${hireNo|| '-'}</div>
                      
                    </div>
                </div>
            `;

                    // ✅ MATERIALS TABLE
                    let totalQty = 0;
                    let materialsHtml = '<h5 class="mt-3">Materials</h5>';
                    if (materials.length > 0) {
                        materialsHtml += `
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Material</th>
                                    <th class="text-end">Qty</th>
                                    <th>Unit</th>
                                    <th>Amount</th>
                                    
                                </tr>
                            </thead>
                            <tbody>`;
                        materials.forEach(item => {
                            const qty = parseFloat(item.Qty) || 0;
                            const amount = parseFloat(item.Amount) || 0;
                            totalQty += amount;
                            materialsHtml += `
                        <tr>
                            <td>${item.Name}</td>
                            <td class="text-end">${qty}</td>
                            <td>${item.Unit}</td>
                            <td>${amount}</td>
                            
                        </tr>`;
                        });
                        materialsHtml += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>                                    
                                    <th></th>
                                    <th></th>                                    
                                    <th class="text-end">${totalQty}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;
                    } else {
                        materialsHtml += '<p>No materials found.</p>';
                    }

                    // ✅ EXPENSES TABLE
                    let totalAmount = 0;
                    let expensesHtml = '<h5 class="mt-4">Expenses</h5>';
                    if (expenses.length > 0) {
                        expensesHtml += `
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Expense Name</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>`;
                        expenses.forEach(exp => {
                            const amount = parseFloat(exp.Amount) || 0;
                            totalAmount += amount;
                            expensesHtml += `
                        <tr>
                            <td>${exp.ExpenseName}</td>
                            <td class="text-end">${amount.toFixed(2)}</td>
                        </tr>`;
                        });
                        expensesHtml += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">${totalAmount.toFixed(2)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>`;
                    } else {
                        expensesHtml += '<p>No expenses found.</p>';
                    }

                    // ✅ Inject into DOM
                    $j('#hireMaterials').html(hireInfoHtml + materialsHtml);
                    $j('#hireExpenses').html(expensesHtml);
                },
                error: function(xhr) {
                    console.error("AJAX Error Response:", xhr.responseText);
                    $j('#hireMaterials').html(
                        '<p class="text-danger">Error loading materials.</p>');
                    $j('#hireExpenses').html(
                        '<p class="text-danger">Error loading expenses.</p>');
                }
            });
        });

        $j('#printSlidePanel').on('click', function() {

            const table = $j('#installedPartsTable').DataTable();

            if (selectedHireRow === null) {
                alert('Please select a hire first');
                return;
            }

            const row = selectedHireRow;

            const rentAmount = parseFloat(row.rent_amount) || 0;
            const totalExpenses = parseFloat(row.total_expenses) || 0;
            const parkingCost = parseFloat(row.parking_highway_expenses) || 0;
            const percent = parseFloat(row.salary_percent) || 0;
            const netAmount = (rentAmount - parkingCost) * (percent / 100);
            const balance = rentAmount - totalExpenses - netAmount;

            // Hire Summary
            const hireSummary = `
        <h4>Hire Summary</h4>
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <th>Hire No</th>
                    <td style="font-weight:bold; font-size:14px;">${row.HireNo}</td>
                    <th>Date</th>
                    <td>${row.rent_start_date}</td>
                </tr>
                <tr>
                    <th>Hire Location</th>
                    <td>${row.hire_location}</td>
                    <th>End Location</th>
                    <td>${row.end_location}</td>
                </tr>
                <tr>
                    <th>Total Mileage</th>
                    <td>${row.total_mileage}</td>
                    <th>Renter</th>
                    <td>${row.renter_name}</td>
                </tr>
            </tbody>
        </table>
    `;

            // Financial Summary (2 columns)
            const financialSummary = `
        <h4>Financial Summary</h4>
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td>Hire Amount</td>
                    <td class="text-end">${rentAmount.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                </tr>
                <tr>
                    <td>Total Expenses</td>
                    <td class="text-end">${totalExpenses.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                </tr>
              
                <tr>
                    <td>Salary (${percent}%)</td>
                    <td class="text-end">${netAmount.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                </tr>
                <tr>
                    <td><strong>Balance</strong></td>
                    <td class="text-end"><strong>${balance.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2})}</strong></td>
                </tr>
            </tbody>
        </table>
    `;

            const materialsContainer = document.getElementById('hireMaterials');
            let materialsHtml =
                '<h4>Materials</h4><table class="table table-bordered table-sm"><thead><tr><th>Material</th><th class="text-end">Qty</th><th>Unit</th><th class="text-end">Amount</th></tr></thead><tbody>';
            materialsContainer.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length >= 4) {
                    const name = tds[0].innerText;
                    const qty = tds[1].innerText;
                    const unit = tds[2].innerText;
                    const amount = parseFloat(tds[3].innerText.replace(/,/g, ''))
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    materialsHtml += `<tr>
                <td>${name}</td>
                <td class="text-end">${qty}</td>
                <td>${unit}</td>
                <td class="text-end">${amount}</td>
            </tr>`;
                }
            });
            materialsHtml += '</tbody></table>';

            // ✅ Expenses in 2-column style
            const expensesContainer = document.getElementById('hireExpenses');
            let expensesHtml = '<h4>Expenses</h4><table class="table table-bordered table-sm"><tbody>';
            expensesContainer.querySelectorAll('tbody tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length >= 2) {
                    const name = tds[0].innerText;
                    const amount = parseFloat(tds[1].innerText.replace(/,/g, ''))
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    expensesHtml +=
                        `<tr><td>${name}</td><td class="text-end">${amount}</td></tr>`;
                }
            });
            expensesHtml += '</tbody></table>';

            // Combine everything
            const printContents = hireSummary + financialSummary + materialsHtml + expensesHtml;

            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
        <html>
        <head>
            <title>Hire Report</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 14px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
                table, th, td { border: 1px solid #000; }
                th, td { padding: 6px; }
                .text-end { text-align: right; }
                h4 { margin: 8px 0; }
            </style>
        </head>
        <body>${printContents}</body>
        </html>
    `;

            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });





        // Close button
        $j('#closeSlidePanel').on('click', function() {
            $j('#slidePanel').removeClass('open');
        });
        $j('#printBtn').on('click', function() {
            // Clone the table
            var tableHtml = $j('#installedPartsTable').clone();

            // Remove hidden columns
            tableHtml.find('thead th, tbody td').filter(function() {
                return $j(this).css('display') === 'none';
            }).remove();

            // Find index(es) of columns that contain buttons
            var buttonColumns = [];
            tableHtml.find('thead th').each(function(index) {
                if (tableHtml.find('tbody tr td').eq(index).find('button').length > 0 ||
                    $j(this).find('button').length > 0) {
                    buttonColumns.push(index);
                }
            });

            // Remove button columns from header and body
            buttonColumns.forEach(function(colIndex) {
                tableHtml.find('thead th').eq(colIndex).remove();
                tableHtml.find('tbody tr').each(function() {
                    $j(this).find('td').eq(colIndex).remove();
                });
            });

            // Print styles
            var style = `
        <style>
            @media print {
                @page { size: A4; margin: 20mm; }
                body { font-family: Arial, sans-serif; font-size: 12pt; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 6px; text-align: left; }
                th { background-color: #343a40; color: #fff; }
                button { display: none !important; }
            }
        </style>`;

            // Open new window for printing
            var newWin = window.open('', '', 'width=900,height=700');
            newWin.document.write('<html><head><title>Print Rent Vehicle Report</title>' + style +
                '</head><body>');
            newWin.document.write('<h3>Rent Vehicle Report</h3>');
            newWin.document.write(tableHtml.prop('outerHTML'));
            newWin.document.write('</body></html>');
            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        });



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

            table.ajax.reload();
        });


        $j.ajax({
            url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                if (response.data && Array.isArray(response.data)) {
                    let select = $j('select[name="vehicle_num"]');
                    select.find('option:not(:first)').remove();

                    let addedIds = new Set(); // To track unique vehicle IDs

                    $j.each(response.data, function(index, vehicle) {
                        let id = vehicle[0]; // Vehicle ID
                        let vehicleNum = vehicle[4]; // Vehicle Number

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