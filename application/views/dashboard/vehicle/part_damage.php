<!DOCTYPE html>
<html>
<head>
    <title>Damage Parts Management</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- jQuery (load first!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI (load after jQuery) -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <style>
    .dataTables_wrapper {
        overflow-x: auto;
    }
    .ui-autocomplete {
        z-index: 9999 !important;
        background: white;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
    }
    #partdamageTable th,
    #partdamageTable td {
        white-space: nowrap;
        vertical-align: middle;
    }
    .dt-buttons-container {
        display: flex;
        gap: 4px;
        justify-content: center;
    }
    </style>

    <style>



    #partdamageTable th,
    #partdamageTable td {
        vertical-align: middle;
        padding: 2px 2px;
        
        font-size: 14px;
       
    }

    /* ⬇️ ADD THIS for wrapping column content */
    #partdamageTable td.wrap-text {
        white-space: normal !important;
        word-break: break-word;
        overflow-wrap: break-word;
        max-width: 80px;
    }

    .dt-buttons-container {
        display: flex;
        gap: 4px;
        justify-content: center;
    }
</style>
</head>

<body class="bg-light">
<div class="container-fluid mt-0">

    <!-- 🔧 Add/Track Damage Part -->
   
    <!-- 📋 Tracked Damage Parts List -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Tracked Damage Parts</h5>
    
    <button id="adddmgbtn" type="button" class="btn btn-success">
        <i class="fas fa-tools mr-2"></i> Add Damage Part
    </button>
</div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="partdamageTable" class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Damage Date</th>
                        <th>Vehicle Number</th>
                        <th>Driver</th>
                        <th>Part Name</th>
                        <th>Part Number</th>
                       <th>Remarks</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Damage Part Modal -->
<div class="modal fade" id="damagePartModal" tabindex="-1" role="dialog" aria-labelledby="damagePartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"><!-- xl for wide form -->
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="damagePartModalLabel"><i class="fas fa-tools mr-2"></i> Damage Part Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Start Form -->
        <?php echo form_open('PartsDamage/save_damagepart', ['id' => 'DamagePartForm']); ?>
        <input type="hidden" name="id" value="" class="form-control">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Vehicle Number</label>
                <select name="vehicle_num" id="vehicleNum" class="form-control" required>
                    <option value="">-- Select Vehicle --</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Driver</label>
                <select name="Driver" id="Driver" class="form-control" required>
                    <option value="">-- Select Driver --</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Damage Part Name</label>
                <input type="text" name="partname" id="partname" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Part No</label>
                <input type="text" name="partno" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Damage Date</label>
                <input type="date" name="last_checked" class="form-control" required>
            </div>
            <div class="col-md-9 mb-3">
                <label>Remarks</label>
                <input type="text" name="remarks" class="form-control">
            </div>
        </div>
        <?php echo form_close(); ?>
        <!-- End Form -->
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="DamagePartForm" class="btn btn-dark">Save Damage Part</button>
      </div>
    </div>
  </div>
</div>


</div>


<script>
var $j = jQuery.noConflict();
$j(document).ready(function () {

$j('#adddmgbtn').on('click', function () {
    // Reset the form inside modal
    $j('#DamagePartForm')[0].reset();

    // Clear hidden ID field if editing
    $j('#DamagePartForm [name="id"]').val('');

    // Show the modal
    $j('#damagePartModal').modal('show');
});


 $j("#partname").autocomplete({
    source: function(request, response) {
        $j.ajax({
            url: "<?php echo base_url('VehicleParts/autocomplete_partnames'); ?>",
            type: "GET",
            dataType: "json",
            data: {
                term: request.term
            },
            success: function(data) {
                console.log("Autocomplete response:", data);
                response(data); // should be an array of strings
            },
            error: function(xhr, status, error) {
                console.error("Autocomplete error:", error);
            }
        });
    },
    minLength: 2
});
    var table = $j('#partdamageTable').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        searchable :true,
        ajax: {
            url: '<?php echo base_url("PartsDamage/getAllDamageParts"); ?>',
            type: 'POST',
          
             dataSrc: function(json) {
        console.log("AJAX Response:", json); // Log entire JSON response
        return json.data; // Make sure 'data' key exists
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
            { data: 'first_name' },              
            { data: 'name' },
            { data: 'part_no' },
             { data: 'remarks' },
          
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary editPartBtn" data-id="${row.id}">Edit</button>
                            <button class="btn btn-sm btn-danger deletePartBtn" data-id="${row.id}">Delete</button>
                        </div>
                    `;
                }
            }
        ]
    });


$j.ajax({
        url: '<?php echo base_url("Driver/get_Driver_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Driver list',response);
            if (response.data && Array.isArray(response.data)) {
                let select = $j('select[name="Driver"]');
                select.find('option:not(:first)').remove();

                $j.each(response.data, function (index, vehicle) {
                    let id = vehicle[0];         // Vehicle ID
                    let driver = vehicle[1]; // Vehicle Number
                    select.append(`<option value="${id}">${driver}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading vehicles:', error);
        }
    });
    // Load Vehicle List for Dropdown
    $j.ajax({
        url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.data && Array.isArray(response.data)) {
                let select = $j('select[name="vehicle_num"]');
                select.find('option:not(:first)').remove();

                $j.each(response.data, function (index, vehicle) {
                    let id = vehicle[0];
                    let vehicleNum = vehicle[4];
                    select.append(`<option value="${id}">${vehicleNum}</option>`);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading vehicles:', error);
        }
    });
  
    // Form submit handler
   $j('#DamagePartForm').on('submit', function (e) {
    e.preventDefault();

    let form = $j(this);
    let formData = form.serialize();

    $j.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    form[0].reset();
                    $j('#damagePartModal').modal('hide'); // Proper way to hide
                    
                    table.ajax.reload(null, false); // Refresh table
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: response.message,
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while submitting the form.'
            });
        }
    });
});



    // Edit
    $j('#partdamageTable tbody').on('click', '.editPartBtn', function() {
        let rowData = table.row($j(this).parents('tr')).data();
        let dateOnly = rowData.damagedate ? rowData.damagedate.split(' ')[0] : '';

        $j('#DamagePartForm [name="id"]').val(rowData.id);
        $j('#DamagePartForm [name="partname"]').val(rowData.name);
        $j('#DamagePartForm [name="price"]').val(rowData.price);
        $j('#DamagePartForm [name="last_checked"]').val(dateOnly);
        $j('#DamagePartForm [name="remarks"]').val(rowData.remarks);
        
        $j('#DamagePartForm [name="partno"]').val(rowData.part_no);
     console.log(rowData.Vehicle_Num);
     console.log(rowData.first_name);
     // Set vehicle by display text
$j('#DamagePartForm [name="vehicle_num"] option').filter(function () {
    return $j(this).text().trim() === rowData.Vehicle_Num;
}).prop('selected', true);

// Set driver by display text
$j('#DamagePartForm [name="Driver"] option').filter(function () {
    return $j(this).text().trim() === rowData.first_name;
}).prop('selected', true);

   $j('#damagePartModal').modal('show');
        
    });

    // Delete
    $j('#partdamageTable tbody').on('click', '.deletePartBtn', function() {
        let partId = $j(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $j.ajax({
                    url: '<?php echo base_url("PartsDamage/delete_part"); ?>',
                    type: 'POST',
                    data: { id: partId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Deleted!', response.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'An error occurred while deleting.', 'error');
                    }
                });
            }
        });
    });

});
</script>
</body>
</html>
