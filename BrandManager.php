<?php
require_once "Brand.php";

class BrandManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(Brand $brand): bool
    {
        if ($brand->getId() === 0) {
            $q = $this->db->prepare('INSERT INTO brands (name, logo_url, logo_blob) VALUES (:name, :logo_url, :logo_blob)');
            $q->bindValue(':name', $brand->getName());
            $q->bindValue(':logo_url', $brand->getLogoUrl());
            $q->bindValue(':logo_blob', $brand->getLogoBlob(), PDO::PARAM_LOB);
        } else {
            $q = $this->db->prepare('INSERT INTO brands (id, name, logo_url, logo_blob) VALUES (:id, :name, :logo_url, :logo_blob)');
            $q->bindValue(':id', $brand->getId());
            $q->bindValue(':name', $brand->getName());
            $q->bindValue(':logo_url', $brand->getLogoUrl());
            $q->bindValue(':logo_blob', $brand->getLogoBlob(), PDO::PARAM_LOB);
        }
        return $q->execute();
    }

    public function get(int $id): ?Brand
    {
        $q = $this->db->prepare('SELECT * FROM brands WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new Brand($row['id'], $row['name'], $row['logo_url'] ?? null, $row['logo_blob'] ?? null, $row['created_at'] ?? null);
    }

    public function getAll(): array
    {
        $q = $this->db->query('SELECT * FROM brands ORDER BY name');
        $brands = [];
        while ($row = $q->fetch()) {
            $brands[] = new Brand($row['id'], $row['name'], $row['logo_url'] ?? null, $row['logo_blob'] ?? null, $row['created_at'] ?? null);
        }
        return $brands;
    }

    public function update(Brand $brand): bool
    {
        $sql = 'UPDATE brands SET name = :name, logo_url = :logo_url';
        if ($brand->getLogoBlob() !== null) {
            $sql .= ', logo_blob = :logo_blob';
        }
        $sql .= ' WHERE id = :id';

        $q = $this->db->prepare($sql);
        $q->bindValue(':id', $brand->getId());
        $q->bindValue(':name', $brand->getName());
        $q->bindValue(':logo_url', $brand->getLogoUrl());
        if ($brand->getLogoBlob() !== null) {
            $q->bindValue(':logo_blob', $brand->getLogoBlob(), PDO::PARAM_LOB);
        }
        return $q->execute();
    }

    public function delete(int $id): bool
    {
        $q = $this->db->prepare('DELETE FROM brands WHERE id = :id');
        $q->bindValue(':id', $id);
        return $q->execute();
    }
}
?>