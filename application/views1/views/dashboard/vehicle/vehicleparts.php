<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Parts Management</title>

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
    .ui-autocomplete {
        z-index: 9999 !important;
        background: white;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
    }
     #partsTable th,
    #partsTable td {
        white-space: nowrap;
        vertical-align: middle;
        padding: 4px 8px; /* ↓ Reduced padding here */
        font-size: 14px; /* Optional: also reduces height visually */
    }

/* #partsTable th,
#partsTable td {
    white-space: nowrap;
    vertical-align: middle;
} */

.dt-buttons-container {
    display: flex;
    gap: 4px;
    justify-content: center;
}
</style>



</head>

<body class="bg-light">
<div class="container">
 <div class="card shadow ">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tracked Vehicle Parts</h5>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#vehiclePartModal">
             <i class="fas fa-tools mr-2"></i> Add Vehicle Part
                </button>
                </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="partsTable" class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th style="display:none;">ID</th>   <!-- hidden column -->
                        <th>#</th>
                        <th>Install Date</th>
                        <th>Vehicle Number</th>
                        <th>Part Name</th>
                        <th>Price</th>
                        <th>Condition</th>
                        <th>Mileage(KM)</th>
                        <th>Remarks</th>
                        <th>Part Number</th>
                        <th>Driver</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="vehiclePartModal" tabindex="-1" role="dialog" aria-labelledby="vehiclePartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"><!-- xl for larger form -->
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="vehiclePartModalLabel"><i class="fas fa-tools mr-2"></i> Service Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <?php echo form_open('VehicleParts/save_part', ['id' => 'vehiclePartForm']); ?>
        <input type="hidden" name="id" value="">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Vehicle Number</label>
                <select name="vehicle_num" id="vehicleNum" class="form-control" required>
                    <option value="">-- Select Vehicle --</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Part Install Date</label>
                <input type="date" name="last_checked" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Driver</label>
                <select name="Driver" id="Driver" class="form-control" required>
                    <option value="">-- Select Driver --</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Mileage (KM)</label>
                <input type="text" name="mileage" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Part Name</label>
                <input type="text" name="partname" id="partname" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Part No</label>
                <input type="text" name="partno" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Condition</label>
                <select name="vehicle_condition" id="vehicle_condition" class="form-control" required>
                    <option value="">-- Select Condition --</option>
                    <option value="Brand New">Brand New</option>
                    <option value="Used">Used</option>
                    <option value="Reconditioned">Reconditioned</option>
                    <option value="Replaced">Replaced</option> <!-- Fixed duplicate -->
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label>Price</label>
                <input type="text" name="price" class="form-control" required>
            </div>
            
            <div class="col-md-9 mb-3">
                <label>Remarks</label>
                <input type="text" id="remarks" name="remarks" class="form-control">
            </div>
        </div>
        <?php echo form_close(); ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
       <button id="addnewbtn" type="button" class="btn" style="background-color: orange; color: white;">Clear</button>

        <button type="submit" form="vehiclePartForm" class="btn btn-success">Save Part Info</button>
      </div>
    </div>
  </div>
</div>



