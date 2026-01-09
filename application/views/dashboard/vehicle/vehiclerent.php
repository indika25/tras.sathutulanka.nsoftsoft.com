<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Rent Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <!-- jQuery core -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI for autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets/js/showToast.js') ?>"></script>

    <style>
    #rentVehicleModal .modal-dialog {
        max-width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #rentVehicleModal .modal-content {
        height: 100%;
        border: 0;
        border-radius: 0;
    }

    #materialUnit {
        width: 100% !important;
        /* full width of td */
        max-width: 150px;
        /* max width you want */
    }

    /* Make Qty input wider */
    #materialQty {
        width: 100% !important;
        max-width: 100px;
        /* adjust as needed */
    }

    #partsTable th,
    #partsTable td {
        vertical-align: middle;
        padding: 2px 2px;

        font-size: 14px;

    }

    .dataTables_filter input {
        width: 120px !important;
        /* Adjust the width as needed */
    }

    /* ⬇️ ADD THIS for wrapping column content */
    #partsTable td.wrap-text {
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

    .ui-autocomplete {
        z-index: 3000 !important;
        /* ensure it shows above modals */
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid mt-3 px-2">



        <!-- 📋 Tracked Parts List -->
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hire Details</h5>

                <div class="d-flex gap-2">
                    <button id="addExpenses" type="button" class="btn text-white mr-2"
                        style="background-color: #e74c3c;">
                        <i class="fas fa-money-bill-wave"></i> Add Expenses
                    </button>

                    <button id="addoils" type="button" class="btn text-dark mr-2" style="background-color: yellow;">
                        <i class="fas fa-oil-can"></i> Add Fuel Price
                    </button>
                    <!-- <button id="addlocations" type="button" class="btn text-white mr-2" style="background-color: orange;">
    <i class="fas fa-plus"></i> Add Locations
</button> -->

                    <button id="addrentbtn" type="button" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Hire
                    </button>
                </div>
            </div>


            <div class="card-body">
                <div class="table-responsive">
                    <table id="partsTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Hire No</th>
                                <th>Vehicle Num</th>
                                <th>Renter Name</th>
                                <th>Agree No</th>
                                <th>Start Date</th>
                                <th>Hire Distance (Km)</th>
                                <th>Start Mileage (Km)</th>
                                <th>Fuel Price Per 1L</th>
                                <th>Amount</th>
                                <th>Driver</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>



        <!-- 🚗 Vehicle Rent Modal -->
        <div class="modal fade" id="rentVehicleModal" tabindex="-1" role="dialog"
            aria-labelledby="rentVehicleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <!-- modal-xl for wider form -->
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="rentVehicleModalLabel">
                            <i class="fas fa-tools mr-2"></i> Vehicle Hire Service
                        </h5>
                        <button type="button" class="close text-white btn-danger" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- 🚗 Vehicle Rent Form -->
                    <div class="modal-body">
                        <?php echo form_open('RentVehicle/save_rent', ['id' => 'vehicleRentForm']); ?>
                        <input type="hidden" name="id" value="">
                        <div class="row">


                            <div class="col-md-3 mb-3">
                                <label>Hire No</label>
                                <input type="text" readonly id="hireno" name="hireno" class="form-control" required>

                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Renter Name</label>
                                <select name="render_name" id="render_name" class="form-control">
                                    <option value="">-- Select Renter --</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Agreement No</label>
                                <input type="text" name="agreement_no" class="form-control">
                            </div>

                            <!-- VEHICLE DETAILS -->
                            <div class="col-md-3 mb-3">
                                <label>Vehicle Number</label>
                                <select name="vehicle_id" id="vehicle_id" class="form-control" required>
                                    <option value="">-- Select Vehicle --</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Category</label>
                                <select id="vehicleCategoryRate" name="vehicleCategoryRate" class="form-control"
                                    required>
                                    <option value="">-- Select Category --</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Driver</label>
                                <select name="driver_id" id="driver_id" class="form-control">
                                    <option value="">-- Select Driver --</option>
                                </select>
                            </div>

                            <!-- RENTAL DETAILS -->
                            <div class="col-md-3 mb-3">
                                <label>Hire Start Date</label>
                                <input type="date" name="rent_start_date" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Hire Start Location</label>
                                <!-- <div class="input-group">
    <select id="hire_start_location" name="hire_start_location" class="form-control" required>
      <option value="">Select Hire Start Location</option>
    </select>
  
         </div> -->
                                <input type="text" name="hire_start_location" class="form-control"
                                    style="text-transform: uppercase;" required>

                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Hire End Location</label>
                                <!-- <div class="input-group">
    <select id="hire_end_location" name="hire_end_location" class="form-control" required>
      <option value="">Select Hire End Location</option>
    </select>
  
         </div> -->
                                <input type="text" name="hire_end_location" class="form-control"
                                    style="text-transform: uppercase;">

                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Hire Starting Mileage (Km)</label>
                                <input type="number" name="mileage" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Hire End Mileage (Km)</label>
                                <input type="number" name="endmileage" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Difference Mileage (Km)</label>
                                <input type="number" id="differencemileage" name="differencemileage"
                                    class="form-control" required readonly>
                            </div>


                            <div class="col-md-3 mb-3">
                                <label for="oillevel">Fuel Type</label>
                                <div class="input-group">
                                    <select name="oillevel" id="oillevel" class="form-control">
                                        <!-- Options will be loaded here by AJAX -->
                                    </select>

                                </div>
                            </div>


                            <div class="col-md-3 mb-3">
                                <label>Hire Duration (KM)</label>
                                <div class="input-group">
                                    <input type="number" id="duration" name="duration" class="form-control">

                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Hire Amount</label>
                                <input type="text" name="rent_amount" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Purpose / Remarks</label>
                                <input type="text" name="remarks" class="form-control">
                            </div>



                            <div class="col-md-3 mb-3">
                                <div class="input-group">
                                    <table class="table table-bordered table-sm align-middle" id="materialTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40%;">Material</th>
                                                <th style="width: 30%;">Unit</th>
                                                <th style="width: 30%;">Quantity</th>
                                                <th style="width: 30%;">Amount</th>
                                                <th style="width: 20%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="materialBody">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <input type="text" id="materialName" class="form-control"
                                                        placeholder="Name" style="width: 80px;" />
                                                </td>
                                                <td>
                                                    <select name="materialUnit" id="materialUnit"
                                                        class="form-control form-select-sm" style="width: 100px;">
                                                        <option value="">Unit</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" id="materialQty" class="form-control"
                                                        placeholder="Qty" min="1" style="width: 80px;" />
                                                </td>
                                                <td>
                                                    <input type="number" id="materialAmount" class="form-control"
                                                        placeholder="Amount" min="1" style="width: 80px;" />
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" id="addMaterialBtn"
                                                        class="btn btn-success btn-sm" title="Add Material">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>





                            <div class="col-md-6 mb-3" style="margin-left:100px">
                                <div class="input-group">
                                    <table class="table table-bordered table-sm align-middle" id="expenseTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 50%;">Expense Name</th>
                                                <th style="width: 30%;">Amount</th>
                                                <th style="width: 20%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expenseBody">
                                            <!-- Added expenses will appear here -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <select id="expenseName" class="form-control">
                                                        <option value="">-- Select Expense --</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="number" id="expenseAmount" class="form-control"
                                                        placeholder="Amount" min="0" />
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" id="addExpenseBtn"
                                                        class="btn btn-success btn-sm" title="Add Expense">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- 
<div class="col-md-3 mb-3">
        <label>Driver Salary</label>
        <input type="text" name="remarks" class="form-control">
    </div> -->


                        </div>

                        <div class="d-flex justify-content-end">
                            <button id="saveBtn" type="submit" class="btn btn-dark">Save Hire</button>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>




        <!-- Expense Modal -->
      <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog"
     aria-labelledby="expenseModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">


                    <div class="modal-header">
                        <h5 class="modal-title" id="expenseModalLabel">Add Expense</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="expenseForm">
                            <div class="form-group">
                                <input type="text" hidden class="form-control" id="expenseIdModel" name="expenseId"
                                    placeholder="Enter expense ID">
                            </div>
                            <div class="form-group">
                                <label for="expenseNameModel">Expense Name</label>
                                <input type="text" class="form-control" id="expenseNameModel" name="expenseNameModel"
                                    placeholder="Enter expense name">
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" form="expenseForm" class="btn btn-success">Save Expense</button>
                    </div>

                    <div class="container mt-4">
                        <h4>Expense List</h4>
                        <table class="table table-bordered" id="expenseTablemodel">
                            <thead class="thead-light">
                                <tr>
                                    <th style="display:none;">ID</th> <!-- Hidden column -->
                                    <th>Expense Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>


        <!-- Add Location Modal -->
        <div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog"
            aria-labelledby="addLocationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <!-- modal-lg for wider modal -->
                <div class="modal-content">
                    <form id="addLocationForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addLocationModalLabel">Add New Location</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!-- Form inputs -->
                            <div class="form-group">
                                <label for="locationName">Location Name</label>
                                <input type="text" class="form-control" id="locationName" name="locationName" required>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save Location</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                            <!-- Scrollable table container -->
                            <div class="table-responsive"
                                style="max-height: 300px; overflow-y: auto; margin-top: 20px;">
                                <h5>Locations List</h5>
                                <table id="locationsTable" class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Location Name</th>
                                            <th>Status</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data loaded by DataTable -->
                                    </tbody>
                                </table>
                            </div>

                        </div>


                    </form>
                </div>
            </div>
        </div>

        <!-- Diesel Levels Modal -->
        <div class="modal fade" id="oilLevelModal" tabindex="-1" role="dialog" aria-labelledby="oilLevelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <!-- Large modal -->
                <div class="modal-content">
                    <form id="oilLevelForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="oilLevelModalLabel">Manage Diesel Levels</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                          <div class="form-group">
                                <label for="levelLabel">Standard Fuel Type (e.g: Octane 92)</label>
                                <select class="form-control" id="levelLabel" name="label" required>
                                    <option value="">-- Select Fuel Type --</option>

                                    <option value="petrol-92">Petrol-92</option>
                                    <option value="petrol-95">Petrol-95</option>
                                    <option value="petrol-98">Petrol-98</option>
                                    <option value="diesel-regular">Diesel-Regular</option>
                                    <option value="diesel-premium">Diesel-Premium</option>
                                    <option value="cng">CNG</option>
                                    <option value="lpg">LPG</option>
                                </select>

                            </div>
                            <!-- Input fields -->
                            <div class="form-group">
                                <label for="levelValue">Today Fuel Price (1L)</label>
                                <input type="number" step="0.1" class="form-control" id="levelValue" name="level_value"
                                    required>
                            </div>
                          

                            <!-- Buttons before table, aligned to right -->
                            <div class="d-flex justify-content-end mb-3">
                                <button type="submit" class="btn btn-success mr-2">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table id="oilLevelsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                             <th>Fuel Type</th>
                                            <th>Fuel Price</th>
                                           
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Optional: remove original modal-footer -->
                        <!-- <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div> -->

                    </form>
                </div>
            </div>
        </div>



        <script>
        var $j = jQuery.noConflict();
        $j(document).ready(function() {
            loadMaxHireNo();

            // First load values


            var expensetable = $j('#expenseTablemodel').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                searchable: true,
                autoWidth: false,
                 pageLength: 5,               
    lengthMenu: [5, 10, 25, 50],  
                ajax: {
                    url: '<?= base_url("RentVehicle/fetchExpenses") ?>',
                    type: 'POST',
                    dataSrc: function(json) {
                        console.log('Raw AJAX response:', json);
                        console.log('Data source (json.data):', json.data);
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
                columns: [{
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'expense_name'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Hide Edit/Delete buttons if id = 6
                            if (row.id == 6) {
                                return ''; // empty cell
                            }
                            return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary editexpenseModelBtn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteexpenseModelBtn" data-id="${row.id}">Delete</button>
                    </div>
                `;
                        }
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        visible: false,
                        searchable: false
                    }, // Hide ID column
                ]
            });




            $.ajax({
                url: '<?= base_url("RentVehicle/getUnits") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Units', response);
                    if (response.status === 'success') {
                        let select = $('select[name="materialUnit"]');
                        select.empty(); // Clear existing options
                        select.append('<option value="">Select Units</option>');

                        $.each(response.data, function(index, unit) {
                            // Set id as value, name as text, and optionally data-name
                            select.append(
                                '<option value="' + unit.id + '" data-name="' + unit
                                .name + '">' + unit.name + '</option>'
                            );
                        });
                    } else {
                        alert('Failed to load Diesel Levels.');
                    }
                },

                error: function() {
                    alert('Error loading Diesel Levels.');
                }
            });

            $.ajax({
                url: '<?= base_url("RentVehicle/get_oil_levels_ajax") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let select = $('select[name="oillevel"]');
                        select.empty(); // clear existing options
                        select.append('<option value="">Select Standered Fuel Type (L)</option>');
                        $.each(response.data, function(index, oil) {
                            select.append('<option value="' + oil.level_value + '">' + oil
                                .label + '</option>');
                        });
                    } else {
                        alert('Failed to load Diesel Levels.');
                    }
                },
                error: function() {
                    alert('Error loading Diesel Levels.');
                }
            });


            $.ajax({

                url: '<?php echo base_url("Category/loadcategory"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.categories && Array.isArray(response.categories)) {

                        let select2 = $('select[name="vehicleCategoryRate"]');

                        select2.find('option:not(:first)').remove();
                        $.each(response.categories, function(index, cat) {
                            select2.append(
                                `<option value="${cat.idCategory}">${cat.Category}</option>`
                            );
                        });


                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading categories:', error);
                }
            });
            $j.ajax({
                url: '<?php echo base_url("Vehicle/get_vehicles_ajax"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.data && Array.isArray(response.data)) {
                        let select = $j('select[name="vehicle_id"]');
                        select.find('option:not(:first)').remove();

                        $j.each(response.data, function(index, vehicle) {
                            let id = vehicle[0]; // Vehicle ID
                            let vehicleNum = vehicle[4]; // Vehicle Number
                            select.append(`<option value="${id}">${vehicleNum}</option>`);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading vehicles:', error);
                }
            });

            // Load Vehicles for select dropdown
            $j.ajax({
                url: '<?php echo base_url("Driver/get_Driver_ajax"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Driver list', response);
                    if (response.data && Array.isArray(response.data)) {
                        let select = $j('select[name="driver_id"]');
                        select.find('option:not(:first)').remove();

                        $j.each(response.data, function(index, vehicle) {
                            let id = vehicle[0]; // Vehicle ID
                            let driver = vehicle[1]; // Vehicle Number
                            select.append(`<option value="${id}">${driver}</option>`);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading vehicles:', error);
                }
            });

            $j.ajax({
                url: '<?php echo base_url("Customer/loadcustomers"); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);

                    if (response.customers && Array.isArray(response.customers)) {
                        let select = $j('select[name="render_name"]');
                        select.find('option:not(:first)').remove();

                        $j.each(response.customers, function(index, customer) {
                            let id = customer.id;
                            let name = customer
                                .customer_name; // Or use customer.display_name

                            if (name) { // filters out null, undefined, and empty strings
                                select.append(`<option value="${id}">${name}</option>`);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading customers:', error);
                }
            });



            $.ajax({
                url: '<?= base_url("RentVehicle/get_hire_locations_ajax") ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let select = $('#hire_start_location');
                        let select2 = $('#hire_end_location');

                        select.find('option:not(:first)').remove(); // Keep default
                        $.each(response.data, function(index, location) {
                            select.append('<option value="' + location.id + '">' + location
                                .location_name + '</option>');
                        });

                        select2.find('option:not(:first)').remove(); // Keep default
                        $.each(response.data, function(index, location) {
                            select2.append('<option value="' + location.id + '">' + location
                                .location_name + '</option>');
                        });

                    } else {
                        console.warn('No locations returned');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching locations:', error);
                }
            });

            ///////////


            $j(document).on('input', 'input[name="mileage"], input[name="endmileage"]', function() {
                let start = parseFloat($j('input[name="mileage"]').val());
                let end = parseFloat($j('input[name="endmileage"]').val());

                if (!isNaN(start) && !isNaN(end)) {
                    let difference = end - start;
                    console.log('Mileage Difference:', difference); // Do what you want with this value
                    // Example: update another field or element
                    $j('#differencemileage').val(
                        difference); // if you have a <span id="mileageDifference">
                }
            });
            $j('#addExpenses').on('click', function() {
                $('#expenseForm')[0].reset();
                $('#expenseModal').modal('dispose'); // Dispose old instance
                $('#expenseModal').modal('show');
            });
            let total = 0;
            $j('#addMaterialBtn').on('click', function() {
                let name = $j('#materialName').val().trim();
                let unit = $j('#materialUnit option:selected').text();
                let qty = $j('#materialQty').val();
                let amount = $j('#materialAmount').val();

                if (!amount || !name || !unit || qty <= 0) {
                    alert('Please enter valid material name, unit, and quantity.');
                    return;
                }

                let newRow = `
        <tr>
          <td>${name}</td>
          <td>${unit}</td>
          <td>${qty}</td>
          <td>${amount}</td>
          <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeMaterialBtn">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        </tr>
    `;

                $j('#materialBody').append(newRow);
                total += parseFloat(amount);
                $j('input[name="rent_amount"]').val(total);
                // Clear inputs
                $j('#materialName').val('');
                $j('#materialUnit').val('');
                $j('#materialQty').val('');
                $j('#materialAmount').val('');
            });


            // Use event delegation for dynamically added buttons
            $j('#materialBody').on('click', '.removeMaterialBtn', function() {
                let row = $j(this).closest('tr');
                let amount = parseFloat(row.find('td:eq(3)').text()) || 0;
                total -= amount;
                $j('input[name="rent_amount"]').val(total);
                $j(this).closest('tr').remove();
            });

            function loadMaxHireNo() {
                $j.ajax({
                    url: '<?= base_url('RentVehicle/get_last_hire_no') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.hire_no !== undefined) {
                            // Pad the number to 4 digits (e.g., 1 → 0001)
                            let paddedHireNo = String(response.hire_no).padStart(4, '0');
                            document.getElementById('hireno').value = paddedHireNo;
                        } else {
                            document.getElementById('hireno').value = '0001';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching hire number:', error);
                        document.getElementById('hireno').value = 1;
                    }
                });
            }

            $j('#addLocationForm').on('submit', function(e) {
                e.preventDefault();

                // Collect form data
                var formData = {
                    id: $j(this).data('edit-id') || '', // edit id or empty for new
                    locationName: $j('#locationName').val(),
                    status: $j('#status').val()
                };

                $j.ajax({
                    url: '<?php echo base_url("RentVehicle/save_location_ajax"); ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Reset form & remove edit id
                                $('#addLocationForm').trigger('reset');
                                $('#addLocationForm').removeData('edit-id');

                                // ✅ Proper way to hide modal
                                $('#addLocationModal').modal('hide');

                                // ✅ Optional cleanup *only if* backdrop stays stuck (not always needed)
                                setTimeout(function() {
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                    $('body').css('padding-right', '');
                                }, 500); // Wait for Bootstrap to finish hiding

                                // ✅ Reload the table
                                table2.ajax.reload(null, false);
                                refreshHireStartLocationDropdown();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        }

                        ,
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while saving location.'
                        });
                    }
                });
            });


            $j('#addrentbtn').on('click', function() {

                $j('#vehicleRentForm')[0].reset();
                refreshHireExpenseModal();
                $j('#rentVehicleModal input[name="id"]').val('');

                // Fetch latest Hire No just before showing modal


                $j('#rentVehicleModal').modal('show');
                loadMaxHireNo();

            });
            $('#addlocations').on('click', function() {
                $('#addLocationModal').modal('show');
            });
            // Initialize DataTable
            var table = $j('#partsTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                searchable: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: '<?php echo base_url("RentVehicle/getAllrent"); ?>',
                    type: 'POST',
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
                columns: [{
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'HireNo'
                    },
                    {
                        data: 'Vehicle_Num'
                    },
                    {
                        data: 'customer_name'
                    },
                    {
                        data: 'agreement_no'
                    },
                    {
                        data: 'rent_start_date'
                    },
                    {
                        data: 'duration'
                    },
                    {
                        data: 'mileage'
                    },
                    {
                        data: 'oillevel'
                    },
                    {
                        data: 'rent_amount'
                    },
                    {
                        data: 'driver_name'
                    },
                    {
                        data: 'remarks'
                    },

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                <div class="btn-group">
                    <button class="btn btn-sm btn-primary editRentBtn" data-id="${row.id}">Edit</button>
                    <button class="btn btn-sm btn-danger deleteRentBtn" data-id="${row.id}">Delete</button>
                </div>
            `;
                        }
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        visible: false,
                        searchable: false
                    }, // Hide ID column

                ]
            });


            var table2 = $j('#locationsTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                searchable: false,
                autoWidth: false,
                ajax: {
                    url: '<?php echo base_url("RentVehicle/get_hire_locations_ajax"); ?>',
                    type: 'POST',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error loading locations',
                            text: 'Please try again later.'
                        });
                    }
                },
                columns: [{
                        data: 'id',
                        visible: false
                    },
                    {
                        data: 'location_name'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary editLocationBtn" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteLocationBtn" data-id="${row.id}">Delete</button>
                    </div>
                `;
                        }
                    }
                ],
                columnDefs: [{
                        targets: 0,
                        visible: false,
                        searchable: false
                    } // Hide ID column
                ]
            });

            var oilTable = $j('#oilLevelsTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '<?= base_url("RentVehicle/get_oil_levels_ajax") ?>',
                    type: 'GET',
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'id',visible:false},
                    
                    {
                        data: 'label'
                    },
                    {
                        data: 'level_value'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-primary editOilBtn" data-id="${row.id}">Edit</button>
                      
                    </div>
                `;
                        }
                    }
                ]
            });
            $j('#addExpenseBtn').on('click', function() {
                let id = $j('#expenseName option:selected').val();


                let name = $j('#expenseName option:selected').text();


                let amount = parseFloat($j('#expenseAmount').val());

                if (!name || isNaN(amount) || amount <= 0) {
                    alert('Please enter a valid expense name and amount.');
                    return;
                }

                let newRow = `
    <tr>
    <td style="display:none;">${id}</td>

      <td>${name}</td>
      <td>${amount.toFixed(2)}</td>
      <td class="text-center">
        <button type="button" class="btn btn-danger btn-sm removeExpenseBtn" title="Remove Expense">
          <i class="fas fa-trash-alt"></i>
        </button>
      </td>
    </tr>
  `;

                $j('#expenseBody').append(newRow);

                // Clear inputs
                $j('#expenseName').val('');
                $j('#expenseAmount').val('');
            });

            $j(document).on('click', '.removeExpenseBtn', function() {
                $j(this).closest('tr').remove();
            });

            // Open modal
            $j('#addoils').on('click', function() {
                $('#oilLevelForm').trigger('reset').removeData('edit-id');
                $('#levelLabel').prop('disabled', false);
                $('#oilLevelModal').modal('show');
            });

            $('#oilLevelForm').on('submit', function(e) {
                e.preventDefault();

                const id = $(this).data('edit-id') || ''; // Empty if insert
                const level_value = $('#levelValue').val();
                const label = $('#levelLabel option:selected').text();

                $.ajax({
                    url: '<?= base_url("RentVehicle/save_oil_level_ajax") ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        level_value: level_value,
                        label: label
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            });

                            // Reset form, clear edit ID
                            $('#oilLevelForm')[0].reset();
                            $('#oilLevelForm').removeData('edit-id');

                            // Hide modal
                            setTimeout(() => {
                                $('#oilLevelModal').modal('hide');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                            }, 300);

                            // Reload DataTable
                            oilTable.ajax.reload(null, false);
                            refreshOilLevelDefault();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Request Failed',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                });
            });

            // DELETE
            $(document).on('click', '.deleteexpenseModelBtn', function() {
                const id = $(this).data('id');
                deleteExpense(id);
            });

            // EDIT
            $(document).on('click', '.editexpenseModelBtn', function() {
                const rowData = expensetable.row($(this).closest('tr')).data();

                $('#expenseIdModel').val(rowData.id);
                $('#expenseNameModel').val(rowData.expense_name);
                $('#expenseModalLabel').text('Edit Expense');
                $('#expenseModal').modal('show');
            });


            // Edit handler
            $('#oilLevelsTable').on('click', '.editOilBtn', function(e) {
                e.preventDefault(); // ✅ Prevents default behavior like form submission

                var rowData = oilTable.row($(this).closest('tr')).data();
                console.log('sel', rowData);
                $('#levelValue').val(rowData.level_value);
                $('#levelLabel option').each(function() {
                    if ($(this).text() === rowData.label) {
                        $(this).prop('selected', true);
                    }
                });
    $('#levelLabel').prop('disabled', true);

                $('#oilLevelForm').data('edit-id', rowData.id);
            });


            // Delete handler
            // $('#oilLevelsTable').on('click', '.deleteOilBtn', function() {
            //     let id = $(this).data('id');
            //     Swal.fire({
            //         title: 'Delete?',
            //         text: 'Are you sure you want to delete this Diesel Level?',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Yes, delete'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             $.post('<?= base_url("RentVehicle/delete_oil_level") ?>', {
            //                 id: id
            //             }, function(response) {
            //                 if (response.status === 'success') {
            //                     Swal.fire('Deleted!', response.message, 'success');
            //                     oilTable.ajax.reload(null, false);
            //                 } else {
            //                     Swal.fire('Error', response.message, 'error');
            //                 }
            //             }, 'json');
            //         }
            //     });
            // });

            $('input[name="duration"]').on('input', function() {
                const vehicleId = $('#vehicle_id').val();
                const durationUnit = $('select[name="duration_unit"]').val();
                const durationValue = parseFloat($(this).val()); // get duration input as number

                // Check if all values are valid
                if (vehicleId && durationUnit && !isNaN(durationValue)) {
                    $.ajax({
                        url: '<?php echo base_url("RentVehicle/get_rent_rate"); ?>',
                        method: 'POST',
                        data: {
                            vehicle_id: vehicleId,
                            duration_unit: durationUnit
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response, 'This response');
                            if (response.status === 'success') {
                                const rentRate = parseFloat(response.rent_rate);

                                if (!isNaN(rentRate)) {
                                    const totalAmount = durationValue * rentRate;
                                    $('input[name="rent_amount"]').val(totalAmount.toFixed(
                                        2)); // round to 2 decimals
                                } else {
                                    $('input[name="rent_amount"]').val('');
                                    console.warn('Invalid rent rate');
                                }
                            } else {
                                $('input[name="rent_amount"]').val('');
                                console.warn(response.message);
                            }
                        },
                        error: function() {
                            console.error('AJAX request failed');
                        }
                    });
                } else {
                    $('input[name="rent_amount"]').val('');
                }
            });

            $('#rent_type').on('change', function() {
                let vehicleId = $('#vehicle_id').val();
                let categoryId = $('#vehicleCategoryRate').val();
                let rentType = $(this).val();

                if (vehicleId && categoryId && rentType) {
                    $.ajax({
                        url: '<?php echo base_url("RentVehicle/get_rate"); ?>',
                        type: 'POST',
                        data: {
                            vehicle_id: vehicleId,
                            category_id: categoryId,
                            rent_type: rentType
                        },
                        success: function(response) {
                            let data = JSON.parse(response);
                            if (data.status === 'success') {
                                $('#showamount').text(data.rate);

                            } else {
                                showToast('warning', 'Please fill in all fields.');
                                $('input[name="rent_amount"]').val('0');
                                $('#showamount').text('0');


                            }
                        },
                        error: function() {
                            showToast('warning', 'Error retriving rate');
                            $('input[name="duration"]').val('0');
                            $('#showamount').text('0');

                        }
                    });
                } else {
                    showToast('warning', 'Please select vehicle and category first');
                }
            });


            $j('#vehicleRentForm').on('submit', function(e) {
                e.preventDefault();


                // Using jQuery
                if ($j('#hireno').val().trim() === '') {
                    alert('Hire No is empty');
                    return;
                }


                let form = $j(this);
                let formData = form.serializeArray();


                const submitBtn = $j('#saveBtn'); // Change this selector to match your save button
                submitBtn.prop('disabled', true);

                // 🔁 Get material table rows
                const materials = [];
                $j('#materialBody tr').each(function() {
                    const name = $j(this).find('td:eq(0)').text().trim();
                    const unit = $j(this).find('td:eq(1)').text().trim();
                    const qty = parseInt($j(this).find('td:eq(2)').text().trim(), 10);
                    const amount = parseFloat($j(this).find('td:eq(3)').text().trim(), 10);



                    if (unit && !isNaN(qty)) {
                        materials.push({
                            name,
                            unit,
                            qty,
                            amount
                        });
                    }
                });

                // Get Expenses from table
                const Expenses = [];
                $j('#expenseBody tr').each(function() {
                    const id = $j(this).find('td:eq(0)').text().trim(); // Hidden ID
                    const exname = $j(this).find('td:eq(1)').text().trim(); // Expense Name
                    const examount = $j(this).find('td:eq(2)').text().trim(); // Amount

                    if (id && exname && !isNaN(examount)) {
                        Expenses.push({
                            id,
                            exname,
                            examount
                        });
                    }
                });

                // 👉 Add materials and expenses to form data
                formData.push({
                    name: 'materials',
                    value: JSON.stringify(materials)
                });
                formData.push({
                    name: 'expenses',
                    value: JSON.stringify(Expenses)
                });

                // ✅ AJAX request
                $j.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Res', response);

                        // Re-enable submit button
                        submitBtn.prop('disabled', false);

                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {

                                form[0].reset();

                                $j('#materialBody').empty();
                                $j('#expenseBody').empty();
                            });

                            table.ajax.reload(null, false);

                            $j('#rentVehicleModal input[name="id"]').val('');
                            loadMaxHireNo();
                            $j('#vehicleRentForm [name="id"]').val('');
                            $j('#showamount').text('');
                        } else if (response.status === 'error') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);

                        // Re-enable submit button on error
                        submitBtn.prop('disabled', false);

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'An error occurred while submitting the form.'
                        });
                    }
                });
            });


            $j('#duration').on('input', function() {
                var duration = parseFloat($j(this).val()) || 0; // get typed value or 0 if empty/invalid
                var amount = parseFloat($j('#showamount').text()) || 0; // get amount from label

                var total = duration * amount;

                $j('input[name="rent_amount"]').val(total.toFixed(2)); // set total, 2 decimals
            });

            // Edit Rent button handler
            $j('#partsTable tbody').on('click', '.editRentBtn', function() {
                $j('#vehicleRentForm')[0].reset();
                let rowData = table.row($j(this).parents('tr')).data();
                $j('#vehicleRentForm [name="id"]').val(rowData.id);
                $j('#vehicleRentForm [name="mileage"]').val(rowData.mileage);
                $j('#vehicleRentForm [name="hireno"]').val(rowData.HireNo);

                // For Vehicle
                if ($j(`#vehicle_id option[value="${rowData.vehicle_number}"]`).length === 0) {
                    $j('#vehicle_id').append(
                        `<option value="${rowData.vehicle_number}">${rowData.Vehicle_Num}</option>`);
                }
                $j('#vehicle_id').val(rowData.vehicle_number);

                // For Renter
                if ($j(`#render_name option[value="${rowData.renter_name}"]`).length === 0) {
                    $j('#render_name').append(
                        `<option value="${rowData.renter_name}">${rowData.customer_name}</option>`);
                }
                $j('#render_name').val(rowData.renter_name);

                $j('#vehicleRentForm [name="agreement_no"]').val(rowData.agreement_no);

                let startDate = rowData.rent_start_date ? rowData.rent_start_date.split(' ')[0] : '';
                $j('#vehicleRentForm [name="rent_start_date"]').val(startDate);

                $j('#vehicleRentForm [name="vehicleCategoryRate"]').val(rowData.rent_type);
                if (rowData.duration) {
                    // const [durationValue, durationUnit] = rowData.duration.split('-');

                    $j('#vehicleRentForm [name="duration"]').val(rowData.duration);



                    if ($j(`#oillevel option[value="${rowData.oillevel}"]`).length === 0) {
                        $j('#oillevel').append(
                            `<option value="${rowData.oillevel}">${rowData.oillevel}</option>`);
                    }



                }

                $j('#oillevel').val(rowData.oillevel);

                $j('#vehicleRentForm [name="rent_amount"]').val(rowData.rent_amount);
                $j('#vehicleRentForm [name="driver_id"]').val(rowData.driver_id);
                $j('#vehicleRentForm [name="remarks"]').val(rowData.remarks);

                $j('#vehicleRentForm [name="hire_start_location"]').val(rowData.hire_location);
                $j('#vehicleRentForm [name="hire_end_location"]').val(rowData.end_location);
                $j('#vehicleRentForm [name="endmileage"]').val(rowData.End_Milage);
                $j('#vehicleRentForm [name="differencemileage"]').val(rowData.Difference_Milage);





                //LOAD VALUES TO HIRENO
                $.ajax({
                    url: '<?= base_url("RentVehicle/get_hire_details") ?>',
                    method: 'GET',
                    data: {
                        hireno: rowData.HireNo
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            populateMaterialTable(response.materials);
                            populateExpenseTable(response.expenses);
                        } else {
                            alert(response.message || 'No data found.');
                        }
                    },
                    error: function() {
                        alert('Error retrieving hire details.');
                    }
                });



                $j('#rentVehicleModal').modal('show');

            });


            // Delete button handler
            $j('#partsTable tbody').on('click', '.deleteRentBtn', function() {
                let rentId = $j(this).data('id');

                // ✅ Get row data using DataTables API
                let rowData = table.row($j(this).closest('tr')).data();
                let hireNo = rowData?.HireNo || 'Unknown';

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This rent record (Hire No: ${hireNo}) will be permanently deleted.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $j.ajax({
                            url: '<?php echo base_url("RentVehicle/deleteRent"); ?>',
                            type: 'POST',
                            data: {
                                hireNo: hireNo
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire('Deleted!', response.message,
                                        'success');
                                    table.ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error',
                                    'An error occurred while deleting.', 'error'
                                );
                            }
                        });
                    }
                });
            });

            $j('#locationsTable').on('click', '.editLocationBtn', function(e) {
                e.preventDefault(); // Prevent form submission or link navigation

                var tr = $j(this).closest('tr');
                var rowData = table2.row(tr).data();

                $j('#locationName').val(rowData.location_name);
                $j('#status').val(rowData.status);

                $j('#addLocationForm').data('edit-id', rowData.id);

                $j('#addLocationModal').modal('show'); // Don't forget to show the modal!
            });

            $j('#locationsTable').on('click', '.deleteLocationBtn', function() {
                var locationId = $j(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send delete request
                        $j.ajax({
                            url: '<?php echo base_url("RentVehicle/delete_location_ajax"); ?>',
                            type: 'POST',
                            data: {
                                id: locationId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire('Deleted!', response.message,
                                        'success');
                                    table2.ajax.reload(null, false);
                                    refreshHireStartLocationDropdown();
                                    // reload datatable without resetting pagination
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete location.',
                                    'error');
                            }
                        });
                    }
                });
            });

            function populateMaterialTable(materials) {
                const $materialBody = $('#materialBody');
                $materialBody.empty(); // clear old rows
                console.log('materials', materials);
                materials.forEach(item => {
                    const row = `
      <tr>
        <td>${item.Name ?? '-'}</td>
        <td>${item.Unit}</td>
        <td>${item.Qty}</td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm removeMaterialBtn">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    `;
                    $materialBody.append(row);
                });
            }

            function populateExpenseTable(expenses) {
                const $expenseBody = $('#expenseBody');
                $expenseBody.empty(); // clear old rows

                expenses.forEach(item => {
                    const row = `
      <tr>
     <td style="display:none;">${item.ExpenseName}</td>

        <td>${item.expense_name}</td>
        <td>${item.Amount}</td>
        <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm removeExpenseBtn">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    `;
                    $expenseBody.append(row);
                });
            }

            function refreshHireStartLocationDropdown() {
                $.ajax({
                    url: '<?= base_url("RentVehicle/get_hire_locations_ajax") ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('come', response);
                        if (response.status === 'success') {
                            let select = $('#hire_start_location');
                            select.find('option:not(:first)').remove(); // Keep default
                            $.each(response.data, function(index, location) {
                                select.append('<option value="' + location.id + '">' +
                                    location.location_name + '</option>');
                            });
                        } else {
                            console.warn('No locations returned');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching locations:', error);
                    }
                });
            }

            function refreshHireExpenseModal() {
                $j.ajax({
                    url: '<?= base_url("RentVehicle/fetchExpenses") ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);

                        if (response.status) { // ✅ This allows both true or 'success'
                            let select = $j('#expenseName');
                            select.find('option:not(:first)')
                                .remove(); // Keep the first option "-- Select Expense --"

                            $j.each(response.data, function(index, expense) {
                                select.append(
                                    '<option value="' + expense.id + '">' + expense
                                    .expense_name + '</option>'
                                );
                            });
                        } else {
                            console.warn('No expenses returned');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching expenses:', error);
                    }
                });
            }



            function refreshOilLevelDefault() {
                $.ajax({
                    url: '<?= base_url("RentVehicle/get_oil_levels_ajax") ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            let select = $('select[name="oillevel"]');
                            select.empty(); // clear existing options
                            select.append(
                                '<option value="">Select Start Diesel Level (L)</option>');
                            $.each(response.data, function(index, oil) {
                                select.append('<option value="' + oil.level_value + '">' +
                                    oil.label + '</option>');
                            });
                        } else {
                            alert('Failed to load Diesel Levels.');
                        }
                    },
                    error: function() {
                        alert('Error loading Diesel Levels.');
                    }
                });

            }
            $j.ajax({
                url: '<?= base_url("RentVehicle/fetchExpenses") ?>',
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    // Clear existing options except the default
                    $j('#expenseName').find('option:not(:first)').remove();

                    if (data.data && data.data.length > 0) {
                        data.data.forEach(item => {
                            $j('#expenseName').append(
                                `<option value="${item.id}">${item.expense_name}</option>`
                            );
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching expenses:', xhr.responseText);
                }
            });

            function deleteExpense(id) {


                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this expense!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url("RentVehicle/deleteExpense") ?>',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {

                                showToast('success', 'Expense deleted successfully!');
                                expensetable.ajax.reload(); // Reload the table
                            },
                            error: function() {
                                showToast('error', 'Failed to delete expense.');
                            }
                        });
                    }
                });
            }
            // Submit handler
            $('#expenseForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url("RentVehicle/insertExpense"); ?>',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            const action = response.action === 'updated' ? 'updated' :
                                'inserted';
                            showToast('success', `Expense ${action} successfully!`);

                            expensetable.ajax.reload();

                            // Hide modal and reset form AFTER modal fully hides
                            $('#expenseModal').modal('hide').one('hidden.bs.modal',
                                function() {
                                    $('#expenseForm')[0].reset();
                                });

                        } else {
                            showToast('error', 'Operation failed.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while saving the expense.');
                        console.error(xhr.responseText);
                    }
                });
            });





        });
        </script>
    </div>
</body>

</html>