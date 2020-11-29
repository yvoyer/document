<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\DomainEvent\Messaging\Command;

final class AddPropertyParameter implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $property;

    /**
     * @var string
     */
    private $parameterName;

    /**
     * @var ParameterData
     */
    private $parameterData;

    public function __construct(
        DocumentId $documentId,
        PropertyName $property,
        string $parameterName,
        ParameterData $parameterData
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
        $this->parameterName = $parameterName;
        $this->parameterData = $parameterData;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function property(): PropertyName
    {
        return $this->property;
    }

    public function parameterName(): string
    {
        return $this->parameterName;
    }

    public function parameterData(): ParameterData
    {
        return $this->parameterData;
    }
}
