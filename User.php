<?php
class User
{
    private int $id;
    private string $name;
    private string $email;
    private ?string $password;
    private ?string $googleId;
    private ?string $phone;
    private ?string $address;
    private ?string $createdAt;

    public function __construct(
        int $id,
        string $name,
        string $email,
        ?string $password = null,
        ?string $googleId = null,
        ?string $phone = null,
        ?string $address = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->googleId = $googleId;
        $this->phone = $phone;
        $this->address = $address;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): ?string { return $this->password; }
    public function getGoogleId(): ?string { return $this->googleId; }
    public function getPhone(): ?string { return $this->phone; }
    public function getAddress(): ?string { return $this->address; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPassword(?string $password): void { $this->password = $password; }
    public function setGoogleId(?string $googleId): void { $this->googleId = $googleId; }
    public function setPhone(?string $phone): void { $this->phone = $phone; }
    public function setAddress(?string $address): void { $this->address = $address; }
}
?>