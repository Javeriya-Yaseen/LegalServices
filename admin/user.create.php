<?php
include('inc.connection.php'); // ensure this includes your $conn connection
include('inc.header.php');
include_once('inc.functions.php');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure hash
    $user_type = $_POST['user_type'];
    $city_id = intval($_POST['city_id']);

    $sql = "INSERT INTO Users (name, email, password, user_type, city_id)
            VALUES ('$name', '$email', '$password', '$user_type', $city_id)";

    if (mysqli_query($conn, $sql)) {
        header('Location: users.php');
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch cities for dropdown
$cities = [];
$cityResult = mysqli_query($conn, "SELECT * FROM Cities ORDER BY city_name ASC");
if ($cityResult && mysqli_num_rows($cityResult) > 0) {
    while ($row = mysqli_fetch_assoc($cityResult)) {
        $cities[] = $row;
    }
}
?>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Add New User</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="users.php">Users</a></li>
      <li class="breadcrumb-item active">Add User</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">User Information</h5>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>
            
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" name="email" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">User Type</label>
              <div class="col-sm-10">
                <select name="user_type" class="form-select" required>
                  <option value="">Select User Type</option>
                  <option value="Customer">Customer</option>
                  <option value="Lawyer">Lawyer</option>
                  <option value="Admin">Admin</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">City</label>
              <div class="col-sm-10">
                <select name="city_id" class="form-select" required>
                  <option value="">Select City</option>
                  <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['city_id'] ?>"><?= htmlspecialchars($city['city_name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Create User</button>
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
