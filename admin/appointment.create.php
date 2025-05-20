<?php
include('inc.header.php');

// Fetch customers and lawyers for dropdowns
$customers = mysqli_query($conn, "SELECT user_id, name FROM users WHERE user_type = 'Customer' ORDER BY name ASC");
$lawyers = mysqli_query($conn, "
  SELECT u.user_id, u.name, s.specialization_name 
  FROM users u 
  JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id 
  JOIN specializations s ON lp.specialization_id = s.specialization_id
  WHERE u.user_type = 'Lawyer'
  ORDER BY u.name ASC
");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $lawyer_id = $_POST['lawyer_id'];
    $schedule_date = $_POST['schedule_date'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $insert = mysqli_query($conn, "
        INSERT INTO appointments (customer_id, lawyer_id, schedule_date, notes)
        VALUES ('$customer_id', '$lawyer_id', '$schedule_date', '$notes')
    ");

    if ($insert) {
        echo "<script>alert('Appointment created successfully'); window.location.href='appointments.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Failed to create appointment: " . mysqli_error($conn) . "</div>";
    }
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Create Appointment</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="appointments.php">Appointments</a></li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-8">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">New Appointment</h5>

            <form method="post">
              <div class="mb-3">
                <label for="customer_id" class="form-label">Customer</label>
                <select name="customer_id" id="customer_id" class="form-select" required>
                  <option value="">Select Customer</option>
                  <?php while ($row = mysqli_fetch_assoc($customers)): ?>
                    <option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="lawyer_id" class="form-label">Lawyer</label>
                <select name="lawyer_id" id="lawyer_id" class="form-select" required>
                  <option value="">Select Lawyer</option>
                  <?php while ($row = mysqli_fetch_assoc($lawyers)): ?>
                    <option value="<?= $row['user_id'] ?>">
                      <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['specialization_name']) ?>)
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="schedule_date" class="form-label">Schedule Date & Time</label>
                <input type="datetime-local" name="schedule_date" id="schedule_date" class="form-control" required>
              </div>

              <div class="mb-3">
                <label for="notes" class="form-label">Notes (optional)</label>
                <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
              </div>

              <button type="submit" class="btn btn-success">Create Appointment</button>
              <a href="appointments.php" class="btn btn-secondary">Cancel</a>
            </form>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include('inc.footer.php'); ?>
