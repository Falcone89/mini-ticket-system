<?php
session_start();
require 'config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
require 'includes/header.php';
?>
<div class="container py-5 col-md-4 mx-auto">
    <h1 class="mb-4">Admin login</h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Log in</button>
    </form>

    <p class="mt-4"><a href="index.php" class="btn btn-outline-secondary">Submit a ticket</a></p>
</div>
<?php require 'includes/footer.php'; ?>
