<?php
require('inc.connection.php');

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
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND user_type = 'Lawyer'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($lawyer = $result->fetch_assoc()) {
            if ($password === $lawyer['password']) {
                $_SESSION['LawyerLogin'] = true;
                $_SESSION['LawyerID'] = $lawyer['user_id'];
                $_SESSION['Name'] = $lawyer['name'];
                $_SESSION['Email'] = $lawyer['email'];

                header("Location: lawyer.dashboard.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Lawyer Login - Legal Services</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="col-lg-4 col-md-6">

          <div class="card mb-3">
            <div class="card-body">
              <div class="pt-4 pb-2 text-center">
                <h5 class="card-title fs-4">Lawyer Login</h5>
                <p class="small">Enter your credentials to log in</p>
                <?php if (!empty($loginError)) echo "<div class='alert alert-danger'>$loginError</div>"; ?>
              </div>

              <form method="post" action="" class="row g-3 needs-validation" novalidate>
                <div class="col-12">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" id="email" required>
                  <div class="invalid-feedback">
                    <?= $emailErr ?: "Please enter your email." ?>
                  </div>
                </div>

                <div class="col-12">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" id="password" required>
                  <div class="invalid-feedback">
                    <?= $passwordErr ?: "Please enter your password." ?>
                  </div>
                </div>

                <div class="col-12">
                  <button class="btn btn-primary w-100" type="submit" name="loginBtn">Login</button>
                </div>
              </form>

            </div>
          </div>

          <div class="credits text-center">
            Designed by <a href="#">Legal Services</a>
          </div>

        </div>
      </section>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Vendor JS -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
