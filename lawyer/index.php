<?php
include('inc.header.php');

if (!isset($_SESSION['LawyerID'])) {
    header('Location: login.php');
    exit;
}

$lawyer_id = $_SESSION['LawyerID'];

// Fetch counts and stats related to the logged-in lawyer
// Total appointments
$totalAppointmentsRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE lawyer_id = $lawyer_id");
$totalAppointments = mysqli_fetch_assoc($totalAppointmentsRes)['total'] ?? 0;

// Pending appointments
$pendingAppointmentsRes = mysqli_query($conn, "SELECT COUNT(*) AS pending FROM appointments WHERE lawyer_id = $lawyer_id AND status = 'Pending'");
$pendingAppointments = mysqli_fetch_assoc($pendingAppointmentsRes)['pending'] ?? 0;

// Confirmed appointments
$confirmedAppointmentsRes = mysqli_query($conn, "SELECT COUNT(*) AS confirmed FROM appointments WHERE lawyer_id = $lawyer_id AND status = 'Confirmed'");
$confirmedAppointments = mysqli_fetch_assoc($confirmedAppointmentsRes)['confirmed'] ?? 0;

// Total customers served (distinct customers in appointments)
$customersServedRes = mysqli_query($conn, "SELECT COUNT(DISTINCT customer_id) AS customers FROM appointments WHERE lawyer_id = $lawyer_id");
$customersServed = mysqli_fetch_assoc($customersServedRes)['customers'] ?? 0;
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="index.php">Home</a></li>        
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Total Appointments Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card appointments-card">

              <div class="card-body">
                <h5 class="card-title">Total Appointments <span>| All Time</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $totalAppointments ?></h6>
                    <span class="text-muted small pt-2 ps-1">appointments</span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Total Appointments Card -->

          <!-- Pending Appointments Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card pending-card">

              <div class="card-body">
                <h5 class="card-title">Pending Appointments <span>| Awaiting Confirmation</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-hourglass-split"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $pendingAppointments ?></h6>
                    <span class="text-warning small pt-1 fw-bold"><?= $totalAppointments > 0 ? round(($pendingAppointments/$totalAppointments)*100, 1) : 0 ?>%</span>
                    <span class="text-muted small pt-2 ps-1">of total</span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Pending Appointments Card -->

          <!-- Confirmed Appointments Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card confirmed-card">

              <div class="card-body">
                <h5 class="card-title">Confirmed Appointments <span>| Scheduled</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-check-circle"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $confirmedAppointments ?></h6>
                    <span class="text-success small pt-1 fw-bold"><?= $totalAppointments > 0 ? round(($confirmedAppointments/$totalAppointments)*100, 1) : 0 ?>%</span>
                    <span class="text-muted small pt-2 ps-1">of total</span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Confirmed Appointments Card -->

          <!-- Customers Served Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card customers-card">

              <div class="card-body">
                <h5 class="card-title">Customers Served <span>| Unique Clients</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $customersServed ?></h6>
                    <span class="text-muted small pt-2 ps-1">clients</span>
                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Customers Served Card -->

        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Recent Appointments List -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Recent Appointments</h5>
            <ul class="list-group list-group-flush">
              <?php
              $recentAppointmentsRes = mysqli_query($conn, "
                SELECT a.*, u.name AS customer_name 
                FROM appointments a 
                JOIN users u ON a.customer_id = u.user_id
                WHERE a.lawyer_id = $lawyer_id
                ORDER BY a.schedule_date DESC
                LIMIT 5
              ");
              if ($recentAppointmentsRes && mysqli_num_rows($recentAppointmentsRes) > 0):
                while ($app = mysqli_fetch_assoc($recentAppointmentsRes)):
              ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?= htmlspecialchars($app['customer_name']) ?></strong><br>
                    <small><?= date('Y-m-d H:i', strtotime($app['schedule_date'])) ?></small>
                  </div>
                  <span class="badge 
                    <?= $app['status'] === 'Confirmed' ? 'bg-success' : ($app['status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                    <?= htmlspecialchars($app['status']) ?>
                  </span>
                </li>
              <?php
                endwhile;
              else:
              ?>
                <li class="list-group-item">No recent appointments found.</li>
              <?php endif; ?>
            </ul>
          </div>
        </div><!-- End Recent Appointments -->

      </div><!-- End Right side columns -->

    </div>
  </section>

</main><!-- End #main -->

<?php include('inc.footer.php'); ?>
