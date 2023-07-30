<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;

trait ServiceRequestTrait {
    protected function parseDate(string $date): ?DateTimeInterface {
        $date = strtotime($date);
        if (!is_int($date)) {
            return NULL;
        }
        return \DateTime::createFromFormat('U', (string) $date);
    }

    protected function setStatusByDate(?string $date): void
    {
        if (empty($date)) {
            $this->setStatus(self::SCHEDULED_STATUS);
        }
        else {
            $this->setStatus(self::NEW_STATUS);
        }
    }
}
