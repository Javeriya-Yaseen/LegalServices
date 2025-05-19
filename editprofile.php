<?php include('inc.header.php');
if(!$_SESSION['CustomerLogin']) {
    header('Location:login.php');
} ?>
            
<body>
<div class="wrapper">
    <!-- Top Bar and Nav Bar -->
    <!-- [Use top-bar and nav-bar from your template] -->

    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Edit Profile</h2>
                </div>
                <div class="col-12">
                    <a href="index.php">Home</a>
                    <a href="#">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Edit Profile Form Start -->
    <div class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Update Your Information</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter full name" value="John Doe">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email" value="johndoe@example.com">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" placeholder="Enter phone number" value="+1234567890">
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" placeholder="Enter city" value="New York">
                            </div>
                            <div class="form-group">
                                <label>User Type</label>
                                <select class="form-control">
                                    <option selected>Lawyer</option>
                                    <option>Customer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Specialization</label>
                                <input type="text" class="form-control" placeholder="Civil Law, Corporate Law" value="Civil Law, Corporate Law">
                            </div>
                            <div class="form-group">
                                <label>Experience (Years)</label>
                                <input type="number" class="form-control" placeholder="Years of experience" value="10">
                            </div>
                            <div class="form-group">
                                <label>Bio</label>
                                <textarea class="form-control" rows="4" placeholder="Brief bio...">Experienced in civil and corporate law with over a decade of legal practice.</textarea>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Enter new password (optional)">
                            </div>
                            <button type="submit" class="btn">Save Changes</button>
                            <a href="userpofile.php" class="btn btn-outline-secondary ml-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Profile Form End -->

    <!-- Newsletter & Footer -->
    <!-- [Use newsletter and footer from your template] -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
</div>
<?php include('inc.footer.php'); ?>