<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\DomainEvent\DomainEvent;

final class TransformerAddedOnProperty implements DomainEvent
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
     * @var TransformerIdentifier
     */
    private $identifier;

    public function __construct(
        DocumentId $documentId,
        PropertyName $property,
        TransformerIdentifier $identifier
    ) {
        $this->documentId = $documentId;
        $this->property = $property;
        $this->identifier = $identifier;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function property(): PropertyName
    {
        return $this->property;
    }

    public function identifier(): TransformerIdentifier
    {
        return $this->identifier;
    }
}
