<?php
require_once "Car.php";

class CarManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(Car $car): bool
    {
        if ($car->getId() === 0) {
            $q = $this->db->prepare('INSERT INTO cars (brand_id, model, year, price, engine, power, drive, description, image_url, image_blob, sold)
                                     VALUES (:brand_id, :model, :year, :price, :engine, :power, :drive, :description, :image_url, :image_blob, :sold)');
            $q->bindValue(':brand_id', $car->getBrandId());
            $q->bindValue(':model', $car->getModel());
            $q->bindValue(':year', $car->getYear());
            $q->bindValue(':price', $car->getPrice());
            $q->bindValue(':engine', $car->getEngine());
            $q->bindValue(':power', $car->getPower());
            $q->bindValue(':drive', $car->getDrive());
            $q->bindValue(':description', $car->getDescription());
            $q->bindValue(':image_url', $car->getImageUrl());
            $q->bindValue(':image_blob', $car->getImageBlob(), PDO::PARAM_LOB);
            $q->bindValue(':sold', $car->getSold());
        } else {
            $q = $this->db->prepare('INSERT INTO cars (id, brand_id, model, year, price, engine, power, drive, description, image_url, image_blob, sold)
                                     VALUES (:id, :brand_id, :model, :year, :price, :engine, :power, :drive, :description, :image_url, :image_blob, :sold)');
            $q->bindValue(':id', $car->getId());
            $q->bindValue(':brand_id', $car->getBrandId());
            $q->bindValue(':model', $car->getModel());
            $q->bindValue(':year', $car->getYear());
            $q->bindValue(':price', $car->getPrice());
            $q->bindValue(':engine', $car->getEngine());
            $q->bindValue(':power', $car->getPower());
            $q->bindValue(':drive', $car->getDrive());
            $q->bindValue(':description', $car->getDescription());
            $q->bindValue(':image_url', $car->getImageUrl());
            $q->bindValue(':image_blob', $car->getImageBlob(), PDO::PARAM_LOB);
            $q->bindValue(':sold', $car->getSold());
        }
        return $q->execute();
    }

    public function get(int $id): ?Car
    {
        $q = $this->db->prepare('SELECT * FROM cars WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new Car(
            $row['id'], $row['brand_id'], $row['model'], $row['year'], $row['price'],
            $row['engine'] ?? null, $row['power'] ?? null, $row['drive'] ?? null, $row['description'] ?? null,
            $row['image_url'] ?? null, $row['image_blob'] ?? null, $row['sold'] ?? 0, $row['created_at'] ?? null
        );
    }

    public function getAll(): array
    {
        $q = $this->db->query('SELECT * FROM cars ORDER BY brand_id, model');
        $cars = [];
        while ($row = $q->fetch()) {
            $cars[] = new Car(
                $row['id'], $row['brand_id'], $row['model'], $row['year'], $row['price'],
                $row['engine'] ?? null, $row['power'] ?? null, $row['drive'] ?? null, $row['description'] ?? null,
                $row['image_url'] ?? null, $row['image_blob'] ?? null, $row['sold'] ?? 0, $row['created_at'] ?? null
            );
        }
        return $cars;
    }

    public function getByBrand(int $brandId): array
    {
        $q = $this->db->prepare('SELECT * FROM cars WHERE brand_id = :brand_id ORDER BY model');
        $q->bindValue(':brand_id', $brandId);
        $q->execute();
        $cars = [];
        while ($row = $q->fetch()) {
            $cars[] = new Car(
                $row['id'], $row['brand_id'], $row['model'], $row['year'], $row['price'],
                $row['engine'] ?? null, $row['power'] ?? null, $row['drive'] ?? null, $row['description'] ?? null,
                $row['image_url'] ?? null, $row['image_blob'] ?? null, $row['sold'] ?? 0, $row['created_at'] ?? null
            );
        }
        return $cars;
    }

    public function getFeatured(int $limit = 6): array
    {
        $q = $this->db->query("SELECT * FROM cars ORDER BY RAND() LIMIT $limit");
        $cars = [];
        while ($row = $q->fetch()) {
            $cars[] = new Car(
                $row['id'], $row['brand_id'], $row['model'], $row['year'], $row['price'],
                $row['engine'] ?? null, $row['power'] ?? null, $row['drive'] ?? null, $row['description'] ?? null,
                $row['image_url'] ?? null, $row['image_blob'] ?? null, $row['sold'] ?? 0, $row['created_at'] ?? null
            );
        }
        return $cars;
    }

    public function update(Car $car): bool
    {
        $sql = 'UPDATE cars SET brand_id = :brand_id, model = :model, year = :year,
                 price = :price, engine = :engine, power = :power, drive = :drive,
                 description = :description, image_url = :image_url, sold = :sold';

        if ($car->getImageBlob() !== null) {
            $sql .= ', image_blob = :image_blob';
        }

        $sql .= ' WHERE id = :id';

        $q = $this->db->prepare($sql);
        $q->bindValue(':id', $car->getId());
        $q->bindValue(':brand_id', $car->getBrandId());
        $q->bindValue(':model', $car->getModel());
        $q->bindValue(':year', $car->getYear());
        $q->bindValue(':price', $car->getPrice());
        $q->bindValue(':engine', $car->getEngine());
        $q->bindValue(':power', $car->getPower());
        $q->bindValue(':drive', $car->getDrive());
        $q->bindValue(':description', $car->getDescription());
        $q->bindValue(':image_url', $car->getImageUrl());
        $q->bindValue(':sold', $car->getSold());

        if ($car->getImageBlob() !== null) {
            $q->bindValue(':image_blob', $car->getImageBlob(), PDO::PARAM_LOB);
        }

        return $q->execute();
    }

    public function delete(int $id): bool
    {
        $q = $this->db->prepare('DELETE FROM cars WHERE id = :id');
        $q->bindValue(':id', $id);
        return $q->execute();
    }
}
?>