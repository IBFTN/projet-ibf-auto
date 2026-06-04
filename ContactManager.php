<?php
require_once "Contact.php";

class ContactManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function add(Contact $contact): bool
    {
        if ($contact->getId() === 0) {
            $q = $this->db->prepare('INSERT INTO contacts (name, email, phone, subject, message)
                                     VALUES (:name, :email, :phone, :subject, :message)');
            $q->bindValue(':name', $contact->getName());
            $q->bindValue(':email', $contact->getEmail());
            $q->bindValue(':phone', $contact->getPhone());
            $q->bindValue(':subject', $contact->getSubject());
            $q->bindValue(':message', $contact->getMessage());
        } else {
            $q = $this->db->prepare('INSERT INTO contacts (id, name, email, phone, subject, message)
                                     VALUES (:id, :name, :email, :phone, :subject, :message)');
            $q->bindValue(':id', $contact->getId());
            $q->bindValue(':name', $contact->getName());
            $q->bindValue(':email', $contact->getEmail());
            $q->bindValue(':phone', $contact->getPhone());
            $q->bindValue(':subject', $contact->getSubject());
            $q->bindValue(':message', $contact->getMessage());
        }
        return $q->execute();
    }

    public function get(int $id): ?Contact
    {
        $q = $this->db->prepare('SELECT * FROM contacts WHERE id = :id');
        $q->bindValue(':id', $id);
        $q->execute();
        $row = $q->fetch();
        if (!$row) return null;
        return new Contact(
            $row['id'], $row['name'], $row['email'], $row['message'],
            $row['phone'] ?? null, $row['subject'] ?? null, $row['created_at'] ?? null
        );
    }

    public function getAll(): array
    {
        $q = $this->db->query('SELECT * FROM contacts ORDER BY created_at DESC');
        $contacts = [];
        while ($row = $q->fetch()) {
            $contacts[] = new Contact(
                $row['id'], $row['name'], $row['email'], $row['message'],
                $row['phone'] ?? null, $row['subject'] ?? null, $row['created_at'] ?? null
            );
        }
        return $contacts;
    }

    public function delete(int $id): bool
    {
        $q = $this->db->prepare('DELETE FROM contacts WHERE id = :id');
        $q->bindValue(':id', $id);
        return $q->execute();
    }
}
?>