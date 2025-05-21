<?php
include('inc.header.php');

// Get logged-in admin ID
$user_id = $_SESSION['LawyerID'] ?? 0;

if ($user_id == 0) {
  echo "<script>alert('Unauthorized access'); window.location.href='login.php';</script>";
  exit;
}

// Get user details with city name
$user_query = "
  SELECT u.*, c.city_name 
  FROM users u
  LEFT JOIN cities c ON u.city_id = c.city_id
  WHERE u.user_id = $user_id
";
$user = mysqli_fetch_assoc(mysqli_query($conn, $user_query));

// Determine profile image
$photo_path = $user['profile_photo'] 
    ? 'uploads/profiles/' . $user['profile_photo'] 
    : 'assets/img/default-user.png';

// Fetch city list for dropdown
$cities = mysqli_query($conn, "SELECT city_id, city_name FROM cities ORDER BY city_name ASC");
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Profile</li>
      </ol>
    </nav>
  </div>

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img src="<?= $photo_path ?>" alt="Profile" class="rounded-circle" style="width: 120px; height: 120px;">
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <h3><?= htmlspecialchars($user['user_type']) ?></h3>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">

            <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>
            </ul>

            <div class="tab-content pt-2">

              <!-- Overview -->
              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                <h5 class="card-title">Profile Details</h5>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Full Name</div>
                  <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['name']) ?></div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">City</div>
                  <div class="col-lg-9 col-md-8"><?= $user['city_name'] ?? 'N/A' ?></div>
                </div>
              </div>

              <!-- Edit Profile -->
              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                <form method="post" enctype="multipart/form-data" action="profile.update.php">
                  <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                    <div class="col-md-8 col-lg-9">
                      <img src="<?= $photo_path ?>" alt="Profile" class="mb-2" style="width: 100px;">
                      <input type="file" name="profile_photo" class="form-control">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="name" type="text" class="form-control" value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="city_id" class="col-md-4 col-lg-3 col-form-label">City</label>
                    <div class="col-md-8 col-lg-9">
                      <select name="city_id" class="form-select" required>
                        <option value="">Select City</option>
                        <?php while ($city = mysqli_fetch_assoc($cities)): ?>
                          <option value="<?= $city['city_id'] ?>" <?= $city['city_id'] == $user['city_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city['city_name']) ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>

              <!-- Change Password -->
              <div class="tab-pane fade pt-3" id="profile-change-password">
                <form method="post" action="profile.password.php">
                  <div class="row mb-3">
                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="current_password" type="password" class="form-control">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="new_password" type="password" class="form-control">
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="confirm_password" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="confirm_password" type="password" class="form-control">
                    </div>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </div>
                </form>
              </div>

            </div><!-- End Tabs -->

          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include('inc.footer.php'); ?>
