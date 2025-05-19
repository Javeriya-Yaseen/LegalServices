<?php
require('inc.connection.php');


$name = $email = $password = '';
$nameErr = $emailErr = $passwordErr = '';
$registerError = $successMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerBtn'])) {
    // Validate name
    if (empty($_POST['name'])) {
        $nameErr = "Name is required!";
    } else {
        $name = trim($_POST['name']);
    }

    // Validate email
    if (empty($_POST['email'])) {
        $emailErr = "Email is required!";
    } else {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        // Check for duplicate
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $emailErr = "Email already registered!";
        }
        $stmt->close();
    }

    // Validate password
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required!";
    } else {
        $password = trim($_POST['password']);
    }

    // If no validation errors
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'Customer')");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        if ($stmt->execute()) {
            $successMsg = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $registerError = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Registration - Legal Services</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex align-items-center justify-content-center">
      <div class="col-lg-4 col-md-6">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center">Create Your Account</h5>

            <?php if ($registerError): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
            <?php elseif ($successMsg): ?>
              <div class="alert alert-success"><?= $successMsg ?></div>
            <?php endif; ?>

            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3 needs-validation" novalidate>

              <div class="col-12">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control <?= $nameErr ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($name) ?>" required>
                <div class="invalid-feedback"><?= $nameErr ?: 'Please enter your name.' ?></div>
              </div>

              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control <?= $emailErr ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($email) ?>" required>
                <div class="invalid-feedback"><?= $emailErr ?: 'Please enter your email.' ?></div>
              </div>

              <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?= $passwordErr ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback"><?= $passwordErr ?: 'Please enter your password.' ?></div>
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit" name="registerBtn">Register</button>
              </div>
              <div class="col-12 text-center">
                <p class="small">Already have an account? <a href="login.php">Login here</a></p>
              </div>
            </form>

          </div>
        </div>

      </div>
    </section>
  </div>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
