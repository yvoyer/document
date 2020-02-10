<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Query;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;

final class RecordRow
{
    /**
     * @var DocumentRecord
     */
    private $record;

    /**
     * @param DocumentRecord $record
     */
    public function __construct(DocumentRecord $record)
    {
        $this->record = $record;
    }

    /**
     * @return RecordId
     */
    public function getRecordId(): RecordId
    {
        return $this->record->getIdentity();
    }

    /**
     * @param string $property
     *
     * @return string
     */
    public function getValue(string $property): string
    {
        return $this->record->getValue($property)->toReadableString();
    }
}
