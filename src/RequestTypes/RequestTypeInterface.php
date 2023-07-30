<?php

declare(strict_types=1);

namespace App\RequestTypes;

use App\Entity\Inspection as InspectionEntity;
use App\Entity\ServiceRequestInterface;
use App\Model\ServiceRequest;

#[AutoconfigureTag('app.request_types')]
interface RequestTypeInterface
{
    public function isApplicable(ServiceRequest $request): bool;
    public function getEntity(): ServiceRequestInterface;

}
