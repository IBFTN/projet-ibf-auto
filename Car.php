<?php
class Car
{
    private int $id;
    private int $brandId;
    private string $model;
    private int $year;
    private float $price;
    private ?string $engine;
    private ?string $power;
    private ?string $drive;
    private ?string $description;
    private ?string $imageUrl;
    private ?string $imageBlob;
    private int $sold;
    private ?string $createdAt;

    public function __construct(
        int $id,
        int $brandId,
        string $model,
        int $year,
        float $price,
        ?string $engine = null,
        ?string $power = null,
        ?string $drive = null,
        ?string $description = null,
        ?string $imageUrl = null,
        ?string $imageBlob = null,
        int $sold = 0,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->brandId = $brandId;
        $this->model = $model;
        $this->year = $year;
        $this->price = $price;
        $this->engine = $engine;
        $this->power = $power;
        $this->drive = $drive;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->imageBlob = $imageBlob;
        $this->sold = $sold;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getBrandId(): int { return $this->brandId; }
    public function getModel(): string { return $this->model; }
    public function getYear(): int { return $this->year; }
    public function getPrice(): float { return $this->price; }
    public function getEngine(): ?string { return $this->engine; }
    public function getPower(): ?string { return $this->power; }
    public function getDrive(): ?string { return $this->drive; }
    public function getDescription(): ?string { return $this->description; }
    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function getImageBlob(): ?string { return $this->imageBlob; }
    public function getSold(): int { return $this->sold; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setBrandId(int $brandId): void { $this->brandId = $brandId; }
    public function setModel(string $model): void { $this->model = $model; }
    public function setYear(int $year): void { $this->year = $year; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setEngine(?string $engine): void { $this->engine = $engine; }
    public function setPower(?string $power): void { $this->power = $power; }
    public function setDrive(?string $drive): void { $this->drive = $drive; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setImageUrl(?string $imageUrl): void { $this->imageUrl = $imageUrl; }
    public function setImageBlob(?string $imageBlob): void { $this->imageBlob = $imageBlob; }
    public function setSold(int $sold): void { $this->sold = $sold; }
}
?>