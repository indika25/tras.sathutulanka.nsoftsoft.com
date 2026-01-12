<?php
// Optional: session validation
// session_start();
// if (!isset($_SESSION['user'])) {
//     header('Location: login.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vehicle Rental Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>

  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      text-align: center;
      padding: 60px 20px 20px;
    }

    .hero-section img {
      max-width: 320px;
      width: 100%;
      animation: fadeInDown 1s ease;
    }

    .dashboard-container {
      max-width: 1100px;
      margin: 40px auto;
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    }

    .dashboard-container h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      color: #333;
    }

    .btn-custom {
      min-width: 180px;
      margin: 15px;
      font-size: 17px;
    }

    .btn:hover {
      transform: scale(1.05);
      transition: 0.3s ease-in-out;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
      .btn-custom {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- ✅ Hero Image Section -->
<!-- Hero Section with Fixed Image Width Using Bootstrap -->
<div class="container text-center my-5">
  <div class="row justify-content-center">
  
  </div>

  <h1 class="mt-4 animate__animated animate__fadeInUp">Vehicle Rental System</h1>
  <p class="text-muted">Manage your fleet, bookings, services & more</p>
</div>


<!-- ✅ Dashboard Button Section -->
<div class="container dashboard-container animate__animated animate__fadeIn">
  <h2>Quick Access</h2>

  <div class="row g-4 justify-content-center text-center">

    <!-- Vehicles -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/vehicledetails'); ?>" class="btn btn-outline-primary btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-car fa-2x mb-2"></i><br>Vehicles
      </a>
    </div>

    <!-- Drivers -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/driverdetails'); ?>" class="btn btn-outline-secondary btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-id-badge fa-2x mb-2"></i><br>Drivers
      </a>
    </div>

    <!-- Customers -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/addcustomer'); ?>" class="btn btn-outline-success btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-user-tie fa-2x mb-2"></i><br>Customers
      </a>
    </div>

    <!-- Rent Vehicle -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/RentVehicle'); ?>" class="btn btn-outline-info btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-car-side fa-2x mb-2"></i><br>Rent Vehicle
      </a>
    </div>

    <!-- Service -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/vehicleparts'); ?>" class="btn btn-outline-warning btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-tools fa-2x mb-2"></i><br>Services
      </a>
    </div>

    <!-- Damages -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/partsDamage'); ?>" class="btn btn-outline-danger btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-exclamation-triangle fa-2x mb-2 text-danger"></i><br>Damages
      </a>
    </div>

    <!-- Reports -->
    <div class="col-md-3">
      <a href="<?php echo base_url('Dashboard/vehiclerentreport'); ?>" class="btn btn-outline-dark btn-lg w-100 py-4 btn-custom">
        <i class="fas fa-file-alt fa-2x mb-2"></i><br>Reports
      </a>
    </div>
 <div class="col-md-3">
  <a href="<?php echo base_url('Login/logout'); ?>" class="btn btn-outline-danger btn-lg w-100 py-4 btn-custom">
    <i class="fas fa-sign-out-alt fa-2x mb-2"></i><br>Logout
  </a>
</div>

  </div>
</div>

<!-- Bootstrap & JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
