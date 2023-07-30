<?php

namespace App\Entity;

class Inspection extends JsonEntityBase
{
    #[JSON\Column]
    protected ?int $id = null;

    #[JSON\Column]
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
}
