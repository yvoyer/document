<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\Design\Domain\Model\DocumentRepository;

final class CreateRecordHandler
{
    /**
     * @var RecordRepository
     */
    private $records;

    /**
     * @var DocumentRepository
     */
    private $documents;

    /**
     * @var SchemaFactory
     */
    private $factory;

    public function __construct(
        RecordRepository $records,
        DocumentRepository $documents,
        SchemaFactory $factory
    ) {
        $this->records = $records;
        $this->documents = $documents;
        $this->factory = $factory;
    }

    public function __invoke(CreateRecord $command): void
    {
        $record = RecordAggregate::withoutValues(
            $command->recordId(),
            $this->factory->createSchema($command->documentId())
        );
        if (\count($command->valueMap()) > 0) {
            $record = RecordAggregate::withValues(
                $command->recordId(),
                $this->factory->createSchema($command->documentId()),
                $command->valueMap()
            );
        }

        $this->records->saveRecord($record->getIdentity(), $record);
    }
}
