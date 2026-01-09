<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
    const BASE_URL = "<?= base_url(); ?>";
    const VEHICLE_SUCCESS_MESSAGE = <?= json_encode($this->session->flashdata('success')) ?>;
</script>
<script src="<?= base_url('assets/js/vehicle-management.js') ?>"></script>
<script src="<?= base_url('assets/js/showToast.js') ?>"></script>


<style>



    #vehicleTable th,
    #vehicleTable td {
        vertical-align: middle;
        padding: 2px 2px;
        
        font-size: 14px;
       
    }

    /* ⬇️ ADD THIS for wrapping column content */
    #vehicleTable td.wrap-text {
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
  
    <div class="card shadow">
      
    

     <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
  <h5 class="mb-0">Vehicle List</h5>

  <!-- Wrap buttons inside a div for proper alignment -->
  <div class="d-flex gap-2">
    <!-- <button type="button" id="addratebtn" class="d-flex align-items-center btn btn-warning me-2 mr-2">
      <i class="fas fa-plus me-2" style="color: #fff; font-size: 20px;"></i> Add Rates
    </button> -->
    <button type="button" id="addvehiclebtn" class="d-flex align-items-center btn btn-success">
      <i class="fas fa-plus me-2" style="color: #fff; font-size: 20px;"></i> Add Vehicle
    </button>
  </div>
</div>


        
        <div class="card-body">
            <div class="table-responsive">
                <table id="vehicleTable" class="table table-bordered table-striped">
                    <thead class="thead-dark">
                       <tr>
    <th>ID</th>
    <th>Make</th>
    <th>Model</th>
    <th>Chassis No</th>
    <th>Number</th>
    <th>Fuel Type</th>
    <th>Seats</th>
    <th>Liters per KM</th>
    <th>Category</th>
    <th>Options</th>
</tr>

                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Vehicle Make Modal -->

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1" role="dialog" aria-labelledby="addRateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="addRateModalLabel">Add Vehicle Rate</h5>
     <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>

      </div>
      
      <div class="modal-body">
        <form id="rateForm">
           <input type="hidden" id="rateId" name="id" value="">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label>Vehicle Number</label>
              <select id="vehicleNumberRate" name="vehicleNumberRate" class="form-control" required>
                <option value="">-- Select Vehicle --</option>
             
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label>Category</label>
              <select id="vehicleCategoryRate" name="vehicleCategoryRate" class="form-control" required>
                <option value="">-- Select Category --</option>
                
              </select>
            </div>
                      <div class="col-md-3 mb-3">
                        <label>Rent Type</label>
                        <select name="rent_type" class="form-control" required>
                            <option value="">-- Select Rent Type --</option>
                            <option value="Hourly">Hourly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Mileage-based">Mileage-based</option>
                        </select>
                      </div>
            <div class="col-md-4 mb-3">
              <label>Rate</label>
              <input type="number" id="vehicleRateInput" class="form-control" placeholder="Enter rate" required>
            </div>


             <div class="col-md-4 mb-3">
              <label>Save </label>
           <button type="button" id="addRateToTable" class="form-control btn btn-success">
              + Add Rate
            </button>   
            </div>
             

          </div>

         
        </form>

        <hr>

        <div class="table-responsive mt-3">
          <table class="table table-bordered" id="rateTable">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Vehicle Number</th>
                <th>Category</th>
                <th>Rate</th>
                <th>Rate Type</th>
                <th>Options</th>
                
              </tr>
            </thead>
            <tbody>
              <!-- Items will be appended here -->
            </tbody>
          </table>
        </div>
      </div>


    </div>
  </div>
</div>

<!-- Vehicle Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="vehicleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="vehicleModalLabel">
          <i class="fas fa-edit me-2"></i>Vehicle Details
        </h5>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>


      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <div class="card shadow mb-0">
          <div class="card-body">
            <?php echo form_open('Vehicle/SaveVehicle', ['id'=>'vehicleForm']); ?>
            <div class="row">
              <div class="col-md-3 mb-3">
                <label>Vehicle Number</label>
                <input type="text" name="Vehicle_Num" class="form-control" required>
              </div>

              <div class="col-md-3 mb-3">
                <label>Vehicle Make</label>
                <div class="d-flex flex-column">
                  <div class="d-flex">
                    <select id="vehicleMake" name="Vehicle_Make" class="form-control me-2" required>
                      <option value="">-- Select Make --</option>
                      <!-- options here -->
                    </select>
                    <button id="btn_make" type="button" class="btn btn-warning fw-bold">+</button>
                  </div>
                  <div id="inputSection" style="display: none; margin-top: 10px;">
                    <input type="text" id="newMake" class="form-control me-2" placeholder="New Vehicle Make" />
                    <button id="btn_addMake" type="button" class="btn btn-primary fw-bold mt-2">Add</button>
                  </div>
                </div>
              </div>

              <div class="col-md-3 mb-3">
                <label>Vehicle Model</label>
                <div class="d-flex">
                  <select id="vehicle_model" name="Vehicle_model" class="form-control me-2" required>
                    <option value="">-- Select Model --</option>
                  </select>
                  <button id="btn_model" type="button" class="btn btn-warning fw-bold">+</button>
                </div>
                <div id="inputSection2" style="display: none; margin-top: 10px;">
                  <input type="text" id="newModel" class="form-control me-2" placeholder="New Vehicle Model" />
                  <button id="btn_addModel" type="button" class="btn btn-primary fw-bold mt-2">Add</button>
                </div>
              </div>

              <div class="col-md-3 mb-3">
                <label>Chassis Number</label>
                <input id='Chassis_No' type="text" name="Chassis_No" class="form-control" required>
              </div>

              <div class="col-md-3 mb-3">
                <label>Fuel Type</label>
                <select name="Fuel_Type" class="form-control" required>
                  <option value="">-- Select Fuel Type --</option>
                </select>
              </div>

              <div class="col-md-2 mb-3">
                <label>Seats</label>
                <input type="number" name="Seats" class="form-control" required>
              </div>

              <!-- <div class="col-md-2 mb-3">
                <label>Status</label>
                <select name="status" class="form-control" >
                  <option value="Available">Available</option>
                  <option value="Rented">Rented</option>
                  <option value="Repair">Repair</option>
                  <option value="Pending">Pending</option>
                </select>
              </div> -->

              <div class="col-md-3 mb-3">
                <label>Category</label>
                <select name="Category_idCategory" class="form-control" required>
                  <option value="">-- Select Category --</option>
                </select>
              </div>
<!--               
              <div class="col-md-3 mb-3">
    <label>Rent Type Per Single</label>
     <select name="duration_unit" class="form-control" required>
                 <option value="Hourly">Hourly</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Mileage-based">Mileage-based</option>
                   </select>
               </div> -->

           <div class="col-md-3 mb-3">
            <label>Liters per KM</label>
            <input type="text" name="rentrate" class="form-control" required>
        </div>
              <!-- <div class="col-md-3 mb-3"> -->
            <!-- <label class="text-danger">Add Multiple Rates</label> -->
           <!-- <button type="button" id="addRateBtn" class="form-control btn-warning">
  Add Rate >>
</button> -->

           </div>
              
            </div>

            <div class="col-md-12 d-flex justify-content-end">
             
                <button id="addnewBtn" type="button" class="btn btn-success mr-2">
                + Add New
              </button>
              <button id="vehicleSubmitBtn" type="submit" class="btn btn-dark">
                Save Vehicle
              </button>
            </div>

            <?php echo form_close(); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>