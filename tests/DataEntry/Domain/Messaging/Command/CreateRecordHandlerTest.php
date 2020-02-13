<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\AlwaysReturnSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Domain\Model\Schema\SchemaBuilder;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class CreateRecordHandlerTest extends TestCase
{
    public function test_it_should_create_record_without_values(): void
    {
        $handler = new CreateRecordHandler(
            $records = new RecordCollection(),
            new DocumentCollection(),
            new AlwaysReturnSchema(SchemaBuilder::create()->getSchema())
        );
        $this->assertCount(0, $records);

        $handler(new CreateRecord(DocumentId::random(), RecordId::random(), []));

        $this->assertCount(1, $records);
    }

    public function test_it_should_create_record_with_values(): void
    {
        $handler = new CreateRecordHandler(
            $records = new RecordCollection(),
            new DocumentCollection(),
            new AlwaysReturnSchema(
                SchemaBuilder::create()
                    ->addText('key')->endProperty()
                    ->getSchema()
            )
        );
        $this->assertCount(0, $records);

        $handler(new CreateRecord(
            DocumentId::random(),
            $recordId = RecordId::random(),
            [
                'key' => 'value',
            ]
        ));

        $this->assertCount(1, $records);
        $this->assertSame(
            'value',
            $records->getRecordWithIdentity($recordId)->getValue('key')->toString()
        );
    }

    public function test_it_should_throw_exception_when_index_not_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Keys of value map "0" is expected to be the name of the property, "integer" given.'
        );
        new CreateRecord(DocumentId::random(), RecordId::random(), ['value']);
    }

    public function test_it_should_throw_exception_when_value_not_scalar(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Values of value map "stdClass" is expected to be a scalar.');
        new CreateRecord(DocumentId::random(), RecordId::random(), ['property' => new \stdClass()]);
    }
}
