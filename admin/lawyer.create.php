<?php
include('inc.header.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialization_id = intval($_POST['specialization_id']);
    $experience_years = intval($_POST['experience_years']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    // Insert into Users
    $user_sql = "INSERT INTO users (name, email, password, user_type) 
                 VALUES ('$name', '$email', '$password', 'Lawyer')";

    if (mysqli_query($conn, $user_sql)) {
        $user_id = mysqli_insert_id($conn);

        // Insert into Lawyers_Profile
        $profile_sql = "INSERT INTO Lawyers_Profile (lawyer_id, specialization_id, experience_years, bio, contact_info)
                        VALUES ($user_id, $specialization_id, $experience_years, '$bio', '$contact_info')";

        if (mysqli_query($conn, $profile_sql)) {
            header('Location: lawyers.php');
            exit();
        } else {
            $error = "Error adding lawyer profile: " . mysqli_error($conn);
        }
    } else {
        $error = "Error adding user: " . mysqli_error($conn);
    }
}

// Fetch specializations for dropdown
$specializations = [];
$result = mysqli_query($conn, "SELECT * FROM Specializations ORDER BY specialization_name ASC");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $specializations[] = $row;
    }
}

// Fetch cities for dropdown
$cities = [];
$result = mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name ASC");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }
}
?>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Add New Lawyer</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="lawyers.php">Lawyers</a></li>
      <li class="breadcrumb-item active">Add Lawyer</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Lawyer Information</h5>

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
              <label class="col-sm-2 col-form-label">Specialization</label>
              <div class="col-sm-10">
                <select name="specialization_id" class="form-select" required>
                  <option value="">Select Specialization</option>
                  <?php foreach ($specializations as $spec): ?>
                    <option value="<?= $spec['specialization_id'] ?>">
                      <?= htmlspecialchars($spec['specialization_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">City</label>
              <div class="col-sm-10">
                <select name="city_id" class="form-select" required>
                  <option value="">Select City</option>
                  <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['city_id'] ?>">
                      <?= htmlspecialchars($city['city_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Experience (Years)</label>
              <div class="col-sm-10">
                <input type="number" name="experience_years" class="form-control" min="0" required>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Bio</label>
              <div class="col-sm-10">
                <textarea name="bio" class="form-control" rows="3" required></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Contact Info</label>
              <div class="col-sm-10">
                <input type="text" name="contact_info" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Create Lawyer</button>
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
