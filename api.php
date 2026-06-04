<?php
require_once "Database.php";
require_once "BrandManager.php";
require_once "CarManager.php";
require_once "ContactManager.php";
require_once "OrderManager.php";
require_once "Order.php";

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$db = Database::getConnection();

$brandManager = new BrandManager($db);
$carManager = new CarManager($db);
$contactManager = new ContactManager($db);
$orderManager = new OrderManager($db);

switch ($action) {
    case 'brands':
        getBrands();
        break;
    case 'cars':
        getCars();
        break;
    case 'car':
        getCar();
        break;
    case 'featured':
        getFeatured();
        break;
    case 'contact':
        submitContact();
        break;
    case 'image':
        getCarImage();
        break;
    case 'order':
        createOrder();
        break;
    case 'orders':
        getOrders();
        break;
    case 'myorders':
        getMyOrders();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getBrands() {
    global $brandManager;
    $brands = $brandManager->getAll();
    $result = [];
    foreach ($brands as $b) {
        $logoData = null;
        if ($b->getLogoBlob()) {
            $ext = 'png'; // Default to png for logos
            if ($b->getLogoUrl()) {
                $ext = pathinfo($b->getLogoUrl(), PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                    $ext = 'png';
                }
            }
            $logoData = 'data:image/' . ($ext === 'svg' ? 'svg+xml' : $ext) . ';base64,' . base64_encode($b->getLogoBlob());
        }

        $result[] = [
            'id' => $b->getId(),
            'name' => $b->getName(),
            'logo_url' => $b->getLogoUrl(),
            'logo_blob' => $logoData
        ];
    }
    echo json_encode($result);
}

function getCars() {
    global $carManager, $brandManager;
    $brand = $_GET['brand'] ?? '';

    // Fetch all brands to map IDs to names
    $brandsList = $brandManager->getAll();
    $brandsMap = [];
    foreach ($brandsList as $b) {
        $brandsMap[$b->getId()] = $b->getName();
    }

    if ($brand && $brand !== 'all' && $brand !== 'All') {
        $brandId = null;
        foreach ($brandsList as $b) {
            if ($b->getName() === $brand) {
                $brandId = $b->getId();
                break;
            }
        }
        if ($brandId) {
            $cars = $carManager->getByBrand($brandId);
        } else {
            $cars = [];
        }
    } else {
        $cars = $carManager->getAll();
    }

    $result = [];
    foreach ($cars as $car) {
        $imageData = null;
        if ($car->getImageBlob()) {
            $ext = 'jpeg';
            if ($car->getImageUrl()) {
                $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $ext = 'jpeg';
                }
            }
            $imageData = 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
        }

        $result[] = [
            'id' => $car->getId(),
            'brand_id' => $car->getBrandId(),
            'brand_name' => $brandsMap[$car->getBrandId()] ?? 'Unknown',
            'model' => $car->getModel(),
            'year' => $car->getYear(),
            'price' => $car->getPrice(),
            'engine' => $car->getEngine(),
            'power' => $car->getPower(),
            'drive' => $car->getDrive(),
            'description' => $car->getDescription(),
            'image_url' => $car->getImageUrl(),
            'image_blob' => $imageData,
            'sold' => $car->getSold(),
            'created_at' => $car->getCreatedAt()
        ];
    }
    echo json_encode($result);
}

function getCar() {
    global $carManager, $brandManager;
    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['error' => 'Invalid car ID']);
        return;
    }

    $car = $carManager->get($id);
    if (!$car) {
        echo json_encode(['error' => 'Car not found']);
        return;
    }

    $brand = $brandManager->get($car->getBrandId());

    $imageData = null;
    if ($car->getImageBlob()) {
        $ext = 'jpeg';
        if ($car->getImageUrl()) {
            $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $ext = 'jpeg';
            }
        }
        $imageData = 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
    }

    echo json_encode([
        'id' => $car->getId(),
        'brand_id' => $car->getBrandId(),
        'brand_name' => $brand ? $brand->getName() : 'Unknown',
        'model' => $car->getModel(),
        'year' => $car->getYear(),
        'price' => $car->getPrice(),
        'engine' => $car->getEngine(),
        'power' => $car->getPower(),
        'drive' => $car->getDrive(),
        'description' => $car->getDescription(),
        'image_url' => $car->getImageUrl(),
        'image_blob' => $imageData,
        'sold' => $car->getSold(),
        'created_at' => $car->getCreatedAt()
    ]);
}

