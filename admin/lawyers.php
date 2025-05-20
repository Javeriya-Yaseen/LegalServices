<?php
include('inc.header.php');

// Fetch all lawyers with specialization and profile
$lawyers = [];
$query = "
  SELECT 
    u.user_id,
    u.name,
    u.created_at,
    s.specialization_name,
    lp.experience_years,
    lp.bio,
    lp.contact_info
  FROM users u
  Left JOIN lawyers_profile lp ON u.user_id = lp.lawyer_id
  Left JOIN specializations s ON lp.specialization_id = s.specialization_id
  WHERE u.user_type = 'Lawyer'
  ORDER BY u.created_at DESC
";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $lawyers[] = $row;
  }
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Lawyers</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Lawyers</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Lawyer List</h5>
            <a href="lawyer.create.php" class="btn btn-primary mb-3">+ Add New Lawyer</a>

            <div class="table-responsive">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Lawyer ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Experience (Years)</th>
                    <th>Bio</th>
                    <th>Contact Info</th>
                    <th>Joined On</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($lawyers)): ?>
                    <?php foreach ($lawyers as $index => $lawyer): ?>
                      <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= (int)$lawyer['user_id'] ?></td>
                        <td><?= htmlspecialchars($lawyer['name']) ?></td>
                        <td><?= htmlspecialchars($lawyer['specialization_name']) ?></td>
                        <td><?= (int)$lawyer['experience_years'] ?></td>
                        <td><?= htmlspecialchars($lawyer['bio']) ?></td>
                        <td><?= htmlspecialchars($lawyer['contact_info']) ?></td>
                        <td><?= date('Y-m-d', strtotime($lawyer['created_at'])) ?></td>
                        <td>
                          <a href="lawyer.edit.php?id=<?= $lawyer['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                          <a href="lawyer.delete.php?id=<?= $lawyer['user_id'] ?>" class="btn btn-sm btn-danger"
                             onclick="return confirm('Are you sure you want to delete this lawyer?');">Delete</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="9" class="text-center text-muted">No lawyers found.</td>
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
