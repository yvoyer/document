<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class PropertyParameterAdded implements DocumentEvent
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
     * @var PropertyParameter
     */
    private $parameter;

    public function __construct(
        DocumentId $documentId,
        PropertyName $property,
        string $parameterName,
        PropertyParameter $parameter
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
        $this->parameterName = $parameterName;
        $this->parameter = $parameter;
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

    public function parameter(): PropertyParameter
    {
        return $this->parameter;
    }
}
