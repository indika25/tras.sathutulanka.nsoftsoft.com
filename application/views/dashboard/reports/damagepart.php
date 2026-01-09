<!DOCTYPE html>
<html>
<head>
  <title>Installed Parts Report</title>
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
        box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }
  </style>
</head>

<body class="bg-light">
<div class="container-fluid mt-4">

  <!-- 🔍 Filter Section -->
  <div class="card filter-section">
    <h5 class="mb-3">Damage Parts Report</h5>
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
      <th>ID</th>
      <th>Damage Date</th>
      <th>Vehicle Num</th>
      <th>Part Name</th>
      <th>Condition</th>
      <th>Remarks</th>
      <th>Part No</th>
      <th>Driver</th>
   
       </tr>
  </thead>
 

</table>

      </div>
    </div>
  </div>

</div>

<script>
    var $j = jQuery.noConflict();

    $j(document).ready(function () {
      // LOAD TABLE
     var table = $j('#installedPartsTable').DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    searchable: true,
    ajax: {
        url: '<?php echo base_url("PartsDamage/getAllDamageParts2"); ?>',
        type: 'POST',
        data: function(d) {
            // Extend existing DataTables params with your filters
            d.vehicleNum = $j('#vehicle_num').val();
            d.startDate = $j('#startDate').val();
            d.endDate = $j('#endDate').val();

            console.log(d.vehicleNum);
            console.log(d.startDate);
            console.log(d.endDate);
            
            return d;
        },
        dataSrc: function (json) {
            console.log("Full AJAX response:", json);
            return json.data;
        },
        error: function(xhr, error, thrown) {
            console.error('AJAX Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error loading parts',
                text: 'Please try again later.'
            });
        }
    },
    columns: [
        { data: 'id', visible: false },        
        { data: 'damagedate' },
        { data: 'Vehicle_Num' },
        { data: 'name' },
        { data: 'p_condition' },
        { data: 'remarks' },
        { data: 'part_no' },
        { data: 'first_name' },
        
      
        // <-- index 8
    ],
  

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
$j('#printBtn').on('click', function() {
    var tableHtml = $j('#installedPartsTable').clone();

    // Remove any hidden columns if needed
    tableHtml.find('thead th, tbody td').filter(function() {
        return $j(this).css('display') === 'none';
    }).remove();

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

    var newWin = window.open('', '', 'width=900,height=700');
    newWin.document.write('<html><head><title>Print Installed Parts Report</title>' + style + '</head><body>');
    newWin.document.write('<h3>Installed Parts Report</h3>');
    newWin.document.write(tableHtml.prop('outerHTML'));
    newWin.document.write('</body></html>');
    newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
});

  $j.ajax({
    url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        console.log(response);
        if (response.data && Array.isArray(response.data)) {
            let select = $j('select[name="vehicle_num"]');
            
            // Clear existing options and add "-Select All-"
              select.empty().append('<option value="">-Select All-</option>');

            let addedIds = new Set(); // To track unique vehicle IDs

            $j.each(response.data, function (index, vehicle) {
                let id = vehicle[0];         // Vehicle ID
                let vehicleNum = vehicle[4]; // Vehicle Number

                if (!addedIds.has(id)) {
                    select.append(`<option value="${id}">${vehicleNum}</option>`);
                    addedIds.add(id);
                }
            });
        }
    },
    error: function (xhr, status, error) {
        console.error('Error loading vehicles:', error);
    }
});

         















    });
</script>


</body>
</html>
