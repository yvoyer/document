<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\AlwaysReturnSchema;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Stub\StubSchema;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;

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

    public function setUp()
    {
        $this->handler = new SetRecordValueHandler(
            $this->records = new RecordCollection(),
            new AlwaysReturnSchema(StubSchema::allOptional())
        );
    }

    public function test_it_should_return_not_data_when_no_value_entered()
    {
        $this->assertCount(0, $this->records);

        $recordId = new RecordId('id');
        $this->handler->__invoke(
            SetRecordValue::fromString('id', $recordId->toString(), 'name', 'value')
        );

        $this->assertCount(1, $this->records);
        $this->assertInstanceOf(
            DocumentRecord::class,
            $record = $this->records->getRecordWithIdentity($recordId)
        );
        $this->assertSame('value', $record->getValue('name')->toString());
    }

    public function test_it_should_return_all_records_when_values_entered()
    {
        $documentId = new DocumentId('id');
        $recordId = new RecordId('id');
        $this->handler->__invoke(
            SetRecordValue::fromString($documentId->toString(), $recordId->toString(), 'p1', 'v1')
        );
        $this->handler->__invoke(
            SetRecordValue::fromString($documentId->toString(), $recordId->toString(), 'p2', 'v2')
        );
        $this->handler->__invoke(
            SetRecordValue::fromString($documentId->toString(), $recordId->toString(), 'p3', 'v3')
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

    public function test_it_should_use_the_old_record_to_store_value()
    {
        $recordId = new RecordId('r1');
        $record = new RecordAggregate($recordId, StubSchema::allOptional());
        $record->setValue('name', 'old-value');
        $this->records->saveRecord($recordId, $record);
        $this->assertCount(1, $this->records);

        $this->assertSame('old-value', $record->getValue('name')->toString());

        $this->handler->__invoke(
            SetRecordValue::fromString('id', $recordId->toString(), 'name', 'new-value')
        );

        $this->assertSame('new-value', $record->getValue('name')->toString());
    }
}
