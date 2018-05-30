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

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    /**
     * @return RecordId
     */
    public function recordId(): RecordId
    {
        return $this->recordId;
    }

    /**
     * @return string
     */
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

    /**
     * @param string $documentId
     * @param string $recordId
     * @param string $property
     * @param mixed $value
     *
     * @return SetRecordValue
     */
    public static function fromString(string $documentId, string $recordId, string $property, $value): self
    {
        return new self(new DocumentId($documentId), new RecordId($recordId), $property, $value);
    }
}
