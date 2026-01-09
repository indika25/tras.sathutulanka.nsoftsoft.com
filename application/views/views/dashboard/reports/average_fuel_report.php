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
        .table td, .table th {
            vertical-align: middle;
            white-space: nowrap;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid mt-4">

    <!-- FILTER -->
    <div class="card filter-section mb-4">
        <h5 class="mb-3">Average Fuel Report</h5>
        <form class="form-inline flex-wrap">
            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Vehicle</label>
                <select id="vehicle_num" class="form-control">
                    <option value="">All Vehicles</option>
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

            <div class="form-group mr-3 mb-2">
                <label class="mr-2">Hire No</label>
                <select id="hire_no" class="form-control">
                    <option value="">All Hires</option>
                </select>
            </div>

            <button type="button" id="filterBtn" class="btn btn-primary mb-2">
                Search
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
                            <th>Vehicle</th>
                            <th>Hire No</th>
                            <th>Km Gone</th>
                            <th>Pumped Fuel (L)</th>
                            <th>Avg (Km/L)</th>
                            <th>Liter Per Km</th>
                            <th>Planned Km</th>
                            <th>Profited Km</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
var $j = jQuery.noConflict();

$j(document).ready(function(){

    // Load vehicles
    $j.getJSON("<?= base_url('VehicleParts/get_vehicles_ajax') ?>", function(res){
        if(res.data){
            res.data.forEach(v => {
                $j('#vehicle_num').append(
                    `<option value="${v[0]}">${v[4]}</option>`
                );
            });
        }
    });

    var table = $j('#averageFuelTable').DataTable({
        processing: true,
        ajax: {
            url: "<?= base_url('VehicleParts/averageFuelReport') ?>",
            type: "POST",
            data: function(d){
                d.vehicleNum = $j('#vehicle_num').val();
                d.startDate  = $j('#startDate').val();
                d.endDate    = $j('#endDate').val();
                d.hireNo     = $j('#hire_no').val();
            }
        },
        columns: [
            { data: 'vehicle_number' },
            { data: 'HireNo' },
            { data: 'km_gone', className:'text-right' },
            { data: 'fuel_liters', className:'text-right' },
            { data: 'average', className:'text-right' },
            { data: 'liter_per_km', className:'text-right' },
            { data: 'planned_km', className:'text-right' },
            {
                data: 'profit',
                className:'text-right',
                render: function(val){
                    let cls = val >= 0 ? 'text-success' : 'text-danger';
                    return `<strong class="${cls}">${val}</strong>`;
                }
            }
        ]
    });

    $j('#filterBtn').click(function(){
        table.ajax.reload();
    });

});
</script>

</body>
</html>
