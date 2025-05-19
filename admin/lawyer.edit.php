<?php
include('inc.connection.php');
include('inc.header.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$lawyer_id = intval($_GET['id']);
$error = '';

// Fetch lawyer data for form
$sql = "
    SELECT 
        u.name, u.email,
        lp.specialization_id, u.city_id, lp.experience_years, lp.bio, lp.contact_info
    FROM users u
    JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id
    WHERE u.user_id = $lawyer_id AND u.user_type = 'Lawyer'
";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Lawyer not found.";
    exit;
}
$lawyer = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $specialization_id = intval($_POST['specialization_id']);
    $city_id = intval($_POST['city_id']);
    $experience_years = intval($_POST['experience_years']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);

    $password_sql = "";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$password'";
    }

    // Update Users table
    $update_user = "
        UPDATE users 
        SET name = '$name',city_id = $city_id, email = '$email' $password_sql 
        WHERE user_id = $lawyer_id
    ";

    // Update Lawyers_Profile table
    $update_profile = "
        UPDATE lawyers_profile 
        SET specialization_id = $specialization_id,            
            experience_years = $experience_years,
            bio = '$bio',
            contact_info = '$contact_info'
        WHERE lawyer_id = $lawyer_id
    ";

    if (mysqli_query($conn, $update_user) && mysqli_query($conn, $update_profile)) {
        header("Location: lawyers.php");
        exit;
    } else {
        $error = "Failed to update: " . mysqli_error($conn);
    }
}

// Fetch specializations and cities
$specializations = [];
$res = mysqli_query($conn, "SELECT * FROM Specializations ORDER BY specialization_name ASC");
while ($row = mysqli_fetch_assoc($res)) {
    $specializations[] = $row;
}

$cities = [];
$res = mysqli_query($conn, "SELECT * FROM Cities ORDER BY city_name ASC");
while ($row = mysqli_fetch_assoc($res)) {
    $cities[] = $row;
}
?>

<main id="main" class="main">
<div class="pagetitle">
  <h1>Edit Lawyer</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="lawyers.php">Lawyers</a></li>
      <li class="breadcrumb-item active">Edit Lawyer</li>
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
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($lawyer['name']) ?>">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($lawyer['email']) ?>">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">New Password (Optional)</label>
              <div class="col-sm-10">
                <input type="password" name="password" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Specialization</label>
              <div class="col-sm-10">
                <select name="specialization_id" class="form-select" required>
                  <option value="">Select Specialization</option>
                  <?php foreach ($specializations as $spec): ?>
                    <option value="<?= $spec['specialization_id'] ?>" <?= $lawyer['specialization_id'] == $spec['specialization_id'] ? 'selected' : '' ?>>
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
                    <option value="<?= $city['city_id'] ?>" <?= $lawyer['city_id'] == $city['city_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($city['city_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Experience (Years)</label>
              <div class="col-sm-10">
                <input type="number" name="experience_years" class="form-control" min="0" required value="<?= htmlspecialchars($lawyer['experience_years']) ?>">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Bio</label>
              <div class="col-sm-10">
                <textarea name="bio" class="form-control" rows="3" required><?= htmlspecialchars($lawyer['bio']) ?></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Contact Info</label>
              <div class="col-sm-10">
                <input type="text" name="contact_info" class="form-control" required value="<?= htmlspecialchars($lawyer['contact_info']) ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">Update Lawyer</button>
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
