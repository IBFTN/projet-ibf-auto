<?php
require_once "User.php";

class UserManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(User $user): bool
    {
        if ($user->getId() === 0) {
            $q = $this->db->prepare('INSERT INTO users (name, email, password, google_id, phone, address)
                                     VALUES (:name, :email, :password, :google_id, :phone, :address)');
            $q->bindValue(':name', $user->getName());
            $q->bindValue(':email', $user->getEmail());
            $q->bindValue(':password', $user->getPassword());
            $q->bindValue(':google_id', $user->getGoogleId());
            $q->bindValue(':phone', $user->getPhone());
            $q->bindValue(':address', $user->getAddress());
        } else {
            $q = $this->db->prepare('INSERT INTO users (id, name, email, password, google_id, phone, address)
                                     VALUES (:id, :name, :email, :password, :google_id, :phone, :address)');
            $q->bindValue(':id', $user->getId());
            $q->bindValue(':name', $user->getName());
            $q->bindValue(':email', $user->getEmail());
            $q->bindValue(':password', $user->getPassword());
            $q->bindValue(':google_id', $user->getGoogleId());
            $q->bindValue(':phone', $user->getPhone());
            $q->bindValue(':address', $user->getAddress());
        }
        return $q->execute();
    }

    public function get(int $id): ?User
    {
        $q = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new User(
            $row['id'], $row['name'], $row['email'], $row['password'],
            $row['google_id'] ?? null, $row['phone'] ?? null, $row['address'] ?? null, $row['created_at'] ?? null
        );
    }

    public function getByEmail(string $email): ?User
    {
        $q = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $q->bindValue(':email', $email);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new User(
            $row['id'], $row['name'], $row['email'], $row['password'],
            $row['google_id'] ?? null, $row['phone'] ?? null, $row['address'] ?? null, $row['created_at'] ?? null
        );
    }

    public function getAll(): array
    {
        $q = $this->db->query('SELECT * FROM users ORDER BY name');
        $users = [];
        while ($row = $q->fetch()) {
            $users[] = new User(
                $row['id'], $row['name'], $row['email'], $row['password'],
                $row['google_id'] ?? null, $row['phone'] ?? null, $row['address'] ?? null, $row['created_at'] ?? null
            );
        }
        return $users;
    }

    public function update(User $user): bool
    {
        $q = $this->db->prepare('UPDATE users SET name = :name, email = :email, password = :password,
                                 google_id = :google_id, phone = :phone, address = :address WHERE id = :id');
        $q->bindValue(':id', $user->getId());
        $q->bindValue(':name', $user->getName());
        $q->bindValue(':email', $user->getEmail());
        $q->bindValue(':password', $user->getPassword());
        $q->bindValue(':google_id', $user->getGoogleId());
        $q->bindValue(':phone', $user->getPhone());
        $q->bindValue(':address', $user->getAddress());
        return $q->execute();
    }

    public function delete(int $id): bool
    {
        $q = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $q->bindValue(':id', $id);
        return $q->execute();
    }
}
?>