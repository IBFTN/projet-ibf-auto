<?php
class Contact
{
    private int $id;
    private string $name;
    private string $email;
    private ?string $phone;
    private ?string $subject;
    private string $message;
    private ?string $createdAt;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $message,
        ?string $phone = null,
        ?string $subject = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->message = $message;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getSubject(): ?string { return $this->subject; }
    public function getMessage(): string { return $this->message; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPhone(?string $phone): void { $this->phone = $phone; }
    public function setSubject(?string $subject): void { $this->subject = $subject; }
    public function setMessage(string $message): void { $this->message = $message; }
}
?>