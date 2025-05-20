<?php
require('inc.header.php');

$lawyer_id = $_SESSION['LawyerID'];

// Handle status update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $new_status = $_POST['status'];

    // Validate status value
    $valid_statuses = ['Pending', 'Confirmed', 'Completed'];
    if (in_array($new_status, $valid_statuses)) {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ? AND lawyer_id = ?");
        $stmt->bind_param('sii', $new_status, $appointment_id, $lawyer_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['msg'] = "Appointment status updated successfully.";
        header('Location: appointments.php');
        exit;
    } else {
        $error = "Invalid status selected.";
    }
}

// Fetch appointments for this lawyer
$sql = "
SELECT a.*, u.name AS customer_name
FROM appointments a
JOIN users u ON a.customer_id = u.user_id
WHERE a.lawyer_id = ?
ORDER BY a.schedule_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $lawyer_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
?>

<?php include('inc.header.php'); ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>My Appointments</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Appointments</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <?php if (!empty($_SESSION['msg'])): ?>
          <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['msg']) ?>
            <?php unset($_SESSION['msg']); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <div class="card">
          <div class="card-body">

            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Customer</th>
                  <th>Scheduled Date</th>
                  <th>Status</th>
                  <th>Notes</th>
                  <th>Change Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($appointments) > 0): ?>
                  <?php foreach ($appointments as $index => $appt): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><?= htmlspecialchars($appt['customer_name']) ?></td>
                      <td><?= date('Y-m-d H:i', strtotime($appt['schedule_date'])) ?></td>
                      <td>
                        <span class="badge
                        <?php 
                          if ($appt['status'] === 'Pending') echo 'bg-warning text-dark';
                          elseif ($appt['status'] === 'Confirmed') echo 'bg-primary';
                          elseif ($appt['status'] === 'Completed') echo 'bg-success';
                          else echo 'bg-secondary';
                        ?>">
                        <?= htmlspecialchars($appt['status']) ?>
                        </span>
                      </td>
                      <td><?= htmlspecialchars($appt['notes']) ?: '-' ?></td>
                      <td>
                        <form method="post" class="d-flex align-items-center gap-2">
                          <input type="hidden" name="appointment_id" value="<?= $appt['appointment_id'] ?>">
                          <select name="status" class="form-select form-select-sm" required>
                            <option value="Pending" <?= $appt['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Confirmed" <?= $appt['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="Completed" <?= $appt['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                          </select>
                          <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center">No appointments found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php include('inc.footer.php'); ?>
