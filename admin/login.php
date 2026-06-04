<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'IBF' && $password === 'ahmedking') {
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = 'IBF Motors';
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect!";
    }
}

$pageTitle = "Login - IBF Motors Admin";
$noHeader = true;
include "templates/header.php";
?>

<div style="min-height: 80vh; display: flex; justify-content: center; align-items: center;">
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 15px 50px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #1a1a2e; font-size: 32px; margin-bottom: 10px;">IBF Motors</h1>
            <p style="color: #666; font-size: 14px;">Espace Administration</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" required autofocus placeholder="Entrez votre identifiant">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="Entrez votre mot de passe">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px; margin-top: 10px;">Se connecter</button>
        </form>

        <a href="../index.html" style="display: block; text-align: center; margin-top: 25px; color: #666; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='#007bff'" onmouseout="this.style.color='#666'">← Retour au site public</a>
    </div>
</div>

<?php include "templates/footer.php"; ?>
