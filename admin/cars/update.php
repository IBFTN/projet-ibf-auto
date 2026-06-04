<?php
require_once "../includes/auth.php";
check_auth('../');

require_once "../../Database.php";
require_once "../../BrandManager.php";
require_once "../../CarManager.php";
require_once "../../Car.php";
require_once "../includes/functions.php";

$db = Database::getConnection();
$carManager = new CarManager($db);
$brandManager = new BrandManager($db);

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$car = $carManager->get($id);

if (!$car) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = (int)($_POST['brand_id'] ?? 0);
    $model = trim($_POST['model'] ?? '');
    $year = (int)($_POST['year'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $engine = trim($_POST['engine'] ?? '');
    $power = trim($_POST['power'] ?? '');
    $drive = trim($_POST['drive'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $image_blob = $car->getImageBlob();
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_name = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_ext, $allowed_ext)) {
            $error = "Seuls les fichiers JPG, PNG, GIF et WEBP sont autorisés !";
        } elseif ($file_size > 5000000) {
            $error = "Le fichier est trop volumineux (max 5 Mo) !";
        } else {
            $uploadDir = "../../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $photoName = time() . "_" . basename($file_name);
            if (move_uploaded_file($file_tmp, $uploadDir . $photoName)) {
                $image_url = $photoName;
                // New file uploaded, update blob
                $image_blob = file_get_contents($uploadDir . $photoName);
            } else {
                $error = "Erreur lors du téléchargement de l'image !";
            }
        }
    }

    if (!$error && (!$brand_id || !$model || !$year || !$price)) {
        $error = "La marque, le modèle, l'année et le prix sont obligatoires !";
    }

    if (!$error) {
        $updatedCar = new Car($id, $brand_id, $model, $year, $price, $engine, $power, $drive, $description, $image_url, $image_blob);

        if ($carManager->update($updatedCar)) {
            $success = "Voiture modifiée avec succès !";
            $car = $carManager->get($id); // Refresh car data
        } else {
            $error = "Erreur lors de la modification.";
        }
    }
}

$brands = $brandManager->getAll();

$pageTitle = "Modifier une Voiture - IBF Admin";
$activePage = 'cars';
$adminRoot = '../';
include "../templates/header.php";
?>

<a href="index.php" class="btn-back">← Retour à la liste</a>

<div class="section" style="max-width: 850px; margin: 0 auto;">
    <div class="section-header">
        <h2>Modifier le Véhicule (ID: <?= $car->getId() ?>)</h2>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>ID</label>
                <input type="number" value="<?= $car->getId() ?>" readonly>
            </div>
            <div class="form-group">
                <label>Marque *</label>
                <select name="brand_id" required>
                    <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand->getId() ?>" <?= ($brand->getId() == $car->getBrandId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand->getName()) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Modèle *</label>
                <input type="text" name="model" value="<?= htmlspecialchars($car->getModel()) ?>" required>
            </div>
            <div class="form-group">
                <label>Année *</label>
                <input type="number" name="year" value="<?= $car->getYear() ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Prix (DT) *</label>
            <input type="number" step="0.01" name="price" value="<?= $car->getPrice() ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Moteur</label>
                <input type="text" name="engine" value="<?= htmlspecialchars($car->getEngine() ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Puissance</label>
                <input type="text" name="power" value="<?= htmlspecialchars($car->getPower() ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Transmission</label>
                <input type="text" name="drive" value="<?= htmlspecialchars($car->getDrive() ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($car->getDescription() ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>URL de l'image (Optionnel)</label>
            <input type="text" name="image_url" value="<?= htmlspecialchars($car->getImageUrl() ?? '') ?>" placeholder="Nom du fichier ou URL externe">
        </div>

        <div class="form-group">
            <label>Remplacer la Photo (Optionnel)</label>
            <div style="display: flex; gap: 25px; align-items: center; border: 1px solid #eee; padding: 15px; border-radius: 12px;">
                <div style="flex: 1;">
                    <input type="file" name="image" accept="image/*">
                </div>
                <div style="text-align: center;">
                    <p style="font-size: 11px; color: #888; margin-bottom: 8px;">Photo actuelle :</p>
                    <?php $img = get_car_image($car, '../../'); ?>
                    <?php if ($img): ?>
                        <img src="<?= $img ?>" alt="" style="width: 130px; height: 85px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <?php else: ?>
                        <div style="width: 130px; height: 85px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 11px; color: #ccc;">Aucune image</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">Mettre à jour les Informations</button>
        </div>
    </form>
</div>

<?php include "../templates/footer.php"; ?>
