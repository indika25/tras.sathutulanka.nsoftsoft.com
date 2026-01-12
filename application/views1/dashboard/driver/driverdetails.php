<!DOCTYPE html>
<html>
<head>
    <title>Driver Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>



 <style>
    .dataTables_wrapper {
        overflow-x: auto;
    }
   
     #driverTable th,
    #driverTable td {
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
<!-- Modal for Adding License Type -->
<div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="licenseForm">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="licenseModalLabel">Add License Type</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="license_code">License Code</label>
            <input type="text" class="form-control" name="license_code" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" required></textarea>
          </div>
        </div>
        
       <div class="modal-footer flex-column">
  <button type="submit" class="btn btn-primary w-100 mb-2">Save</button>
  <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">Cancel</button>

  <!-- Scrollable Table Container -->
  <div style="max-height: 300px; overflow-y: auto;  margin-top: 20px; padding: 10px; border: 1px solid #dee2e6; border-radius: 5px;">
    <h6 class="mb-3">License Types List</h6>
    <table id="licenseTable" class="table table-bordered table-striped mb-0">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>License Code</th>
          <th>Description</th>
          <th>Options</th>
          
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>


      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="drivertypeModal" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="driverTypeForm">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="licenseModalLabel">Add Driver Type</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="ldriver_code">Driver Type Code</label>
            <input type="text" class="form-control" name="ldriver_code" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" required></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
         <div style="max-height: 300px; overflow-y: auto;  margin-top: 20px; padding: 10px; border: 1px solid #dee2e6; border-radius: 5px;">
    <h6 class="mb-3">Driver Types List</h6>
    <table id="drivertypeTable" class="table table-bordered table-striped mb-0">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Type Code</th>
          <th>Description</th>
          <th>Options</th>
          
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
      </div>
    </form>
  </div>
</div>
  




<div class="container-fluid mt-1">
    <div class="card shadow mb-1">
       

    </div>
    <div class="card shadow">
       <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Driver List</h5>

    <button class="btn btn-success" data-toggle="modal" data-target="#driverModal">
       <i class="fas fa-plus mr-2"></i> Add Driver
    </button>
</div>

        <div class="card-body">
    <div class="table-responsive">
        <table id="driverTable" class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Salary Percent</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

    </div>
</div>

