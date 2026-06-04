<?php
require_once "../../Database.php";
require_once "../../OrderManager.php";
require_once "../../CarManager.php";
require_once "../../BrandManager.php";

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

$db = Database::getConnection();
$orderManager = new OrderManager($db);
$carManager = new CarManager($db);
$brandManager = new BrandManager($db);

$orders = $orderManager->getAll();
$cars = $carManager->getAll();
$brands = $brandManager->getAll();

$carMap = [];
foreach ($cars as $car) {
    $carMap[$car->getId()] = $car;
}
$brandMap = [];
foreach ($brands as $brand) {
    $brandMap[$brand->getId()] = $brand->getName();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - IBF Admin</title>
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
        .status { padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffc107; color: #000; }
        .status-confirmed { background: #28a745; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }
        .btn { padding: 5px 12px; text-decoration: none; border-radius: 5px; color: white; font-size: 12px; border: none; cursor: pointer; }
        .btn-confirm { background: #28a745; }
        .btn-cancel { background: #dc3545; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #666; text-decoration: none; }
        .empty { text-align: center; color: #666; padding: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>IBF Motors - Orders</h1>
        <div class="nav">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Orders</a>
        </div>
        <a href="../logout.php" class="btn-logout">Deconnexion</a>
    </div>

    <div class="container">
        <a href="../dashboard.php" class="btn-back">← Back to Dashboard</a>

        <div class="section">
            <div class="section-header">
                <h2>All Orders (<?= count($orders) ?>)</h2>
            </div>

            <?php if (empty($orders)): ?>
            <div class="empty">
                <p>No orders yet.</p>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <?php $car = $carMap[$order->getCarId()] ?? null; ?>
                    <?php $brandName = $car ? ($brandMap[$car->getBrandId()] ?? 'Unknown') : 'Unknown'; ?>
                    <tr>
                        <td><?= $order->getId() ?></td>
                        <td>
                            <?php if ($car): ?>
                            <strong><?= $brandName ?> <?= $car->getModel() ?></strong>
                            <br><small style="color:#666;">$<?= number_format($car->getPrice(), 2) ?></small>
                            <?php else: ?>
                            Car #<?= $order->getCarId() ?> (Deleted)
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($order->getCustomerName() ?? '-') ?></td>
                        <td><?= htmlspecialchars($order->getCustomerEmail() ?? '-') ?></td>
                        <td><?= htmlspecialchars($order->getCustomerPhone() ?? '-') ?></td>
                        <td>
                            <?php if ($order->getStatus() === 'pending'): ?>
                            <span class="status status-pending">PENDING</span>
                            <?php elseif ($order->getStatus() === 'confirmed'): ?>
                            <span class="status status-confirmed">CONFIRMED</span>
                            <?php else: ?>
                            <span class="status status-cancelled">CANCELLED</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $order->getCreatedAt() ?></td>
                        <td>
                            <?php if ($order->getStatus() === 'pending'): ?>
                            <a href="confirm.php?id=<?= $order->getId() ?>" class="btn btn-confirm">Confirm</a>
                            <a href="cancel.php?id=<?= $order->getId() ?>" class="btn btn-cancel">Cancel</a>
                            <?php else: ?>
                            <span style="color:#666;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>