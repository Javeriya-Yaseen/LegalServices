<?php 
include('inc.header.php');
$email = $password = '';
$emailErr = $passwordErr = '';
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {
    if (empty($_POST['email'])) {
        $emailErr = "Email is required!";
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    }

    if (empty($_POST['password'])) {
        $passwordErr = "Password is required!";
    } else {
        $password = trim($_POST['password']);
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'Customer'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($lawyer = $result->fetch_assoc()) {
            if ($password === $lawyer['password']) {
                $_SESSION['CustomerLogin'] = true;
                $_SESSION['CustomerID'] = $lawyer['user_id'];
                $_SESSION['Name'] = $lawyer['name'];
                $_SESSION['Email'] = $lawyer['email'];

                header("Location: index.php");
                exit;
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "Lawyer not found.";
        }
        $stmt->close();
    }
} 
?>           

        <!-- Page Header Start -->
        <div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2>Login</h2>
                    </div>
                    <div class="col-12">
                        <a href="index.php">Home</a>
                        <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Login Form Start -->
        <div class="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="login-form">
                            <h2>Member Login</h2>
                            <form action="process_login.php" method="POST">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email address" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-dark btn-block" type="submit">Login</button>
                                    
                                </div>
                                <div class="form-group text-center">
                                    <p>Don’t have an account? <a href="register.php">Register here</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Login Form End -->

    <?php include('inc.footer.php'); ?>