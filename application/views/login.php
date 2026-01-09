<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>

    <!-- CSS -->
    <link href="<?php echo base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }

        .login-wrapper {
            max-width: 900px;
            width: 100%;
        }

        .login-card {
            border-radius: 16px;
            overflow: hidden;
            animation: fadeUp 0.8s ease;
        }

        /* LEFT IMAGE PANEL */
        .login-image {
            background: url("<?php echo base_url('img/a.jpg'); ?>") center/cover no-repeat;
            position: relative;
            animation: zoomFade 8s infinite alternate ease-in-out;
        }

        .login-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
        }

        .login-image h2 {
            position: relative;
            z-index: 1;
            color: #fff;
            font-weight: 700;
        }

        /* FORM */
        .form-control {
            border-radius: 10px;
        }

        .btn-login {
            border-radius: 30px;
            padding: 10px 30px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* ANIMATIONS */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomFade {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.08);
            }
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .login-image {
                display: none;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="container login-wrapper">
    <div class="card shadow-lg login-card border-0">
        <div class="row no-gutters">

            <!-- IMAGE SECTION -->
            <div class="col-md-6 d-flex align-items-center justify-content-center login-image">
                <!-- <h2 class="text-center px-4">Welcome Back 👋</h2> -->
            </div>

            <!-- FORM SECTION -->
            <div class="col-md-6 p-5 bg-white">
                <h3 class="text-center font-weight-bold mb-4">Login</h3>

                <?php echo form_open('login/verify', ['onsubmit' => 'return validateLoginForm();']); ?>

                    <div class="form-group">
                        <label>Email address</label>
                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password">
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" id="inputRememberPassword" name="remember" type="checkbox" value="1">
                        <label class="form-check-label" for="inputRememberPassword">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-login">
                        Login
                    </button>

                    <div class="text-center mt-3">
                        <a class="small" href="<?php echo base_url('login/forgot_password'); ?>">Forgot Password?</a>
                    </div>

                <?php echo form_close(); ?>

                <hr>
                <div class="text-center">
                    <!-- <small>Need an account?
                        <a href="<?php echo base_url('register'); ?>">Sign up</a>
                    </small> -->
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function validateLoginForm() {
    const email = inputEmail.value.trim();
    const password = inputPassword.value.trim();

    if (!email) {
        Swal.fire('Oops!', 'Please enter your email', 'warning');
        return false;
    }

    if (!password) {
        Swal.fire('Oops!', 'Please enter your password', 'warning');
        return false;
    }
    return true;
}
</script>

<?php if($errors = $this->session->flashdata('login_errors')): ?>
<script>
$(function(){
    <?php foreach($errors as $error): ?>
    Swal.fire('Error', '<?php echo $error; ?>', 'error');
    <?php endforeach; ?>
});
</script>
<?php endif; ?>

</body>
</html>
