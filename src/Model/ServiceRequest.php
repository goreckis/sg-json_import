<?php

declare(strict_types=1);

namespace App\Model;

class ServiceRequest {
    private ?int $number =  NULL;

    private ?string $description =  NULL;

    private ?string $dueDate =  NULL;

    private ?string $phone =  NULL;

    public function setNumber(?int $number): void {
        $this->number = $number;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setDueDate(?string $dueDate): void {
        $this->dueDate = $dueDate;
    }

    public function setPhone(?string $phone): void {
        $this->phone = $phone;
    }

    public function getNumber(): ?int {
        return $this->number;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getDate(): ?string {
        return $this->dueDate;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

}
