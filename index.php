<?php
require 'config.php';

$name = $email = $subject = $description = '';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email must be a valid email address.';
    }

    if ($subject === '') {
        $errors[] = 'Subject is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO tickets (name, email, subject, description, status) VALUES (?, ?, ?, ?, "pending")'
        );
        $stmt->execute([$name, $email, $subject, $description]);
        $success = true;
        $name = $email = $subject = $description = '';
    }
}

require 'includes/header.php';
?>
<div class="container py-5 col-md-6 mx-auto">
    <h1 class="mb-4">Submit a ticket</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Your ticket has been submitted.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($subject); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <p class="mt-4"><a href="login.php" class="btn btn-outline-secondary">Admin dashboard</a></p>
</div>
<?php require 'includes/footer.php'; ?>
