<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FailureReport;
use App\Entity\JsonEntityBase;
use App\Entity\ServiceRequestInterface;
use App\Model\ServiceRequest;
use App\RequestTypes\RequestTypeInterface;

class RequestTypeParser
{
    private const TYPE_PARSER_TAG = 'app.request_types';

    /**
     * @var RequestTypeInterface[]
     */
    private array $parsers;

    public function __construct() {
        $this->parsers = [];
    }

    public function addTypeParser( RequestTypeInterface $type): void {
        $this->parsers[] = $type;
    }


    public function processRequest(ServiceRequest $request): ServiceRequestInterface {
        foreach ($this->parsers as $parser) {
            if ($parser->isApplicable($request)) {
                $entity = $parser->getEntity($request);
            }
        }

        // The FailureReport is a default type.
        return $entity ?? new FailureReport();
    }

}
