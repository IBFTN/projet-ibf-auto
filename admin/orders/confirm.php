<?php
require_once "../../Database.php";
require_once "../../OrderManager.php";
require_once "../../CarManager.php";

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$db = Database::getConnection();
$orderManager = new OrderManager($db);
$carManager = new CarManager($db);

// Get the order first
$order = $orderManager->get($id);

if (!$order) {
    echo "<h2 style='color:red;'>Order not found!</h2>";
    echo "<a href='index.php'>Back to Orders</a>";
    exit;
}

// Confirm the order
if ($orderManager->updateStatus($id, 'confirmed')) {
    // Mark the car as sold
    $car = $carManager->get($order->getCarId());
    if ($car) {
        $car->setSold(1);
        $carManager->update($car);
    }
    echo "<h2 style='color:green;'>Order confirmed and car marked as SOLD!</h2>";
} else {
    echo "<h2 style='color:red;'>Failed to confirm order.</h2>";
}
?>
<a href="index.php" style="display:inline-block;margin-top:20px;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;">Back to Orders</a>