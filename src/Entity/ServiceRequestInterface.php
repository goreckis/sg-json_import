<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\ServiceRequest;

interface ServiceRequestInterface {
    public function setByServiceRequest(ServiceRequest $request): self;
}
