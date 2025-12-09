<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper: check if logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment System</title>
    <meta charset="utf-8">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Enrollment System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (is_logged_in()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php">Available Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_classes.php">My Classes</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (is_logged_in()): ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Hello, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Student'); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>

<div class="container mt-4">
