<?php
require('inc.connection.php');
// session_start();

$email = $password = '';
$emailErr = $passwordErr = '';
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginBtn'])) {

    // Validation
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

    // Authenticate
    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'Customer'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($customer = $result->fetch_assoc()) {
            if (password_verify($password, $customer['password'])) {
                // Set session
                $_SESSION['CustomerLogin'] = true;
                $_SESSION['UserID'] = $customer['user_id'];
                $_SESSION['Name'] = $customer['name'];
                $_SESSION['Email'] = $customer['email'];
                $_SESSION['UserType'] = 'Customer';

                header("Location: index.php");
                exit;
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "Customer not found.";
        }

        $stmt->close();
    }
}
?>

<!-- Now your HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Customer Login - Legal Services</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<main>
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6">

            <div class="card mb-3">
              <div class="card-body">

                <div class="pt-4 pb-2 text-center">
                  <h5 class="card-title">Login to Your Account</h5>
                  <p class="text-muted small">Customer Access Only</p>
                </div>

                <?php if ($loginError): ?>
                  <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3 needs-validation" novalidate>

                  <div class="col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" required>
                    <div class="invalid-feedback"><?= $emailErr ?: 'Please enter your email.' ?></div>
                  </div>

                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?= $passwordErr ? 'is-invalid' : '' ?>" required>
                    <div class="invalid-feedback"><?= $passwordErr ?: 'Please enter your password.' ?></div>
                  </div>

                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                  </div>

                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit" name="loginBtn">Login</button>
                  </div>
                </form>

              </div>
            </div>

            <div class="text-center">
              <p class="small">Not a customer? <a href="register.php">Register here</a></p>
            </div>

          </div>
        </div>
      </div>
    </section>

  </div>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
