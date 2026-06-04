<?php
require_once "../../Database.php";
require_once "../../OrderManager.php";

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

if ($orderManager->updateStatus($id, 'cancelled')) {
    echo "<h2 style='color:orange;'>Order cancelled!</h2>";
} else {
    echo "<h2 style='color:red;'>Failed to cancel order.</h2>";
}
?>
<a href="index.php">Back to Orders</a>