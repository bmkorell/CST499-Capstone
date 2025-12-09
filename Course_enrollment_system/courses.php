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

// Handle enroll POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['course_id'])) {
    $course_id = (int) $_POST['course_id'];

    // Check if already enrolled
    $checkSql = "SELECT enrollment_id FROM enrollments 
                 WHERE user_id = :user_id AND course_id = :course_id";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $message = "You are already enrolled in that course.";
    } else {
        $insertSql = "INSERT INTO enrollments (user_id, course_id)
                      VALUES (:user_id, :course_id)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);

        if ($insertStmt->execute()) {
            $message = "Successfully enrolled in the course.";
        } else {
            $message = "Enrollment failed. Please try again.";
        }
    }
}

// Get all courses
$coursesSql = "SELECT * FROM courses ORDER BY course_code";
$coursesStmt = $conn->query($coursesSql);
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Available Courses</h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

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
    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?php echo htmlspecialchars($course['course_code']); ?></td>
            <td><?php echo htmlspecialchars($course['course_name']); ?></td>
            <td><?php echo (int)$course['credits']; ?></td>
            <td>
                <form action="courses.php" method="POST" class="d-inline">
                    <input type="hidden" name="course_id"
                           value="<?php echo (int)$course['course_id']; ?>">
                    <button type="submit" class="btn btn-sm btn-success">
                        Enroll
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php
echo '</div></body></html>';
?>
