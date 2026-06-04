<?php
require_once "Database.php";
require_once "UserManager.php";

session_start();

header('Content-Type: application/json');

$db = Database::getConnection();
$userManager = new UserManager($db);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        login();
        break;
    case 'register':
        register();
        break;
    case 'logout':
        logout();
        break;
    case 'check':
        checkAuth();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function login() {
    global $userManager;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        return;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Email and password are required']);
        return;
    }

    $user = $userManager->getByEmail($email);

    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_email'] = $user->getEmail();

        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid email or password']);
    }
}

function register() {
    global $userManager;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        return;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        return;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters']);
        return;
    }

    $existingUser = $userManager->getByEmail($email);
    if ($existingUser) {
        echo json_encode(['success' => false, 'error' => 'Email already registered']);
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $user = new User(0, $name, $email, $hashedPassword);

    if ($userManager->add($user)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Registration failed']);
    }
}

function logout() {
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    echo json_encode(['success' => true]);
}

function checkAuth() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode([
            'logged_in' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email']
            ]
        ]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
}
?>