<?php
require 'includes/auth.php';
require 'config.php';

$statuses = ['pending', 'in progress', 'done', 'rejected'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id > 0 && in_array($status, $statuses, true)) {
        $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }
}

$tickets = $pdo->query('SELECT * FROM tickets ORDER BY created_at DESC')->fetchAll();

require 'includes/header.php';
?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin dashboard</h1>
        <a href="logout.php" class="btn btn-outline-secondary">Log out</a>
    </div>

    <h2>Tickets</h2>

    <table class="table table-bordered align-middle">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Created at</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                <td><?php echo htmlspecialchars($ticket['email']); ?></td>
                <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></td>
                <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                <td>
                    <form method="post" class="d-flex gap-2">
                        <input type="hidden" name="id" value="<?php echo (int) $ticket['id']; ?>">
                        <select name="status" class="form-select">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo $status === $ticket['status'] ? 'selected' : ''; ?>>
                                    <?php echo $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require 'includes/footer.php'; ?>
