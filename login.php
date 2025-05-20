<?php 
include('inc.header.php');  // Make sure this includes session_start() and DB connection ($conn)

$email = $password = '';
$emailErr = $passwordErr = '';
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    if (empty($_POST['email'])) {
        $emailErr = "Email is required!";
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format!";
        }
    }

    // Validate password
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required!";
    } else {
        $password = trim($_POST['password']);
    }

    // Proceed if no validation errors
    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'Customer'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user found
        if ($user = $result->fetch_assoc()) {
            // Verify password using password_verify if hashed passwords
            // Assuming your DB stores plain text passwords (not recommended), do plain compare (not secure)
            // For security, please hash passwords with password_hash() when registering users

            // Example if passwords are hashed:
            // if (password_verify($password, $user['password'])) {
            //     // Login success
            // }

            if ($password === $user['password']) {
                // Set session variables
                $_SESSION['CustomerLogin'] = true;
                $_SESSION['CustomerID'] = $user['user_id'];
                $_SESSION['UserType'] = $user['user_type'];
                $_SESSION['Name'] = $user['name'];
                $_SESSION['Email'] = $user['email'];

                header("Location: index.php");
                exit;
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "User not found.";
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

                    <?php if (!empty($loginError)): ?>
                        <div class="alert alert-danger"><?php echo $loginError; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <input 
                              type="email" 
                              class="form-control <?php echo !empty($emailErr) ? 'is-invalid' : ''; ?>" 
                              name="email" 
                              placeholder="Email address" 
                              value="<?php echo htmlspecialchars($email); ?>" 
                              required>
                            <div class="invalid-feedback"><?php echo $emailErr; ?></div>
                        </div>
                        <div class="form-group">
                            <input 
                              type="password" 
                              class="form-control <?php echo !empty($passwordErr) ? 'is-invalid' : ''; ?>" 
                              name="password" 
                              placeholder="Password" 
                              required>
                            <div class="invalid-feedback"><?php echo $passwordErr; ?></div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-dark btn-block" type="submit" name="loginBtn">Login</button>
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