function getFeatured() {
    global $carManager, $brandManager;
    $cars = $carManager->getFeatured(6);

    // Fetch all brands to map IDs to names
    $brandsList = $brandManager->getAll();
    $brandsMap = [];
    foreach ($brandsList as $b) {
        $brandsMap[$b->getId()] = $b->getName();
    }

    $result = [];
    foreach ($cars as $car) {
        $imageData = null;
        if ($car->getImageBlob()) {
            $ext = 'jpeg';
            if ($car->getImageUrl()) {
                $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $ext = 'jpeg';
                }
            }
            $imageData = 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
        }

        $result[] = [
            'id' => $car->getId(),
            'brand_id' => $car->getBrandId(),
            'brand_name' => $brandsMap[$car->getBrandId()] ?? 'Unknown',
            'model' => $car->getModel(),
            'year' => $car->getYear(),
            'price' => $car->getPrice(),
            'engine' => $car->getEngine(),
            'power' => $car->getPower(),
            'drive' => $car->getDrive(),
            'description' => $car->getDescription(),
            'image_url' => $car->getImageUrl(),
            'image_blob' => $imageData,
            'sold' => $car->getSold(),
            'created_at' => $car->getCreatedAt()
        ];
    }
    echo json_encode($result);
}

function submitContact() {
    global $contactManager;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        return;
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Name, email and message are required']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        return;
    }

    $contact = new Contact(0, $name, $email, $message, $phone, $subject);

    if ($contactManager->add($contact)) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send message']);
    }
}

function getCarImage() {
    global $carManager;
    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid car ID']);
        return;
    }

    $car = $carManager->get($id);
    if (!$car || !$car->getImageBlob()) {
        http_response_code(404);
        echo json_encode(['error' => 'Image not found']);
        return;
    }

    $ext = 'jpeg';
    if ($car->getImageUrl()) {
        $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $ext = 'jpeg';
        }
    }

    header('Content-Type: image/' . $ext);
    echo $car->getImageBlob();
}

function createOrder() {
    global $orderManager, $carManager;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $carId = (int)($data['car_id'] ?? 0);
    $userId = $data['user_id'] ?? null;
    $customerName = trim($data['customer_name'] ?? '');
    $customerEmail = trim($data['customer_email'] ?? '');
    $customerPhone = trim($data['customer_phone'] ?? '');

    if ($carId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid car ID']);
        return;
    }

    if (empty($customerName) || empty($customerEmail)) {
        echo json_encode(['success' => false, 'error' => 'Name and email are required']);
        return;
    }

    if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        return;
    }

    $order = new Order(
        0,
        $carId,
        $userId ? (int)$userId : null,
        $customerName,
        $customerEmail,
        $customerPhone,
        'pending'
    );

    if ($orderManager->add($order)) {
        echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to place order']);
    }
}

function getOrders() {
    global $orderManager, $carManager;

    $orders = $orderManager->getAll();

    $result = [];
    foreach ($orders as $order) {
        $car = $carManager->get($order->getCarId());

        $imageData = null;
        if ($car && $car->getImageBlob()) {
            $ext = 'jpeg';
            if ($car->getImageUrl()) {
                $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $ext = 'jpeg';
                }
            }
            $imageData = 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
        }

        $result[] = [
            'id' => $order->getId(),
            'car_id' => $order->getCarId(),
            'car_model' => $car ? $car->getModel() : 'Unknown',
            'car_price' => $car ? $car->getPrice() : 0,
            'user_id' => $order->getUserId(),
            'customer_name' => $order->getCustomerName(),
            'customer_email' => $order->getCustomerEmail(),
            'customer_phone' => $order->getCustomerPhone(),
            'status' => $order->getStatus(),
            'created_at' => $order->getCreatedAt()
        ];
    }
    echo json_encode($result);
}

function getMyOrders() {
    global $orderManager, $carManager;

    $userId = (int)($_GET['user_id'] ?? 0);

    if ($userId <= 0) {
        echo json_encode(['error' => 'Invalid user ID']);
        return;
    }

    $orders = $orderManager->getByUser($userId);

    $result = [];
    foreach ($orders as $order) {
        $car = $carManager->get($order->getCarId());

        $imageData = null;
        if ($car && $car->getImageBlob()) {
            $ext = 'jpeg';
            if ($car->getImageUrl()) {
                $ext = pathinfo($car->getImageUrl(), PATHINFO_EXTENSION);
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $ext = 'jpeg';
                }
            }
            $imageData = 'data:image/' . $ext . ';base64,' . base64_encode($car->getImageBlob());
        }

        $result[] = [
            'id' => $order->getId(),
            'car_id' => $order->getCarId(),
            'car_model' => $car ? $car->getModel() : 'Unknown',
            'car_price' => $car ? $car->getPrice() : 0,
            'status' => $order->getStatus(),
            'created_at' => $order->getCreatedAt()
        ];
    }
    echo json_encode($result);
}
?>