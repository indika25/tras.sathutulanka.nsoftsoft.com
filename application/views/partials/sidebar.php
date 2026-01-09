  <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('Dashboard/dash'); ?>">
              <div class="sidebar-brand-icon rotate-n-15">
    <i class="fas fa-home"></i>
</div>

                <div class="sidebar-brand-text mx-3">Home</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url('Dashboard/dash'); ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-car"></i>
                    <span>Vehicle</span>
                </a>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
    <a class="collapse-item" href="<?php echo base_url('Dashboard/vehicledetails'); ?>">
        <i class="fas fa-car-side me-2"></i> Vehicle Details
    </a>
</div>
  <!-- <div class="bg-white py-2 collapse-inner rounded">
    <a class="collapse-item" href="<?php echo base_url('Dashboard/vehicledetails'); ?>">
        <i class="fas fa-car-side me-2"></i> Vehicle Rate Mange
    </a>
</div> -->
 <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/driverdetails'); ?>">
                        <i class="fas fa-tachometer-alt"></i> Driver Details
                       </a>
                    </div>

<div class="bg-white py-2 collapse-inner rounded">
    <a class="collapse-item" href="<?php echo base_url('Dashboard/vehicleparts'); ?>">
        <i class="fas fa-tools me-2"></i> Service Details
    </a>
</div>

<div class="bg-white py-2 collapse-inner rounded">
    <a class="collapse-item" href="<?php echo base_url('Dashboard/partsDamage'); ?>">
        <i class="fas fa-exclamation-triangle me-2 text-danger"></i> Damage Details
    </a>
</div>


                </div>
            </li>
            
          <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCustomer"
                    aria-expanded="true" aria-controls="collapseCustomer">
                   <i class="fas fa-user-tie"></i> <!-- Business person -->

                    <span>Customer</span>
                </a>
              <div id="collapseCustomer" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/addcustomer'); ?>">Customer Details</a>
                    </div>
              </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapserentvehicle"
                    aria-expanded="true" aria-controls="collapseCustomer">
                 <i class="fas fa-car-side"></i> <span>Hire Vehicle</span>

                </a>
              <div id="collapserentvehicle" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/RentVehicle'); ?>">Vehicle Hire Details</a>
                    </div>

                    
                    
                </div>
            </li>

                <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsereport"
                    aria-expanded="true" aria-controls="collapseCustomer">
                <i class="fas fa-file-alt"></i> <span>Reports</span>

                </a>
              <div id="collapsereport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/partsreport'); ?>">Installed Parts Report</a>
                    </div>
                   <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/damagepartsreport'); ?>">Damaged Parts Report</a>
                    </div>
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/vehiclerentreport'); ?>">Vehicles Hire Report</a>
                    </div>
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/vehicle_expense_report'); ?>">Vehicles Expenses Report</a>
                    </div>
                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/averageFuelView'); ?>">Average Fuel Report</a>
                    </div>

                    <div class="bg-white py-2 collapse-inner rounded">
                       <a class="collapse-item" href="<?php echo base_url('Dashboard/monthlysumery'); ?>">Summary</a>
                    </div>

                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <div class="sidebar-card d-none d-lg-flex">
               <p class="text-center mb-2">Vehicle maintaince</p>
            </div>

        </ul>