<!-- Driver Details Modal -->
<div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-labelledby="driverModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document"> <!-- Use modal-xl for wider modals -->
    <div class="modal-content">
      
      <!-- Modal Header -->
      <div class="modal-header bg-dark text-white">
        <h3 class="modal-title" id="driverModalLabel" style="display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-edit" style="color: #fff; font-size: 24px;"></i> Driver Details
          
        

        </h3>
        
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>



      <!-- Modal Body -->
      <div class="modal-body">
        <?php 
          $action = isset($driver) ? 'Driver/SaveDriver' : 'Driver/SaveDriver';
          echo form_open($action, ['id'=>'driverForm', 'enctype'=>'multipart/form-data']); 
        ?>

        <input type="hidden" name="driver_id" value="<?= isset($driver) ? $driver->driver_id : '' ?>">

        <div class="row">
          <!-- FIRST NAME -->
          <div class="col-md-4 mb-3">
              <label>First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control" required value="<?= isset($driver) ? $driver->first_name : '' ?>">
          </div>

          <!-- LAST NAME -->
          <div class="col-md-4 mb-3">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control" value="<?= isset($driver) ? $driver->last_name : '' ?>">
          </div>

          <!-- DOB -->
          <div class="col-md-4 mb-3">
              <label>Date of Birth</label>
              <input type="date" name="date_of_birth" class="form-control" value="<?= isset($driver) ? $driver->date_of_birth : '' ?>">
          </div>

          <!-- GENDER -->
          <div class="col-md-4 mb-3">
              <label>Gender</label>
              <select name="gender" class="form-control">
                  <option value="">-- Select Gender --</option>
                  <option value="Male" <?= (isset($driver) && $driver->gender == 'Male') ? 'selected' : '' ?>>Male</option>
                  <option value="Female" <?= (isset($driver) && $driver->gender == 'Female') ? 'selected' : '' ?>>Female</option>
                  <option value="Other" <?= (isset($driver) && $driver->gender == 'Other') ? 'selected' : '' ?>>Other</option>
              </select>
          </div>

          <!-- LICENSE NUMBER -->
          <div class="col-md-4 mb-3">
              <label>License Number <span class="text-danger">*</span></label>
              <input type="text" name="license_number" class="form-control" required value="<?= isset($driver) ? $driver->license_number : '' ?>">
          </div>

          <!-- LICENSE ISSUE DATE -->
          <div class="col-md-4 mb-3">
              <label>License Issue Date</label>
              <input type="date" name="license_issue_date" class="form-control" value="<?= isset($driver) ? $driver->license_issue_date : '' ?>">
          </div>

          <!-- LICENSE EXPIRY DATE -->
          <div class="col-md-4 mb-3">
              <label>License Expiry Date</label>
              <input type="date" name="license_expiry_date" class="form-control" value="<?= isset($driver) ? $driver->license_expiry_date : '' ?>">
          </div>

          <!-- LICENSE TYPE -->
          <div class="col-md-4 mb-3">
              <label>License Type</label>
              <div class="d-flex">
                  <select name="assigned_lisense_id" class="form-control">
                      <option value="">-- Select License Types --</option>
                      <?php foreach ($vehicles as $v): ?>
                          <option value="<?= $v->id ?>" <?= (isset($driver) && $driver->assigned_vehicle_id == $v->id) ? 'selected' : '' ?>>
                              <?= $v->Vehicle_Name ?> (<?= $v->Vehicle_Num ?>)
                          </option>
                      <?php endforeach; ?>
                  </select>
                  <button id="lisense_btn" type="button" class="btn btn-warning fw-bold ml-2" data-toggle="modal" data-target="#licenseModal">+</button>
              </div>
          </div>

          <!-- DRIVER TYPE -->
          <div class="col-md-4 mb-3">
              <label>Driver Type</label>
              <div class="d-flex">
                  <select name="assigned_driver_id" class="form-control">
                      <option value="">-- Select Driver Types --</option>
                      <!-- Populate dynamically if needed -->
                  </select>
                  <button id="drivertype_btn" type="button" class="btn btn-warning fw-bold ml-2" data-toggle="modal" data-target="#drivertypeModal">+</button>
              </div>
          </div>

          <!-- CONTACT NUMBER -->
          <div class="col-md-4 mb-3">
              <label>Contact Number</label>
              <input type="text" name="contact_number" class="form-control" value="<?= isset($driver) ? $driver->contact_number : '' ?>">
          </div>

          <!-- EMAIL -->
          <div class="col-md-4 mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="<?= isset($driver) ? $driver->email : '' ?>">
          </div>

          <!-- NATIONAL / PASSPORT ID -->
          <div class="col-md-4 mb-3">
              <label>National / Passport ID</label>
              <input type="text" name="national_id" class="form-control" value="<?= isset($driver) ? $driver->national_id : '' ?>">
          </div>

          <!-- ADDRESS -->
          <div class="col-md-6 mb-3">
              <label>Address</label>
              <textarea name="address" class="form-control"><?= isset($driver) ? $driver->address : '' ?></textarea>
          </div>

          <!-- STATUS -->
          <div class="col-md-4 mb-3">
              <label>Status</label>
              <select name="status" class="form-control" required>
                  <option value="Available" <?= (isset($driver) && $driver->status=='Available')?'selected':'' ?>>Available</option>
                  <option value="On Trip" <?= (isset($driver) && $driver->status=='On Trip')?'selected':'' ?>>On Trip</option>
                  <option value="Inactive" <?= (isset($driver) && $driver->status=='Inactive')?'selected':'' ?>>Inactive</option>
                  <option value="Suspended" <?= (isset($driver) && $driver->status=='Suspended')?'selected':'' ?>>Suspended</option>
              </select>
          </div>

          <!-- JOINING DATE -->
          <div class="col-md-4 mb-3">
              <label>Joining Date</label>
              <input type="date" name="joining_date" class="form-control" value="<?= isset($driver) ? $driver->joining_date : '' ?>">
          </div>

          <!-- SALARY RATE -->
          <div class="col-md-4 mb-3">
              <label>Salary ( % )</label>
              <input type="number" name="salary_rate" class="form-control" value="<?= isset($driver) ? $driver->salary_rate : '' ?>">
          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button id="driverSubmitBtn" type="submit" class="btn btn-dark">Save Driver</button>
        </div>

        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>




<script>
var $j = jQuery.noConflict();
$j(document).ready(function() {

loadLicenseTable();
refreshDriverTypeTable();

    if (typeof $j.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded.');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'DataTables library failed to load. Please check your internet connection or CDN.'
        });
        return;
    }

