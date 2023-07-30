<?php

declare(strict_types=1);

namespace App\RequestTypes;

use App\Entity\FailureReport as FailureReportEntity;
use App\Entity\ServiceRequestInterface;
use App\Model\ServiceRequest;

class FailureReport implements RequestTypeInterface
{
    public function isApplicable(ServiceRequest $request): bool {
        // There is no special condition to this type.
        return FALSE;
    }
    public function getEntity(): ServiceRequestInterface {
        return new FailureReportEntity();
    }

}
