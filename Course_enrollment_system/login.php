<?php
require 'db.php';
include 'header.php';

if (is_logged_in()) {
    header("Location: courses.php");
    exit;
}

$db = new Database();
$conn = $db->connect();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['full_name'] = $user['full_name'];

        header("Location: courses.php");
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<h2>Login</h2>

<?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    <div class="alert alert-success">Registration successful. Please log in.</div>
<?php endif; ?>

<?php if (!empty($message)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<form action="login.php" method="POST" class="mt-3">

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php
echo '</div></body></html>';
?>
