<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;

final class SetRecordValueHandler
{
    /**
     * @var RecordRepository
     */
    private $records;

    /**
     * @var SchemaFactory
     */
    private $factory;

    public function __construct(RecordRepository $records, SchemaFactory $factory)
    {
        $this->records = $records;
        $this->factory = $factory;
    }

    public function __invoke(SetRecordValue $command): void
    {
        $recordId = $command->recordId();
        if ($this->records->recordExists($recordId)) {
            $record = $this->records->getRecordWithIdentity($recordId);
        } else {
            $record = new RecordAggregate($recordId, $this->factory->createSchema($command->documentId()));
        }

        $record->setValue($command->property(), $command->value());

        $this->records->saveRecord($recordId, $record);
    }
}
