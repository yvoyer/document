<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\Common\Domain\Messaging\Command;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;

final class SetRecordValue implements Command
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
     * @var string
     */
    private $property;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param DocumentId $documentId
     * @param RecordId $recordId
     * @param string $property
     * @param mixed $value
     */
    public function __construct(
        DocumentId $documentId,
        RecordId $recordId,
        string $property,
        $value
    ) {
        $this->documentId = $documentId;
        $this->recordId = $recordId;
        $this->property = $property;
        $this->value = $value;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    public function property(): string
    {
        return $this->property;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
