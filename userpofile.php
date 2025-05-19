<?php include('inc.header.php');
if(!$_SESSION['CustomerLogin']) {
    header('Location:login.php');
} ?>
            
<body>
<div class="wrapper">
    <!-- Top Bar -->
    <!-- [Use existing top-bar and navbar here as-is] -->

    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>User Profile</h2>
                </div>
                <div class="col-12">
                    <a href="index.php">Home</a>
                    <a href="#">User Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Profile Section Start -->
    <div class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Profile Details</h2>
            </div>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="img/userprofile.jpg" alt="User Photo" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                    <h3 class="mb-1">John Doe</h3>
                    <p><i class="fas fa-user-tag"></i> Lawyer</p>
                    <p><i class="fas fa-map-marker-alt"></i> New York, USA</p>
                </div>
                <div class="col-md-8">
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fa fa-envelope"></i>
                            <div class="contact-text">
                                <h2>Email</h2>
                                <p>johndoe@example.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-phone-alt"></i>
                            <div class="contact-text">
                                <h2>Phone</h2>
                                <p>+123 456 7890</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-briefcase"></i>
                            <div class="contact-text">
                                <h2>Specialization</h2>
                                <p>Civil Law, Corporate Law</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-clock"></i>
                            <div class="contact-text">
                                <h2>Experience</h2>
                                <p>10 Years</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fa fa-info-circle"></i>
                            <div class="contact-text">
                                <h2>Bio</h2>
                                <p>John is an experienced lawyer who has handled numerous high-profile civil and corporate law cases. He is passionate about justice and client advocacy.</p>
                            </div>
                        </div>
                    </div>
                    <a href="editprofile.php" class="btn mt-3">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Section End -->

    <!-- Newsletter & Footer -->
    <!-- [Use existing newsletter and footer as-is] -->

    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
</div>
   <?php include('inc.footer.php'); ?>

