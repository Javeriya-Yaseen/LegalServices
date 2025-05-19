<?php
include('inc.header.php');

// Fetch users
$users = [];
$sql = "SELECT users.*, cities.city_name 
        FROM users 
        JOIN cities ON users.city_id = cities.city_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Users</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item"><a href="lawyers.php">lawyers</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">User List</h5>
            <a href="user.create.php" class="btn btn-primary mb-3">Add New User</a>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>City</th>
                  <th>Type</th>
                  <th>Created At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($users)): ?>
                  <?php foreach ($users as $index => $user): ?>
                    <tr>
                      <th scope="row"><?= $index + 1 ?></th>
                      <td><?= htmlspecialchars($user['name']) ?></td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                      <td><?= htmlspecialchars($user['city_name']) ?></td>
                      <td><?= htmlspecialchars($user['user_type']) ?></td>
                      <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                      <td>
                        <a href="user.edit.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="user.delete.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center">No users found.</td></tr>
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
