<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\ServiceRequest;

class InvalidRequest extends JsonEntityBase implements ServiceRequestInterface
{
    #[JSON\Column]
    protected ?int $id = null;

    #[JSON\Column]
    protected ?int $number;

    #[JSON\Column]
    protected ?string $description;

    #[JSON\Column]
    protected ?string $dueDate;

    #[JSON\Column]
    protected ?string $phone;

    #[JSON\Column]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int {
        return $this->number;
    }

    public function setNumber(?int $number): void {
        $this->number = $number;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    public function setDueDate(?string $date): static
    {
        $this->dueDate = $date;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function setByServiceRequest(ServiceRequest $request): ServiceRequestInterface
    {
        $this->setNumber($request->getNumber());
        $this->setDescription($request->getDescription());
        $this->setDueDate($request->getDate());
        $this->setPhone($request->getPhone());

        return $this;
    }
}
