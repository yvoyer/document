<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValue;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\SetRecordValueHandler;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\DocumentAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Identity\Exception\EntityNotFoundException;
use function sprintf;

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
            $this->records = new RecordCollection()
        );
    }

    public function test_it_should_throw_exception_when_record_do_not_exists(): void
    {
        $this->assertCount(0, $this->records);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                "Object of class '%s' with identity 'id' could not be found.",
                DocumentRecord::class
            )
        );
        $this->handler->__invoke(
            new SetRecordValue(
                DocumentTypeId::random(),
                RecordId::fromString('id'),
                'name',
                StringValue::fromString('value')
            )
        );
    }

    public function test_it_should_return_all_records_when_values_entered(): void
    {
        $documentId = DocumentTypeId::fromString('id');
        $recordId = RecordId::fromString('id');

        $this->records->saveRecord(
            $recordId,
            DocumentAggregate::withValues(
                $recordId,
                DocumentTypeBuilder::startDocumentTypeFixture($documentId->toString())
                    ->createText('p1')->endProperty()
                    ->createText('p2')->endProperty()
                    ->createText('p3')->endProperty()
                    ->getSchema()
            )
        );
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p1', StringValue::fromString('v1'))
        );
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p2', StringValue::fromString('v2'))
        );
        $this->handler->__invoke(
            new SetRecordValue($documentId, $recordId, 'p3', StringValue::fromString('v3'))
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
        $recordId = RecordId::fromString('r1');
        $record = DocumentAggregate::withValues(
            $recordId,
            DocumentTypeBuilder::startDocumentTypeFixture()->createText('name')->endProperty()->getSchema(),
            [
                'name' => StringValue::fromString('old-value'),
            ]
        );
        $this->records->saveRecord($recordId, $record);
        $this->assertCount(1, $this->records);

        $this->assertSame('old-value', $record->getValue('name')->toString());

        $this->handler->__invoke(
            new SetRecordValue(
                DocumentTypeId::random(),
                $recordId,
                'name',
                StringValue::fromString('new-value')
            )
        );

        $this->assertSame('new-value', $record->getValue('name')->toString());
    }
}
