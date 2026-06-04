<?php
require_once "Order.php";

class OrderManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(Order $order): bool
    {
        if ($order->getId() === 0) {
            $q = $this->db->prepare('INSERT INTO orders (car_id, user_id, customer_name, customer_email, customer_phone, status)
                                     VALUES (:car_id, :user_id, :customer_name, :customer_email, :customer_phone, :status)');
            $q->bindValue(':car_id', $order->getCarId());
            $q->bindValue(':user_id', $order->getUserId());
            $q->bindValue(':customer_name', $order->getCustomerName());
            $q->bindValue(':customer_email', $order->getCustomerEmail());
            $q->bindValue(':customer_phone', $order->getCustomerPhone());
            $q->bindValue(':status', $order->getStatus());
        } else {
            $q = $this->db->prepare('INSERT INTO orders (id, car_id, user_id, customer_name, customer_email, customer_phone, status)
                                     VALUES (:id, :car_id, :user_id, :customer_name, :customer_email, :customer_phone, :status)');
            $q->bindValue(':id', $order->getId());
            $q->bindValue(':car_id', $order->getCarId());
            $q->bindValue(':user_id', $order->getUserId());
            $q->bindValue(':customer_name', $order->getCustomerName());
            $q->bindValue(':customer_email', $order->getCustomerEmail());
            $q->bindValue(':customer_phone', $order->getCustomerPhone());
            $q->bindValue(':status', $order->getStatus());
        }
        return $q->execute();
    }

    public function get(int $id): ?Order
    {
        $q = $this->db->prepare('SELECT * FROM orders WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new Order(
            $row['id'], $row['car_id'], $row['user_id'] ?? null,
            $row['customer_name'] ?? null, $row['customer_email'] ?? null,
            $row['customer_phone'] ?? null, $row['status'] ?? 'pending',
            $row['created_at'] ?? null
        );
    }

    public function getAll(): array
    {
        $q = $this->db->query('SELECT * FROM orders ORDER BY created_at DESC');
        $orders = [];
        while ($row = $q->fetch()) {
            $orders[] = new Order(
                $row['id'], $row['car_id'], $row['user_id'] ?? null,
                $row['customer_name'] ?? null, $row['customer_email'] ?? null,
                $row['customer_phone'] ?? null, $row['status'] ?? 'pending',
                $row['created_at'] ?? null
            );
        }
        return $orders;
    }

    public function getByUser(int $userId): array
    {
        $q = $this->db->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
        $q->bindValue(':user_id', $userId);
        $q->execute();
        $orders = [];
        while ($row = $q->fetch()) {
            $orders[] = new Order(
                $row['id'], $row['car_id'], $row['user_id'] ?? null,
                $row['customer_name'] ?? null, $row['customer_email'] ?? null,
                $row['customer_phone'] ?? null, $row['status'] ?? 'pending',
                $row['created_at'] ?? null
            );
        }
        return $orders;
    }

    public function update(Order $order): bool
    {
        $q = $this->db->prepare('UPDATE orders SET car_id = :car_id, user_id = :user_id,
                                 customer_name = :customer_name, customer_email = :customer_email,
                                 customer_phone = :customer_phone, status = :status WHERE id = :id');
        $q->bindValue(':id', $order->getId());
        $q->bindValue(':car_id', $order->getCarId());
        $q->bindValue(':user_id', $order->getUserId());
        $q->bindValue(':customer_name', $order->getCustomerName());
        $q->bindValue(':customer_email', $order->getCustomerEmail());
        $q->bindValue(':customer_phone', $order->getCustomerPhone());
        $q->bindValue(':status', $order->getStatus());
        return $q->execute();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $q = $this->db->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->bindValue(':status', $status);
        return $q->execute();
    }

    public function delete(int $id): bool
    {
        $q = $this->db->prepare('DELETE FROM orders WHERE id = :id');
        $q->bindValue(':id', $id);
        return $q->execute();
    }
}
?>