$j('#driverTable').DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    searchable :true,
     // keep true for automatic sizing on other columns
    ajax: {
        url: '<?php echo base_url("Driver/get_Driver_ajax"); ?>',
        type: 'POST',
        dataSrc: function(json) {
            console.log('Driver Data:', json);
            return json.data;
        },
        error: function(xhr, error, thrown) {
            console.error('AJAX Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error loading drivers',
                text: 'Please try again later.'
            });
        }
    },

    
    columns: [
        { data: 0, visible:true }, // ID
        { data: 1}, 
        { data: 10 }, 
        { data: 16 }, 
        { data: 14 }, 
        
              {
            data: null,
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary editDriverBtn" data-id="${row[0]}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteDriverBtn" data-id="${row[0]}">Delete</button>
                    </div>
                `;
            }
        }
    ],
   
});




    var successMessage = <?php echo json_encode($this->session->flashdata('success')); ?>;
    if (typeof Swal !== 'undefined' && successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: successMessage
        });
    }

//set vehicles
// $.ajax({
//     url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
//     type: 'POST',
//     success: function(response) {
//     console.log('Vehicle Data:', response);
//     let select = $('select[name="assigned_vehicle_id"]');
//     select.empty();
//     select.append('<option value="">-- No Vehicle Assigned --</option>');

//     let addedIds = new Set(); // To track unique vehicle IDs

//     response.data.forEach(function(v) {
//         let id = v[0];         // idvehicledetails
//         let vehicleNum = v[4]; // vehicle number

//         if (!addedIds.has(id)) {
//             select.append('<option value="' + id + '">' + vehicleNum + '</option>');
//             addedIds.add(id);
//         }
//     });
// }
// ,
//     error: function(xhr, status, error) {
//         console.error('Failed to fetch vehicles:', error);
//         Swal.fire('Error', 'Could not load vehicle list', 'error');
//     }
// });
//set driver types
$j.ajax({
    url: '<?php echo base_url("Driver/getDriver_types"); ?>',
    type: 'POST',
    dataType: 'json',
    success: function(response) {
        console.log('Driver Details:', response);

        let select = $j('select[name="assigned_driver_id"]'); // target correct <select>

        select.empty();
        select.append('<option value="">-- Select Driver Type --</option>');

        // Access driver_types array
        response.driver_types.forEach(function(type) {
            select.append('<option value="' + type.driver_type_code + '">' +
                type.driver_type_code + ' - ' + type.description +
                '</option>');
        });
    },
    error: function(xhr, status, error) {
        console.error('Failed to fetch driver types:', error);
        Swal.fire('Error', 'Could not load driver types', 'error');
    }
});

//set license types
$j.ajax({
    url: '<?php echo base_url("Driver/getLisence_types"); ?>',
    type: 'POST',
    dataType: 'json',
    success: function(response) {
        console.log('lisense Details:', response);

        let select = $j('select[name="assigned_lisense_id"]'); // target correct <select>

        select.empty();
        select.append('<option value="">-- Select License Type --</option>');

        // Access driver_types array
        response.license.forEach(function(type) {
            select.append('<option value="' + type.id + '">' +
                type.license_code + ' - ' + type.description +
                '</option>');
        });
    },
    error: function(xhr, status, error) {
        console.error('Failed to fetch driver types:', error);
        Swal.fire('Error', 'Could not load driver types', 'error');
    }
});
//edit button
// Edit button click handler
$j('#driverTable tbody').on('click', '.editDriverBtn', function() {
    var driverId = $(this).data('id');
    
    // Fetch driver data via AJAX or populate the form with existing data
    // Here, example using AJAX:
    $j.ajax({
        url: '<?php echo base_url("Driver/get_driver_by_id"); ?>', // Create this method in your controller
        type: 'POST',
        data: { driver_id: driverId },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                var driver = response.data;
console.log(driver,'clicked driver');
                // Populate form fields with driver data
                $j('input[name="driver_id"]').val(driver.id);
                $j('input[name="first_name"]').val(driver.first_name);
                $j('input[name="last_name"]').val(driver.last_name);
                $j('input[name="date_of_birth"]').val(driver.date_of_birth);
                $j('select[name="gender"]').val(driver.gender);
                $j('input[name="license_number"]').val(driver.license_number);
                $j('input[name="license_issue_date"]').val(driver.license_issue_date);
                $j('input[name="license_expiry_date"]').val(driver.license_expiry_date);
                $j('select[name="assigned_lisense_id"]').val(driver.license_type);
                $j('select[name="assigned_driver_id"]').val(driver.driver_type);
                $j('input[name="contact_number"]').val(driver.contact_number);
                $j('input[name="email"]').val(driver.email);
                $j('input[name="national_id"]').val(driver.national_id);
                $j('textarea[name="address"]').val(driver.address);
                $j('select[name="status"]').val(driver.status);
                $j('select[name="assigned_vehicle_id"]').val(driver.assigned_vehicle_id);
                $j('input[name="joining_date"]').val(driver.joining_date);
                $j('input[name="salary_rate"]').val(driver.salary_percent);

                // Scroll to the form or open modal if you have one
                window.scrollTo({top: 0, behavior: 'smooth'});
                $j('#driverModal').modal('show');
            } else {
                Swal.fire('Error', 'Failed to load driver data.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Server error occurred.', 'error');
        }
    });
});


//delete button
$j('#driverTable tbody').on('click', '.deleteDriverBtn', function() {
    var driverId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $j.ajax({
                url: '<?php echo base_url("Driver/delete_driver"); ?>', // Create this method in your controller
                type: 'POST',
                data: { driver_id: driverId },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'Driver has been deleted.',
                            'success'
                        );
                        $j('#driverTable').DataTable().ajax.reload(null, false); // Reload table without resetting pagination
                    } else {
                        Swal.fire('Error', response.message || 'Failed to delete driver.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Server error occurred.', 'error');
                }
            });
        }
    });
});




let editingLicenseId = null;
//insert license type
  $('#licenseForm').on('submit', function (e) {
    e.preventDefault();

    const url = editingLicenseId
        ? '<?php echo base_url("Driver/update_license_type"); ?>'
        : '<?php echo base_url("Driver/save_license_type"); ?>';

    const formData = $(this).serialize() + (editingLicenseId ? `&id=${editingLicenseId}` : '');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire('Success', response.message, 'success');
                $('#licenseForm')[0].reset();
                $('#licenseModal').modal('hide');
                 setTimeout(() => {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }, 500);

                editingLicenseId = null;
                refreshLicenseDropdown();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function () {
            Swal.fire('Server Error', 'Failed to save/update license type.', 'error');
        }
    });
});


let editingDriverTypeId = null;

$j(document).on('submit', '#driverForm', function (e) {
    e.preventDefault();

    var form = $j(this);
    var url = form.attr('action');

  $j.ajax({
    url: url,
    method: 'POST',
    data: form.serialize(),
    dataType: 'json',
    success: function (response) {
        if (response.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Driver Saved',
                text: response.message || 'Driver has been added successfully.'
            });

            form[0].reset();
            $j('#driverTable').DataTable().ajax.reload(null, false);
             $j('#driverModal').modal('hide');
 setTimeout(function () {
        $j('body').removeClass('modal-open');
        $j('.modal-backdrop').remove();
    }, 500);
        } else {
            let title = 'Error';
            let icon = 'error';

            // Customize title/message based on error type
            if (response.type === 'duplicate') {
                title = 'Duplicate Entry';
            } else if (response.type === 'db') {
                title = 'Database Error';
            }

            Swal.fire({
                icon: icon,
                title: title,
                text: response.message || 'Something went wrong.'
            });
        }
    },
    error: function (xhr, status, error) {
        console.error(xhr.responseText);
        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Failed to save driver.'
        });
    }
});

});

$('#driverTypeForm').on('submit', function (e) {
    e.preventDefault();

    const url = editingDriverTypeId
        ? '<?php echo base_url("Driver/update_driver_type"); ?>'
        : '<?php echo base_url("Driver/save_driver_type"); ?>';

    const formData = $(this).serialize() + (editingDriverTypeId ? `&id=${editingDriverTypeId}` : '');
    console.log(formData);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire('Success', response.message, 'success');
                $('#driverTypeForm')[0].reset();
                $('#drivertypeModal').modal('hide');

                // Clean up modal state
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');

                editingDriverTypeId = null;
                refreshDriverTypeTable();
                refreshDriverTypeDropdown();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function () {
            Swal.fire('Server Error', 'Failed to save/update driver type.', 'error');
        }
    });
});


function refreshLicenseDropdown() {
        $.ajax({
            url: '<?php echo base_url("Driver/getLisence_types"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                const select = $('select[name="assigned_lisense_id"]');
                select.empty().append('<option value="">-- Select License Type --</option>');
                response.license.forEach(function (type) {
                    select.append(`<option value="${type.id}">${type.license_code} - ${type.description}</option>`);
                });
            },
            error: function () {
                console.error('Failed to refresh license types');
            }
        });
    }
function refreshDriverTypeDropdown() {
    $.ajax({
        url: '<?php echo base_url("Driver/get_driver_types_ajax"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            const select = $('select[name="assigned_driver_id"]');
            select.empty().append('<option value="">-- Select Driver Type --</option>');

            response.data.forEach(function (type) {
                select.append(
                    `<option value="${type.id}">${type.driver_type_code} - ${type.description}</option>`
                );
            });
        },
        error: function () {
            console.error('Failed to refresh driver type dropdown.');
        }
    });
}


    function loadLicenseTable() {
    $.ajax({
        url: '<?php echo base_url("Driver/get_license_types_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var tbody = $('#licenseTable tbody');
            tbody.empty();

            response.data.forEach(function (item) {
    tbody.append(`
        <tr>
            <td>${item.id}</td>
            <td>${item.license_code}</td>
            <td>${item.description}</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-primary editLicenseBtn" data-id="${item.id}" data-code="${item.license_code}" data-description="${item.description}">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger deleteLicenseBtn" data-id="${item.id}">Delete</button>
                </div>
            </td>
        </tr>
    `);
});

        },
        error: function () {
            console.error('Failed to load license types');
        }
    });
}


// Handle Edit Button Click
$('#licenseTable').on('click', '.editLicenseBtn', function () {
    const id = $(this).data('id');
    const code = $(this).data('code');
    const description = $(this).data('description');

    editingLicenseId = id;

    // Populate the form fields
    $('#licenseForm [name="license_code"]').val(code);
    $('#licenseForm [name="description"]').val(description);

    // Optional: scroll to form
    $('html, body').animate({ scrollTop: $('#licenseModal').offset().top }, 300);
     refreshLicenseDropdown();
                        refreshLicenseTable();
});

// Handle Delete Button Click
$('#licenseTable').on('click', '.deleteLicenseBtn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently delete the license type.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url("Driver/delete_license_type"); ?>',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success');
                        refreshLicenseDropdown();
                        refreshLicenseTable();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Failed to delete license type.', 'error');
                }
            });
        }
    });
});


function refreshLicenseTable() {
    $.ajax({
        url: '<?php echo base_url("Driver/get_license_types_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            const tbody = $('#licenseTable tbody');
            tbody.empty();

            response.data.forEach(function (item) {
                tbody.append(`
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.license_code}</td>
                        <td>${item.description}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary editLicenseBtn" data-id="${item.id}" data-code="${item.license_code}" data-description="${item.description}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger deleteLicenseBtn" data-id="${item.id}">Delete</button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        },
        error: function () {
            console.error('Failed to load license types');
        }
    });
}
// Variable to track editing mode

