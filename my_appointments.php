<?php
include('inc.header.php');

// Check if Customer is logged in
if (!isset($_SESSION['CustomerLogin']) || !$_SESSION['CustomerLogin']) {
    header("Location: login.php");
    exit;
}

$customer_id = $_SESSION['CustomerID'];

// Fetch customer's appointments
$stmt = $conn->prepare("
    SELECT a.appointment_id, a.schedule_date, a.status, u.name AS lawyer_name, s.specialization_name 
    FROM appointments a
    JOIN users u ON a.lawyer_id = u.user_id
    LEFT JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id
    LEFT JOIN specializations s ON lp.specialization_id = s.specialization_id
    WHERE a.customer_id = ?
    ORDER BY a.schedule_date DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>My Appointments</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="#">My Appointments</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Appointments List Start -->
<div class="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="login-form">
                    <h3>Your Appointments</h3>
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Lawyer Name</th>
                                    <th>Specialization</th>
                                    <th>Schedule Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $count++ ?></td>
                                        <td><?= htmlspecialchars($row['lawyer_name']) ?></td>
                                        <td><?= htmlspecialchars($row['specialization_name'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($row['schedule_date']) ?></td>
                                        <td><span class="badge badge-<?= $row['status'] === 'Pending' ? 'warning' : 'success' ?>">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>You have no appointments yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Appointments List End -->

<?php include('inc.footer.php'); ?>
