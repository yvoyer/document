<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use Star\Component\Document\DataEntry\Domain\Model\DocumentAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordRepository;
use Star\Component\Document\DataEntry\Domain\Model\SchemaFactory;
use Star\Component\Document\Design\Domain\Model\DocumentTypeRepository;

final class CreateRecordHandler
{
    /**
     * @var RecordRepository
     */
    private $records;

    /**
     * @var DocumentTypeRepository
     */
    private $documents;

    /**
     * @var SchemaFactory
     */
    private $factory;

    public function __construct(
        RecordRepository $records,
        DocumentTypeRepository $documents,
        SchemaFactory $factory
    ) {
        $this->records = $records;
        $this->documents = $documents;
        $this->factory = $factory;
    }

    public function __invoke(CreateRecord $command): void
    {
        $record = DocumentAggregate::withValues(
            $command->recordId(),
            $this->factory->createSchema($command->documentId()),
            $command->valueMap()
        );

        $this->records->saveRecord($record->getIdentity(), $record);
    }
}
