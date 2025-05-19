<?php
include('inc.header.php');

// Fake login user (customer) for testing purpose — replace with your session logic
$customer_id = 3; // Example: $_SESSION['user_id']

// Fetch lawyers list
$lawyers = [];
$result = mysqli_query($conn, "SELECT user_id, name FROM users WHERE user_type = 'Lawyer' ORDER BY name ASC");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $lawyers[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lawyer_id = intval($_POST['lawyer_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $sql = "INSERT INTO appointments (customer_id, lawyer_id, appointment_date, notes)
            VALUES ($customer_id, $lawyer_id, '$appointment_date', '$notes')";

    if (mysqli_query($conn, $sql)) {
        header('Location: appointments.php?success=1');
        exit();
    } else {
        $error = "Error booking appointment: " . mysqli_error($conn);
    }
}
?>

<main id="main" class="main">

<div class="pagetitle">
  <h1>Book Appointment</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Book Appointment</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-8">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Appointment Form</h5>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Select Lawyer</label>
              <div class="col-sm-9">
                <select name="lawyer_id" class="form-select" required>
                  <option value="">-- Choose Lawyer --</option>
                  <?php foreach ($lawyers as $lawyer): ?>
                    <option value="<?= $lawyer['user_id'] ?>"><?= htmlspecialchars($lawyer['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Date & Time</label>
              <div class="col-sm-9">
                <input type="datetime-local" name="appointment_date" class="form-control" required>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-sm-3 col-form-label">Notes</label>
              <div class="col-sm-9">
                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes..."></textarea>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary">Book Appointment</button>
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
