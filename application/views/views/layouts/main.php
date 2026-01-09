<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo isset($title) ? $title : 'Dashboard'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    
    <!-- SB Admin 2 CSS -->
    <link href="<?php echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <style>
       body {
    font-family: 'Poppins', sans-serif !important;
    background-color: #f8f9fc;   /* Soft light background like SB Admin 2 */
    color: #3a3b45;              /* Dark gray text for readability */
    line-height: 1.6;            /* Better text spacing */
}

    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php $this->load->view('partials/sidebar'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php $this->load->view('partials/topbar'); ?>
                <!-- End of Topbar -->

                <!-- Page Content -->
                <div class="container-fluid">
                    <?php echo isset($content) ? $content : ''; ?>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- <?php $this->load->view('partials/footer'); ?> -->
        </div>
    </div>

   <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/sb-admin-2.min.js'); ?>"></script>
<script>
function updateDateTime() {
  const now = new Date();
  const options = { 
    weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
    hour: '2-digit', minute: '2-digit', second: '2-digit',
    hour12: false
  };
  const formatted = now.toLocaleString('en-US', options);
  document.getElementById('liveDateTime').textContent = formatted;
}

// Update immediately, then every second
updateDateTime();
setInterval(updateDateTime, 1000);
</script>



</body>
</html>