// Delegate click event for edit button
$(document).on('click', '.editDriverTypeBtn', function () {
    const id = $(this).data('id');
    const code = $(this).data('code');
    const description = $(this).data('description');

    // Set the form values
   // This must match your input `name` or `id`
$('input[name="ldriver_code"]').val(code);
$('textarea[name="description"]').val(description);


    // Set editing ID
    editingDriverTypeId = id;

    // Open modal
    $('#drivertypeModal').modal('show');
});
$(document).on('click', '.deleteDriverTypeBtn', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform AJAX request to delete
            $.ajax({
                url: '<?php echo base_url("Driver/delete_driver_type"); ?>',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Deleted!', response.message, 'success');
                        refreshDriverTypeTable();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Server Error', 'Failed to delete driver type.', 'error');
                }
            });
        }
    });
});




function refreshDriverTypeTable() {
    $.ajax({
        url: '<?php echo base_url("Driver/get_driver_types_ajax"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var tbody = $('#drivertypeTable tbody');
            tbody.empty();

           response.data.forEach(function (item) {
    tbody.append(`
        <tr>
            <td>${item.id}</td>
            <td>${item.driver_type_code}</td>
            <td>${item.description}</td>
            <td>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary editDriverTypeBtn"
                        style="width: 80px;"
                        data-id="${item.id}"
                        data-code="${item.driver_type_code}"
                        data-description="${item.description}">
                        Edit
                    </button>
                    <button type="button" class="btn btn-danger deleteDriverTypeBtn"
                        style="width: 80px;"
                        data-id="${item.id}">
                        Delete
                    </button>
                </div>
            </td>
        </tr>
    `);
});


        },
        error: function () {
            console.error('Failed to load driver types');
        }
    });
}





});

</script>
</body>
</html>