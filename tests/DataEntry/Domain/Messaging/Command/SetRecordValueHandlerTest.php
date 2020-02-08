<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\AlwaysReturnSchema;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Domain\Model\Builders\SchemaBuilder;

final class SetRecordValueHandlerTest extends TestCase
{
    /**
     * @var SetRecordValueHandler
     */
    private $handler;

    /**
     * @var RecordCollection
     */
    private $records;

    public function setUp(): void
    {
        $this->handler = new SetRecordValueHandler(
            $this->records = new RecordCollection(),
            new AlwaysReturnSchema(SchemaBuilder::create(RecordId::random())->getSchema())
        );
    }

    public function test_it_should_return_not_data_when_no_value_entered(): void
    {
        $this->assertCount(0, $this->records);

        $recordId = new RecordId('id');
        $this->handler->__invoke(
            new SetRecordValue(DocumentId::random(), $recordId, 'name', 'value')
        );

        $this->assertCount(1, $this->records);
        $this->assertInstanceOf(
            DocumentRecord::class,
            $record = $this->records->getRecordWithIdentity($recordId)
        );
        $this->assertSame('value', $record->getValue('name')->toString());
    }

    public function test_it_should_return_all_records_when_values_entered(): void
    {
        $documentId = DocumentId::fromString('id');
        $recordId = new RecordId('id');
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p1', 'v1')
        );
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p2', 'v2')
        );
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p3', 'v3')
        );

        $this->assertCount(1, $this->records);
        $this->assertInstanceOf(
            DocumentRecord::class,
            $record = $this->records->getRecordWithIdentity($recordId)
        );
        $this->assertSame('v1', $record->getValue('p1')->toString());
        $this->assertSame('v2', $record->getValue('p2')->toString());
        $this->assertSame('v3', $record->getValue('p3')->toString());
    }

    public function test_it_should_use_the_old_record_to_store_value(): void
    {
        $recordId = new RecordId('r1');
        $record = RecordAggregate::withValues($recordId, SchemaBuilder::create($recordId)->getSchema(), []);
        $record->setValue(
            'name',
            'old-value',
            $this->createMock(StrategyToHandleValidationErrors::class)
        );
        $this->records->saveRecord($recordId, $record);
        $this->assertCount(1, $this->records);

        $this->assertSame('old-value', $record->getValue('name')->toString());

        $this->handler->__invoke(
            new SetRecordValue(DocumentId::random(), $recordId, 'name', 'new-value')
        );

        $this->assertSame('new-value', $record->getValue('name')->toString());
    }
}
