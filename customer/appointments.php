<?php
include('inc.header.php');

// Check login
if (!isset($_SESSION['UserID']) || !isset($_SESSION['UserType'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['UserID'];
$userType = $_SESSION['UserType'];
$appointments = [];

// Prepare query
$sql = "
    SELECT 
        a.appointment_id,
        a.customer_id,
        a.lawyer_id,
        a.schedule_date,
        a.status,
        a.note,
        a.created_at
    FROM appointments a
    WHERE " . ($userType === 'Customer' ? "a.customer_id = ?" : "a.lawyer_id = ?") . "
    ORDER BY a.schedule_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

$stmt->close();
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Appointments</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Appointments</li>
      </ol>
    </nav>
  </div>

  <section class="section appointments">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Your Appointments</h5>

        <table class="table table-bordered table-hover datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Appointment ID</th>
              <th>Customer ID</th>
              <th>Lawyer ID</th>
              <th>Schedule Date</th>
              <th>Status</th>
              <th>Note</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)): ?>
              <?php foreach ($appointments as $index => $a): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= $a['appointment_id'] ?></td>
                  <td><?= $a['customer_id'] ?></td>
                  <td><?= $a['lawyer_id'] ?></td>
                  <td><?= date('Y-m-d H:i', strtotime($a['schedule_date'])) ?></td>
                  <td>
                    <span class="badge bg-<?= 
                      $a['status'] === 'Pending' ? 'warning' : 
                      ($a['status'] === 'Confirmed' ? 'primary' : 'success') ?>">
                      <?= htmlspecialchars($a['status']) ?>
                    </span>
                  </td>
                  <td><?= $a['note'] ? htmlspecialchars($a['note']) : '—' ?></td>
                  <td><?= date('Y-m-d H:i', strtotime($a['created_at'])) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>

      </div>
    </div>
  </section>
</main>

<?php include('inc.footer.php'); ?>
