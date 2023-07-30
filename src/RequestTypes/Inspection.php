<?php

declare(strict_types=1);

namespace App\RequestTypes;

use App\Entity\ServiceRequestInterface;
use App\Model\ServiceRequest;
use \App\Entity\Inspection as InspectionEntity;

class Inspection implements RequestTypeInterface
{
    public function isApplicable(ServiceRequest $request): bool {
        if (!$request->getDescription()) {
            return FALSE;
        }
        return str_contains($request->getDescription(), 'przegląd');
    }

    public function getEntity(): ServiceRequestInterface {
        return new InspectionEntity();
    }

}
