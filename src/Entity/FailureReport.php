<?php

declare(strict_types=1);

namespace App\Entity;
use App\Model\ServiceRequest;

class FailureReport extends JsonEntityBase implements ServiceRequestInterface
{
    use ServiceRequestTrait;
    public const TYPE = 'zgłoszenie awarii';
    public const PRIORITY_URGENT = 'pilne';
    public const PRIORITY_CRITICAL = 'krytyczny';
    public const PRIORITY_NORMAL = 'normalny';
    public const NEW_STATUS = 'nowy';
    public const SCHEDULED_STATUS = 'termin';
    #[JSON\Column]
    protected ?int $id = null;

    #[JSON\Column]
    #[JSON\Unique]
    protected ?string $description = null;

    #[JSON\Column]
    protected ?string $type = null;

    #[JSON\Column]
    protected ?string $priority = null;

    #[JSON\Column]
    protected ?\DateTimeInterface $dateOfService = null;

    #[JSON\Column]
    protected ?string $status = null;

    #[JSON\Column]
    protected ?string $notices = null;

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

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(?string $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDateOfService(): ?\DateTimeInterface
    {
        return $this->dateOfService;
    }

    public function setDateOfService(?\DateTimeInterface $dateOfService): static
    {
        $this->dateOfService = $dateOfService;

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

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(?string $notices): static
    {
        $this->notices = $notices;

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

    public function setPriorityByDescription(): void
    {
        if (preg_match('/\bbardzo pilne\b/iu', $this->getDescription())) {
            $this->setPriority(self::PRIORITY_CRITICAL);
        } elseif (preg_match('/\b(bardzo\s*)?pilne(!|\p{P})?\b/iu', $this->getDescription())) {
            $this->setPriority(self::PRIORITY_URGENT);
        } else {
            $this->setPriority(self::PRIORITY_NORMAL);
        }
    }

    public function setByServiceRequest(ServiceRequest $request): ServiceRequestInterface
    {
        $this->setDescription($request->getDescription());
        $this->setPhone($request->getPhone());
        $this->setPriorityByDescription($request->getDescription());
        if ($date = $request->getDate()) {
            $this->setDateOfService($this->parseDate($date));
        }
        $this->setStatusByDate($request->getDate());

        return $this;
    }
}
