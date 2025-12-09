<?php
require 'db.php';
include 'header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$db = new Database();
$conn = $db->connect();
$user_id = $_SESSION['user_id'];
$message = "";

// Handle drop by GET param
if (isset($_GET['drop_id'])) {
    $enrollment_id = (int) $_GET['drop_id'];

    // Ensure the enrollment belongs to current user
    $checkSql = "SELECT enrollment_id FROM enrollments
                 WHERE enrollment_id = :enrollment_id AND user_id = :user_id";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $delSql = "DELETE FROM enrollments WHERE enrollment_id = :enrollment_id";
        $delStmt = $conn->prepare($delSql);
        $delStmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_INT);
        if ($delStmt->execute()) {
            $message = "Course dropped successfully.";
        } else {
            $message = "Failed to drop course.";
        }
    } else {
        $message = "Invalid enrollment selected.";
    }
}

// Get user's enrollments
$sql = "SELECT e.enrollment_id, c.course_code, c.course_name, c.credits
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        WHERE e.user_id = :user_id
        ORDER BY c.course_code";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$enrolled = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>My Registered Classes</h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (empty($enrolled)): ?>
    <p>You are not currently enrolled in any classes.</p>
    <a href="courses.php" class="btn btn-primary">Enroll in Courses</a>
<?php else: ?>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Credits</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($enrolled as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><?php echo (int)$row['credits']; ?></td>
                <td>
                    <a href="my_classes.php?drop_id=<?php echo (int)$row['enrollment_id']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to drop this course?');">
                       Drop
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
echo '</div></body></html>';
?>
