<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once "../../Database.php";
require_once "../../CarManager.php";

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $db = Database::getConnection();
    $carManager = new CarManager($db);
    $carManager->delete($id);
}

header("Location: index.php");
exit;
