<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;

final class SetRecordValueHandler
{
    /**
     * @var RecordRepository
     */
    private $records;

    public function __construct(RecordRepository $records)
    {
        $this->records = $records;
    }

    public function __invoke(SetRecordValue $command): void
    {
        $record = $this->records->getRecordWithIdentity($command->recordId());
        $record->setValue(
            $command->property(),
            $command->value(),
            new AlwaysThrowExceptionOnValidationErrors()
        );

        $this->records->saveRecord($record->getIdentity(), $record);
    }
}
