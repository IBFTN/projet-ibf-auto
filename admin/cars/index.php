<?php
require_once "../includes/auth.php";
check_auth('../');

require_once "../../Database.php";
require_once "../../BrandManager.php";
require_once "../../CarManager.php";
require_once "../includes/functions.php";

$db = Database::getConnection();
$carManager = new CarManager($db);
$brandManager = new BrandManager($db);

$cars = $carManager->getAll();
$brands = $brandManager->getAll();

$brandMap = [];
foreach ($brands as $brand) {
    $brandMap[$brand->getId()] = $brand->getName();
}

$pageTitle = "Gestion des Voitures - IBF Admin";
$activePage = 'cars';
$adminRoot = '../';
include "../templates/header.php";
?>

<div class="section">
    <div class="section-header">
        <h2>Liste des Voitures (<?= count($cars) ?>)</h2>
        <a href="insertion.php" class="btn btn-success">+ Ajouter une Voiture</a>
    </div>
    
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Prix</th>
                    <th>Moteur</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= $car->getId() ?></td>
                    <td>
                        <?php $img = get_car_image($car, '../../'); ?>
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
                        <small style="color: #666;">
                            <?= htmlspecialchars($car->getEngine() ?? '-') ?><br>
                            <?= htmlspecialchars($car->getPower() ?? '-') ?>
                        </small>
                    </td>
                    <td>
                        <?php if ($car->getSold()): ?>
                            <span class="badge badge-success">Vendu</span>
                        <?php else: ?>
                            <span class="badge badge-primary">Disponible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="update.php?id=<?= $car->getId() ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Éditer</a>
                            <a href="delete.php?id=<?= $car->getId() ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Voulez-vous vraiment supprimer cette voiture ?')">Supprimer</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../templates/footer.php"; ?>
