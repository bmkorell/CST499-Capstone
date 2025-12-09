<?php include 'header.php'; ?>

<div class="mt-4">
    <h1>Welcome to the Course Enrollment System</h1>
    <p class="lead">Register, log in, and manage your course enrollments.</p>

    <?php if (!is_logged_in()): ?>
        <a href="login.php" class="btn btn-primary me-2">Login</a>
        <a href="register.php" class="btn btn-success">Register</a>
    <?php else: ?>
        <a href="courses.php" class="btn btn-primary me-2">View Available Courses</a>
        <a href="my_classes.php" class="btn btn-secondary">View My Classes</a>
    <?php endif; ?>
</div>

<?php
// footer
echo '</div></body></html>';
?>
