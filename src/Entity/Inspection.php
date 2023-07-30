<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\ServiceRequest;

class Inspection extends JsonEntityBase implements ServiceRequestInterface
{
    use ServiceRequestTrait;
    public const TYPE = 'przegląd';
    public const SCHEDULED_STATUS = 'zaplanowano';
    public const NEW_STATUS = 'nowy';

    #[JSON\Column]
    protected ?int $id = null;

    #[JSON\Column]
    #[JSON\Unique]
    protected ?string $description = null;

    #[JSON\Column]
    protected ?string $type = null;

    #[JSON\Column]
    protected ?\DateTimeInterface $date = null;

    #[JSON\Column]
    protected ?int $week = null;

    #[JSON\Column]
    protected ?string $status = null;

    #[JSON\Column]
    protected ?string $postInspectionServices = null;

    #[JSON\Column]
    protected ?string $phone = null;

    #[JSON\Column]
    protected ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(?int $week): static
    {
        $this->week = $week;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPostInspectionServices(): ?string
    {
        return $this->postInspectionServices;
    }

    public function setPostInspectionServices(?string $postInspectionServices): static
    {
        $this->postInspectionServices = $postInspectionServices;

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
        $this->setDescription($request->getDescription());
        $this->setPhone($request->getPhone());
        $this->setType(self::TYPE);
        if ($date = $request->getDate()) {
            $date = $this->parseDate($date);
            $this->setDate($date);
            $this->setWeek((int) $date->format('W'));
        }
        $this->setStatusByDate($request->getDate());

        return $this;
    }
}
