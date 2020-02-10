<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Assert\Assertion;
use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;

final class CreateRecord implements Command
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var RecordId
     */
    private $recordId;

    /**
     * @var mixed[]
     */
    private $values = [];

    public function __construct(
        DocumentId $documentId,
        RecordId $recordId,
        array $values
    ) {
        Assertion::allString(
            \array_keys($values),
            'Keys of value map "%s" is expected to be the name of the property, "%s" given.'
        );
        Assertion::allScalar($values, 'Values of value map "%s" is expected to be a scalar.');
        $this->documentId = $documentId;
        $this->recordId = $recordId;
        $this->values = $values;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function valueMap(): array
    {
        return $this->values;
    }
}
