<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class PropertyConstraintWasRemoved implements DocumentEvent
{
    /**
     * @var DocumentId
     */
    private $document;

    /**
     * @var PropertyName
     */
    private $propertyName;

    /**
     * @var string
     */
    private $constraintName;

    public function __construct(
        DocumentId $document,
        PropertyName $propertyName,
        string $constraintName
    ) {
        $this->document = $document;
        $this->propertyName = $propertyName;
        $this->constraintName = $constraintName;
    }

    public function documentId(): DocumentId
    {
        return $this->document;
    }

    public function propertyName(): PropertyName
    {
        return $this->propertyName;
    }

    public function constraintName(): string
    {
        return $this->constraintName;
    }
}
