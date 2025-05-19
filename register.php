<?php include('inc.header.php') ?>
            

    <style>
        .login-form {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .login-form h2 {
            font-family: 'EB Garamond', serif;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .login-form .form-control {
            height: 45px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Top Bar Start -->
        <!-- (Same Top Bar Code as login.html) -->
        <!-- Top Bar End -->

        <!-- Nav Bar Start -->
        <!-- (Same Nav Bar Code as login.html) -->
        <!-- Nav Bar End -->
       
        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Register</h2>
                    </div>
                    <div class="col-12">
                        <a href="index.php">Home</a>
                        <a href="register.php">Register</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Register Form Start -->
        <div class="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="login-form">
                            <h2>Create an Account</h2>
                            <form action="process_register.php" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-dark btn-block" type="submit">Register</button>
                                </div>
                                <div class="form-group text-center">
                                    <p>Already have an account? <a href="login.php">Login here</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Register Form End -->

        <!-- Footer Start -->
        <!-- (Same Footer Code as login.html) -->
        <!-- Footer End -->

        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    </div>
 <?php include('inc.footer.php'); ?>