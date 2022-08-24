<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Messaging\Command;

final class CreateRecord implements Command
{
    /**
     * @var DocumentTypeId
     */
    private $documentId;

    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var RecordValue[]
     */
    private $values = [];

    /**
     * @param DocumentTypeId $documentId
     * @param RecordId $recordId
     * @param RecordValue[] $values
     */
    public function __construct(
        DocumentTypeId $documentId,
        RecordId $recordId,
        array $values
    ) {
        Assertion::allString(
            \array_keys($values),
            'Keys of value map "%s" is expected to be the name of the property, "%s" given.'
        );
        Assertion::allIsInstanceOf(
            $values,
            RecordValue::class,
            'Value in value map "%s" was expected to be an instances of "%s".'
        );
        $this->documentId = $documentId;
        $this->recordId = $recordId;
        $this->values = $values;
    }

    public function documentId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    /**
     * @return RecordValue[]
     */
    public function valueMap(): array
    {
        return $this->values;
    }
}
