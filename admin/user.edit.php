<?php
include('inc.header.php');
include('inc.connection.php');

// --- INITIAL SETUP ---
$success = "";
$error = "";

// Get user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID.");
}

$user_id = intval($_GET['id']);

// --- PROCESS FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // optional
    $user_type = $_POST['user_type'];
    $city_id = intval($_POST['city_id']);

    // Validate inputs (basic)
    if (empty($name) || empty($email) || empty($user_type) || empty($city_id)) {
        $error = "Please fill all required fields.";
    } else {
        // Prepare query
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "UPDATE users SET name=?, email=?, password=?, user_type=?, city_id=? WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssii", $name, $email, $hashed_password, $user_type, $city_id, $user_id);
        } else {
            $query = "UPDATE users SET name=?, email=?, user_type=?, city_id=? WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssii", $name, $email, $user_type, $city_id, $user_id);
        }

        // Execute and check
        if (mysqli_stmt_execute($stmt)) {
            $success = "User updated successfully.";
            // Optional redirect:
            // header("Location: users.php"); exit;
        } else {
            $error = "Error updating user: " . mysqli_error($conn);
        }
    }
}

// --- FETCH USER INFO ---
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
if (!$user_result || mysqli_num_rows($user_result) !== 1) {
    die("User not found.");
}
$user = mysqli_fetch_assoc($user_result);

// --- FETCH CITIES ---
$cities = [];
$cityResult = mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name ASC");
if ($cityResult && mysqli_num_rows($cityResult) > 0) {
    while ($row = mysqli_fetch_assoc($cityResult)) {
        $cities[] = $row;
    }
}
?>

<main id="main" class="main">
<div class="pagetitle">
  <h1>Edit User</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="users.php">Users</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">User Information</h5>

          <form method="POST">
            <!-- Name -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
              </div>
            </div>

            <!-- Email -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>
            </div>

            <!-- Password (optional) -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">New Password</label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
              </div>
            </div>

            <!-- User Type -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">User Type</label>
              <div class="col-sm-10">
                <select name="user_type" class="form-select" required>
                  <option value="">Select User Type</option>
                  <option value="Customer" <?= $user['user_type'] == 'Customer' ? 'selected' : '' ?>>Customer</option>
                  <option value="Lawyer" <?= $user['user_type'] == 'Lawyer' ? 'selected' : '' ?>>Lawyer</option>
                  <option value="Admin" <?= $user['user_type'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                </select>
              </div>
            </div>

            <!-- City -->
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">City</label>
              <div class="col-sm-10">
                <select name="city_id" class="form-select" required>
                  <option value="">Select City</option>
                  <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['city_id'] ?>" <?= $user['city_id'] == $city['city_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($city['city_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Submit -->
            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="users.php" class="btn btn-secondary">Cancel</a>
              </div>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</section>
</main>

<?php include('inc.footer.php'); ?>
