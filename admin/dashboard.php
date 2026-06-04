<?php
require_once "includes/auth.php";
check_auth();

require_once "../Database.php";
require_once "../BrandManager.php";
require_once "../CarManager.php";
require_once "../UserManager.php";
require_once "../ContactManager.php";
require_once "../OrderManager.php";
require_once "includes/functions.php";

$db = Database::getConnection();
$carManager = new CarManager($db);
$brandManager = new BrandManager($db);
$userManager = new UserManager($db);
$contactManager = new ContactManager($db);
$orderManager = new OrderManager($db);

$cars = $carManager->getAll();
$brands = $brandManager->getAll();
$contacts = $contactManager->getAll();
$users = $userManager->getAll();
$orders = $orderManager->getAll();

$pendingOrders = array_filter($orders, fn($o) => $o->getStatus() === 'pending');
$soldCars = array_filter($cars, fn($c) => $c->getSold() === 1);

$brandMap = [];
foreach ($brands as $brand) {
    $brandMap[$brand->getId()] = $brand->getName();
}

$pageTitle = "Dashboard - IBF Motors Admin";
$activePage = 'dashboard';
$adminRoot = '';
include "templates/header.php";
?>

<div class="stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Total Voitures</h3>
        <div class="number" style="font-size: 36px; font-weight: 700; color: #007bff;"><?= count($cars) ?></div>
    </div>
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Voitures Vendues</h3>
        <div class="number" style="font-size: 36px; font-weight: 700; color: #28a745;"><?= count($soldCars) ?></div>
    </div>
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Commandes</h3>
        <div class="number" style="font-size: 36px; font-weight: 700; color: #17a2b8;"><?= count($orders) ?></div>
    </div>
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">En Attente</h3>
        <div class="number" style="font-size: 36px; font-weight: 700; color: #ffc107;"><?= count($pendingOrders) ?></div>
    </div>
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Messages</h3>
        <div class="number" style="font-size: 36px; font-weight: 700; color: #6c757d;"><?= count($contacts) ?></div>
    </div>
</div>

<div class="section">
    <div class="section-header">
        <h2>Dernières Voitures Ajoutées</h2>
        <a href="cars/insertion.php" class="btn btn-success">+ Ajouter</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Année</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($cars, 0, 5) as $car): ?>
            <tr>
                <td><?= $car->getId() ?></td>
                <td>
                    <?php $img = get_car_image($car, '../'); ?>
                    <?php if ($img): ?>
                        <img src="<?= $img ?>" alt="" class="car-img-small">
                    <?php else: ?>
                        <span style="color:#888;font-size:12px;">No Image</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($brandMap[$car->getBrandId()] ?? 'Inconnu') ?></td>
                <td><?= htmlspecialchars($car->getModel()) ?></td>
                <td><?= $car->getYear() ?></td>
                <td><?= number_format($car->getPrice(), 0, '.', ' ') ?> DT</td>
                <td>
                    <?php if ($car->getSold()): ?>
                        <span class="badge badge-success">Vendu</span>
                    <?php else: ?>
                        <span class="badge badge-primary">Disponible</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="cars/update.php?id=<?= $car->getId() ?>" class="btn btn-primary" style="padding: 5px 12px; font-size: 12px;">Éditer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 20px; text-align: right;">
        <a href="cars/index.php" style="color: #007bff; text-decoration: none; font-weight: 600; font-size: 14px;">Voir tout →</a>
    </div>
</div>

<?php include "templates/footer.php"; ?>