<script>
var $j = jQuery.noConflict();
$j(document).ready(function () {

    $j('[data-target="#vehiclePartModal"]').on('click', function () {
        // Reset the form fields
        $j('#vehiclePartForm')[0].reset();

        // Reset select elements (Select2 or regular)
        $j('#vehicleNum, #Driver, #vehicle_condition').val('').trigger('change');

        // Clear hidden inputs
        $j('#vehiclePartModal').find('input[type=hidden]').val('');

        // Clear any error or custom messages
        $j('#vehiclePartModal').find('.error, .custom-message').remove();
    });

$j('#addnewbtn').on('click', function () {
        $j('#vehiclePartForm')[0].reset();

    });

    // Initialize DataTable
    var table = $j('#partsTable').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        searchable :true,
        ajax: {
            url: '<?php echo base_url("VehicleParts/getAllParts2"); ?>',
            type: 'POST',
              data: function (d) {
        d.vehicleNum = $j('#vehicleNum').val(); // reuse modal dropdown
        d.singleDate = $j('#filterSingleDate').val();
        d.fromDate = $j('#filterFromDate').val();
              d.toDate = $j('#filterToDate').val();
              },
            dataSrc: 'data',
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
            { data: 'id', visible: false }, // Hidden ID column
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1; // Row numbering starting from 1
                }
            },
            { data: 'installdate' },
            { data: 'Vehicle_Num' },
            { data: 'name' },
            { data: 'price' },
            { data: 'p_condition' },
            { data: 'mileage' },
            { data: 'remarks' },
            { data: 'part_no' },
            { data: 'first_name' },
            
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
        ],
        columnDefs: [
            { targets: 0, visible: false, searchable: false }, // Hide ID column
            { targets: 1, visible: false }, // Row number column hidden (you may show if needed)
            { targets: 2 },
            { targets: 3, width: "10%" },
            { targets: 4, width: "40%" },
            { targets: 5, width: "60%" },
        ]
    });

    // Load Vehicles for select dropdown
    $j.ajax({
        url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.data && Array.isArray(response.data)) {
            let select = $j('select[name="vehicle_num"]');
             select.find('option:not(:first)').remove();

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

// $j(document).on('change', '#vehicleNum', function () {
//     let vehicleId = $j(this).val();

//     if (vehicleId) {
//         $j.ajax({
//             url: '<?php echo base_url("Vehicle/get_vehicle_details"); ?>',
//             type: 'POST',
//             data: { id: vehicleId },
//             dataType: 'json',
//           success: function (response) {
//     console.log(response);

//     if (response.success && Array.isArray(response.data)) {
//         let driverSelect = $j('#Driver');

//         // Clear existing options except the placeholder
//         driverSelect.find('option:not(:first)').remove();

//         // Loop through each driver and append as an option
//         $j.each(response.data, function(index, driver) {
//             let fullName = `${driver.first_name}`;
//             driverSelect.append(`<option value="${driver.id}">${fullName}</option>`);
//         });
//     } else {
//         alert('No drivers found for selected vehicle.');
//     }
// }

// ,
//             error: function (xhr, status, error) {
//                 console.error('Error fetching vehicle details:', error);
//             }
//         });
//     }
// });


     // Load Vehicles for select dropdown
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


    // Form submission handler
$j('#vehiclePartForm').on('submit', function (e) {
    e.preventDefault();

    let form = $j(this);
    let formData = form.serialize();
    console.log('form data',formData);
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
                    // Cache values of fields that should not be cleared
                    let vehicleNum = $j('#vehicleNum').val();
                    let installDate = $j('input[name="last_checked"]').val();

                    let driver = $j('#Driver').val();
                    let remarks = $j('#remarks').val();
let mileage = $j('input[name="mileage"]').val();
                    
                    // Reset the form
                    form[0].reset();

                    // Restore cached values
                    $j('#vehicleNum').val(vehicleNum).trigger('change');
                    $j('input[name="last_checked"]').val(installDate);
                    $j('#Driver').val(driver).trigger('change');
                    $j('#remarks').val(remarks);
  $j('input[name="mileage"]').val(mileage);
                    // Hide modal
//                     $j('#vehiclePartModal').modal('hide');
// $j('#vehiclePartModal').on('hidden.bs.modal', function () {
//     // Ensure backdrop is removed and body is scrollable
//     $j('body').removeClass('modal-open');
//     $j('.modal-backdrop').remove();
// });

                    // Reload table data
                    table.ajax.reload(null, false);
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
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while submitting the form.'
            });
        }
    });
});

    // Edit button handler
    $j('#partsTable tbody').on('click', '.editPartBtn', function() {
        let partId = $j(this).data('id');
        
        let rowData = table.row($j(this).parents('tr')).data();
        console.log('Hello',rowData);

        // Adjust these field names to your form's inputs as needed
        $j('#vehiclePartForm [name="id"]').val(rowData.id);
        $j('#vehiclePartForm [name="partname"]').val(rowData.name);
        $j('#vehiclePartForm [name="price"]').val(rowData.price);
           let dateOnly = rowData.installdate ? rowData.installdate.split(' ')[0] : '';
        $j('#vehiclePartForm [name="last_checked"]').val(dateOnly);
        $j('#vehiclePartForm [name="vehicle_condition"]').val(rowData.p_condition);
        $j('#vehiclePartForm [name="remarks"]').val(rowData.remarks);

        $j('#vehiclePartForm [name="partno"]').val(rowData.part_no);
        $j('#vehiclePartForm [name="mileage"]').val(rowData.mileage);
        
        $j('#vehiclePartForm [name="vehicle_num"]').val(rowData.Vid);
        $j('#vehiclePartForm [name="Driver"]').val(rowData.Did);

        
        $j('#vehiclePartModal').modal('show');
        
    });

    // Delete button handler
    $j('#partsTable tbody').on('click', '.deletePartBtn', function() {
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
                    url: '<?php echo base_url("VehicleParts/deletePart"); ?>',
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






});
</script>
</div>





</body>
</html>
