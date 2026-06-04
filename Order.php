<?php
class Order
{
    private int $id;
    private int $carId;
    private ?int $userId;
    private ?string $customerName;
    private ?string $customerEmail;
    private ?string $customerPhone;
    private string $status;
    private ?string $createdAt;

    public function __construct(
        int $id,
        int $carId,
        ?int $userId = null,
        ?string $customerName = null,
        ?string $customerEmail = null,
        ?string $customerPhone = null,
        string $status = 'pending',
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->carId = $carId;
        $this->userId = $userId;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->customerPhone = $customerPhone;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

    public function getId(): int { return $this->id; }
    public function getCarId(): int { return $this->carId; }
    public function getUserId(): ?int { return $this->userId; }
    public function getCustomerName(): ?string { return $this->customerName; }
    public function getCustomerEmail(): ?string { return $this->customerEmail; }
    public function getCustomerPhone(): ?string { return $this->customerPhone; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setCarId(int $carId): void { $this->carId = $carId; }
    public function setUserId(?int $userId): void { $this->userId = $userId; }
    public function setCustomerName(?string $customerName): void { $this->customerName = $customerName; }
    public function setCustomerEmail(?string $customerEmail): void { $this->customerEmail = $customerEmail; }
    public function setCustomerPhone(?string $customerPhone): void { $this->customerPhone = $customerPhone; }
    public function setStatus(string $status): void { $this->status = $status; }
}
?>