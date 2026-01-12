<!DOCTYPE html>
<html>

<head>
    <title>Average Fuel Report</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <style>
    .filter-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
    }

    table th,
    table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    </style>
</head>

<body class="bg-light">

    <div class="container-fluid mt-4">

        <!-- FILTER SECTION -->
        <div class="card filter-section mb-4">
            <h5 class="mb-3">Average Fuel Report</h5>

            <form class="form-inline flex-wrap">

                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">Vehicle</label>
                    <select id="vehicle_num" class="form-control">
                       
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">Start</label>
                    <input type="date" id="startDate" class="form-control">
                </div>

                <div class="form-group mr-3 mb-2">
                    <label class="mr-2">End</label>
                    <input type="date" id="endDate" class="form-control">
                </div>

                <button type="button" id="filterBtn" class="btn btn-primary mb-2 mr-2">
                    Search
                </button>

                <button type="button" id="printBtn" class="btn btn-success mb-2">
                    Print
                </button>

            </form>
        </div>

        <!-- TABLE -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="averageFuelTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Vehicle No</th>
                                <th>Hire No</th>
                                <th>Liter Price</th>
                                <th>Km Per 1L</th>
                                <th>Pumped Amount</th>
                                <th>Pumped Liters</th>
                                <th>Planned Km</th>
                                <th>Gone Km</th>
                                <th>Available Km</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
    var $j = jQuery.noConflict();

    $j(document).ready(function() {

        /* ---------------- LOAD VEHICLES ---------------- */
        $j.getJSON("<?= base_url('VehicleParts/get_vehicles_ajax') ?>", function(res) {
            // First, clear existing options and add the default
            $j('#vehicle_num').empty().append('<option value="">-Select All-</option>');

            if (res.data) {
                res.data.forEach(v => {
                    $j('#vehicle_num').append(
                        `<option value="${v[0]}">${v[4]}</option>`
                    );
                });
            }
        });


        /* ---------------- DATATABLE ---------------- */
        var table = $j('#averageFuelTable').DataTable({
            processing: true,
            ajax: {
                url: "<?= base_url('VehicleParts/averageFuelReport') ?>",
                type: "POST",
                data: function(d) {
                    d.vehicleNum = $j('#vehicle_num').val();
                    d.startDate = $j('#startDate').val();
                    d.endDate = $j('#endDate').val();
                }
            },
            columns: [{
                    data: 'vehicle_number'
                },
                {
                    data: 'HireNo'
                },
                {
                    data: 'liter_price',
                    className: 'text-right',
                    render: function(d) {
                        let val = parseFloat(d || 0).toFixed(2);
                        let color = (val > 0) ? 'green' : 'red';

                        return `<span style="color:${color};font-weight:bold;">${val}</span>`;
                    }
                },
                {
                    data: 'liter_per_km',
                    className: 'text-right',
                    render: function(d) {
                        let val = parseFloat(d || 0).toFixed(2);
                        let color = val > 0 ? '#007bff' : '#999';

                        return `<span style="color:${color};font-weight:600;">${val}</span>`;
                    }
                },

                {
                    data: 'pumped_amount',
                    className: 'text-right',
                    render: d => parseFloat(d || 0).toFixed(2)
                },
                {
                    data: 'pumped_liters',
                    className: 'text-right',
                    render: d => parseFloat(d || 0).toFixed(2)
                },
                {
                    data: 'planned_km',
                    className: 'text-right',
                    render: d => parseFloat(d || 0).toFixed(2)
                },
                {
                    data: 'gone_km',
                    className: 'text-right',
                    render: d => parseFloat(d || 0).toFixed(2)
                },
                {
                    data: 'profit_km',
                    className: 'text-right',
                    render: function(val) {
                        let num = parseFloat(val || 0).toFixed(2);
                        let cls = num >= 0 ? 'text-success' : 'text-danger';
                        return `<strong class="${cls}">${num}</strong>`;
                    }
                }
            ]
        });

        /* ---------------- FILTER BUTTON ---------------- */
        $j('#filterBtn').click(function() {
            table.ajax.reload();
        });

        /* ---------------- PRINT BUTTON ---------------- */
        $j('#printBtn').click(function() {

            var vehicle = $j('#vehicle_num option:selected').text() || 'All Vehicles';
            var start = $j('#startDate').val() || 'All Dates';
            var end = $j('#endDate').val() || 'All Dates';

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

            var printWindow = window.open('', '', 'width=900,height=650');

            printWindow.document.write('<html><head><title>Average Fuel Report</title>');
            printWindow.document.write(style); // ✅ ADD STYLE HERE
            printWindow.document.write('</head><body>');

            printWindow.document.write('<h4>Average Fuel Report</h4>');
            printWindow.document.write(
                `<p><strong>Vehicle:</strong> ${vehicle} | 
         <strong>Date Range:</strong> ${start} to ${end}</p>`
            );

            printWindow.document.write(
                $j('#averageFuelTable').clone()[0].outerHTML
            );

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });


    });
    </script>

</body>

</html>