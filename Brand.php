<?php
class Brand
{
    private int $id;
    private string $name;
    private ?string $logoUrl;
    private ?string $logoBlob;
    private ?string $createdAt;

    public function __construct(int $id, string $name, ?string $logoUrl = null, ?string $logoBlob = null, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->logoUrl = $logoUrl;
        $this->logoBlob = $logoBlob;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getLogoUrl(): ?string { return $this->logoUrl; }
    public function getLogoBlob(): ?string { return $this->logoBlob; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setLogoUrl(?string $logoUrl): void { $this->logoUrl = $logoUrl; }
    public function setLogoBlob(?string $logoBlob): void { $this->logoBlob = $logoBlob; }
}
?>