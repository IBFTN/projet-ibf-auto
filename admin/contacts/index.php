<?php
require_once "../../Database.php";
require_once "../../ContactManager.php";

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

$db = Database::getConnection();
$contactManager = new ContactManager($db);

$contacts = $contactManager->getAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Contacts - IBF Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 24px; }
        .header .nav { display: flex; gap: 20px; }
        .header .nav a { color: white; text-decoration: none; padding: 8px 15px; border-radius: 5px; }
        .header .nav a:hover { background: rgba(255,255,255,0.1); }
        .btn-logout { background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        .section { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section-header { margin-bottom: 20px; }
        .section-header h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; color: #333; font-weight: 600; }
        tr:hover { background: #f8f9fa; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 5px; color: white; font-size: 14px; }
        .btn-danger { background: #dc3545; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #666; text-decoration: none; }
        .btn-back:hover { color: #007bff; }
        .empty { text-align: center; color: #666; padding: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>IBF Motors - Messages</h1>
        <div class="nav">
            <a href="../dashboard.php">Dashboard</a>
            <a href="../orders/index.php">Orders</a>
            <a href="index.php">Contacts</a>
        </div>
        <a href="../logout.php" class="btn-logout">Deconnexion</a>
    </div>

    <div class="container">
        <a href="../dashboard.php" class="btn-back">← Back to Dashboard</a>

        <div class="section">
            <div class="section-header">
                <h2>Messages des Clients (<?= count($contacts) ?>)</h2>
            </div>

            <?php if (empty($contacts)): ?>
            <div class="empty">
                <p>Aucun message pour le moment.</p>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $contact->getId() ?></td>
                        <td><?= htmlspecialchars($contact->getName()) ?></td>
                        <td><?= htmlspecialchars($contact->getEmail()) ?></td>
                        <td><?= htmlspecialchars($contact->getPhone() ?? '-') ?></td>
                        <td><?= htmlspecialchars($contact->getSubject() ?? '-') ?></td>
                        <td><?= htmlspecialchars(substr($contact->getMessage(), 0, 50)) ?><?= strlen($contact->getMessage()) > 50 ? '...' : '' ?></td>
                        <td><?= $contact->getCreatedAt() ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>