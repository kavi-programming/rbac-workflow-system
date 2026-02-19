<!DOCTYPE html>
<html>
    <head>
        <title>Login - Workflow System</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <style>
            body {
                background: #f2f2f2;
                height: 100vh;
                font-family: 'Segoe UI', sans-serif;
            }

            .login-card {
                border-radius: 20px;
                background-color: #ffffff;
                padding: 30px 25px;
            }

            .login-title {
                font-size: 32px;
                font-weight: 700;
                text-align: center;
                margin-bottom: 30px;
            }

            .form-group {
                position: relative;
                margin-bottom: 25px;
            }

            .form-group i {
                position: absolute;
                top: 50%;
                left: 10px;
                transform: translateY(-50%);
                color: #999;
            }

            .form-control {
                border: none;
                border-bottom: 1px solid #ccc;
                border-radius: 0;
                padding-left: 35px;
                box-shadow: none;
            }

            .form-control:focus {
                border-bottom: 2px solid #6c63ff;
                box-shadow: none;
            }

            .forgot-password {
                font-size: 14px;
                text-align: right;
                display: block;
                margin-top: -10px;
                margin-bottom: 20px;
                color: #666;
                text-decoration: none;
            }

            .forgot-password:hover {
                text-decoration: underline;
            }

            .btn-login {
                background: linear-gradient(to right, #00c6ff, #ff00cc);
                border: none;
                border-radius: 30px;
                padding: 10px;
                font-weight: 600;
                color: white;
                transition: 0.3s ease;
            }

            .btn-login:hover {
                opacity: 0.9;
            }
            .input-wrapper {
                position: relative;
            }

            .input-wrapper i {
                position: absolute;
                top: 50%;
                left: 10px;
                transform: translateY(-50%);
                color: #999;
            }

        </style>
    </head>

    <body class="d-flex justify-content-center align-items-center">

        <div class="col-md-4">
            <div class="card login-card shadow">

                <h4 class="login-title">Login</h4>

                <!-- Success Message -->
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('login') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fa fa-user"></i>
                            <input type="email" name="email" class="form-control" placeholder="Type your Email Address" value="<?= old('email') ?>">
                        </div>
                        <?php if(session('errors.email')): ?>
                            <div class="text-danger small mt-1">
                                <?= session('errors.email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Type your Password">
                        </div>
                        <?php if(session('errors.password')): ?>
                            <div class="text-danger small mt-1">
                                <?= session('errors.password') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-login w-100">LOGIN</button>
                </form>
            </div>
        </div>
    </body>
</html>
