<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login</title>

    <!-- CSS -->
    <link href="<?php echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet" />

    <style>
        /* Fixed width for login card */
        .login-card {
            width: 500px; /* fixed width */
            max-width: 90%; /* ensures it shrinks on smaller screens */
        }
    </style>
</head>
<body class="bg-primary d-flex align-items-center justify-content-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <!-- Responsive column -->
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <!-- Login Card -->
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-dark text-center">
                        <h2 class="text-white m-0">Login</h2>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('login/verify', ['onsubmit' => 'return validateLoginForm();']); ?>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" />
                                <label for="inputEmail">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" />
                                <label for="inputPassword">Password</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="inputRememberPassword" name="remember" type="checkbox" value="1" />
                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <a class="small" href="<?php echo base_url('login/forgot_password'); ?>">Forgot Password?</a>
                                <button type="submit" class="btn btn-dark">Login</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="<?php echo base_url('register'); ?>">Need an account? Sign up!</a></div>
                    </div>
                </div>
                <!-- End Login Card -->
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.onload = function() {
        if (performance.navigation.type === 2) {
            // User clicked "Back" button
            location.reload(true);
        }
    };
</script>

    <script>
        
    function validateLoginForm() {
        var email = document.getElementById("inputEmail").value.trim();
        var password = document.getElementById("inputPassword").value.trim();

        if (email === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Please enter your email!',
            });
            return false;
        }

        if (password === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Please enter your password!',
            });
            return false;
        }

        return true;
    }
    </script>

    <?php if($errors = $this->session->flashdata('login_errors')): ?>
    <script>
    $(document).ready(function() {
        <?php foreach($errors as $key => $error): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $error; ?>',
        });
        <?php endforeach; ?>
    });
    </script>
    <?php endif; ?>
</body>

</html>
