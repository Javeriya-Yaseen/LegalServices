<?php include('inc.header.php');
  // Count Lawyers
  $lawyers_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'Lawyer'");
  $lawyers = mysqli_fetch_assoc($lawyers_query)['total'];

  // Count Customers
  $customers_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'Customer'");
  $customers = mysqli_fetch_assoc($customers_query)['total'];

  // Count Appointments (today)
  $today = date('Y-m-d');
  $appointments_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE DATE(schedule_date) = '$today'");
  $appointments = mysqli_fetch_assoc($appointments_query)['total'];
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="index.php">Home</a></li>
      </ol>
    </nav>
  </div>

  <section class="section dashboard">
    <div class="row">

      <!-- Dashboard Cards -->
      <div class="col-lg-8">
        <div class="row">

          <!-- Lawyers Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">Lawyers Registered <span>| All Time</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $lawyers ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>         

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">Customers Registered <span>| All Time</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-person-plus"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $customers ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      
      <!-- Appointments Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">Appointments Today <span>| <?= date('d M, Y') ?></span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?= $appointments ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>

      <!-- Right side section (Optional Widgets or Stats) -->
      <div class="col-lg-4">
        <!-- You can keep or remove the recent activity/news based on actual usage -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">System Summary</h5>
            <ul>
              <li>Total Users: <?= $lawyers + $customers ?></li>
              <li>Appointments Today: <?= $appointments ?></li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>
<?php include('inc.footer.php'); ?>
