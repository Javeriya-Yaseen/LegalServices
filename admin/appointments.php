<?php
include('inc.header.php');

// Fetch appointments with customer and lawyer details
$appointments = [];
$query = "
  SELECT 
    a.appointment_id,
    a.schedule_date,
    a.status,
    a.notes,
    a.created_at,
    c.name AS customer_name,
    l.name AS lawyer_name,
    s.specialization_name
  FROM appointments a
  JOIN users c ON a.customer_id = c.user_id
  JOIN users l ON a.lawyer_id = l.user_id
  LEFT JOIN lawyers_profile lp ON l.user_id = lp.lawyer_id
  LEFT JOIN specializations s ON lp.specialization_id = s.specialization_id
  ORDER BY a.schedule_date DESC
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $appointments[] = $row;
  }
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Appointments</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Appointments</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Appointment List</h5>
            <a href="appointment.create.php" class="btn btn-primary mb-3">+ Add New Appointment</a>

            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Appointment ID</th>
                    <th>Customer</th>
                    <th>Lawyer</th>
                    <th>Specialization</th>
                    <th>Schedule Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Created At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $index => $appt): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= (int)$appt['appointment_id'] ?></td>
                        <td><?= htmlspecialchars($appt['customer_name']) ?></td>
                        <td><?= htmlspecialchars($appt['lawyer_name']) ?></td>
                        <td><?= htmlspecialchars($appt['specialization_name'] ?? 'N/A') ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($appt['schedule_date'])) ?></td>
                        <td>
                          <span class="badge 
                            <?= $appt['status'] == 'Completed' ? 'bg-success' : 
                                 ($appt['status'] == 'Confirmed' ? 'bg-primary' : 'bg-warning') ?>">
                            <?= $appt['status'] ?>
                          </span>
                        </td>
                        <td><?= htmlspecialchars($appt['notes'] ?? '-') ?></td>
                        <td><?= date('Y-m-d', strtotime($appt['created_at'])) ?></td>
                        <td>
                          <a href="appointment.edit.php?id=<?= $appt['appointment_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                          <a href="appointment.delete.php?id=<?= $appt['appointment_id'] ?>" class="btn btn-sm btn-danger"
                             onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="10" class="text-center text-muted">No appointments found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include('inc.footer.php'); ?>
