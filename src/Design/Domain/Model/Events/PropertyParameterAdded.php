<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\DomainEvent\DomainEvent;

final class PropertyParameterAdded implements DomainEvent
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
     * @var PropertyParameter
     */
    private $parameter;

    public function __construct(
        DocumentId $documentId,
        PropertyName $property,
        PropertyParameter $parameter
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
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

    public function parameter(): PropertyParameter
    {
        return $this->parameter;
    }
}
