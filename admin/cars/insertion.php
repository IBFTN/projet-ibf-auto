<?php
require_once "../includes/auth.php";
check_auth('../');

require_once "../../Database.php";
require_once "../../BrandManager.php";
require_once "../../CarManager.php";
require_once "../../Car.php";

$db = Database::getConnection();
$carManager = new CarManager($db);
$brandManager = new BrandManager($db);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $brand_id = (int)($_POST['brand_id'] ?? 0);
    $model = trim($_POST['model'] ?? '');
    $year = (int)($_POST['year'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $engine = trim($_POST['engine'] ?? '');
    $power = trim($_POST['power'] ?? '');
    $drive = trim($_POST['drive'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $imageUrl = '';
    $imageBlob = null;

    if (!$brand_id || !$model || !$year || !$price) {
        $error = "La marque, le modèle, l'année et le prix sont obligatoires !";
    } else {
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
                // Read file for blob
                $imageBlob = file_get_contents($file_tmp);
                
                // Also save to folder for backup
                $uploadDir = "../../uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $photoName = time() . "_" . basename($file_name);
                if (move_uploaded_file($file_tmp, $uploadDir . $photoName)) {
                    $imageUrl = $photoName;
                } else {
                    // Even if moving fails, we have the blob
                    $imageUrl = '';
                }
            }
        } else {
            $error = "Veuillez télécharger une photo de la voiture !";
        }
    }

    if (!$error && $brand_id && $model && $year && $price && $imageBlob) {
        $car = new Car($id, $brand_id, $model, $year, $price, $engine, $power, $drive, $description, $imageUrl, $imageBlob);

        if ($carManager->add($car)) {
            $success = "Voiture ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout. L'ID existe peut-être déjà.";
        }
    }
}

$brands = $brandManager->getAll();

$pageTitle = "Ajouter une Voiture - IBF Admin";
$activePage = 'cars';
$adminRoot = '../';
include "../templates/header.php";
?>

<a href="index.php" class="btn-back">← Retour à la liste</a>

<div class="section" style="max-width: 850px; margin: 0 auto;">
    <div class="section-header">
        <h2>Ajouter une Nouvelle Voiture</h2>
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
                <label>ID (Optionnel)</label>
                <input type="number" name="id" placeholder="Laissez vide pour auto-incrément">
            </div>
            <div class="form-group">
                <label>Marque *</label>
                <select name="brand_id" required>
                    <option value="">-- Sélectionner une Marque --</option>
                    <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand->getId() ?>"><?= htmlspecialchars($brand->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Modèle *</label>
                <input type="text" name="model" required placeholder="Ex: Aventador SVJ">
            </div>
            <div class="form-group">
                <label>Année *</label>
                <input type="number" name="year" required placeholder="Ex: 2024">
            </div>
        </div>

        <div class="form-group">
            <label>Prix (DT) *</label>
            <input type="number" step="0.01" name="price" required placeholder="Ex: 1250000">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Moteur</label>
                <input type="text" name="engine" placeholder="Ex: 6.5L V12">
            </div>
            <div class="form-group">
                <label>Puissance</label>
                <input type="text" name="power" placeholder="Ex: 770 hp">
            </div>
            <div class="form-group">
                <label>Transmission</label>
                <input type="text" name="drive" placeholder="Ex: AWD">
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Description détaillée du véhicule..."></textarea>
        </div>

        <div class="form-group">
            <label>Photo du Véhicule *</label>
            <div style="border: 2px dashed #ddd; padding: 25px; text-align: center; border-radius: 12px; background: #fafafa;">
                <input type="file" name="image" required accept="image/*">
                <p style="margin-top: 10px; font-size: 12px; color: #888;">PNG, JPG ou WEBP (Max. 5 Mo)</p>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-success" style="width: 100%; padding: 15px; font-size: 16px;">Enregistrer le Véhicule</button>
        </div>
    </form>
</div>

<?php include "../templates/footer.php"; ?>
