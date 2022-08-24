<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecord;
use Star\Component\Document\DataEntry\Domain\Messaging\Command\CreateRecordHandler;
use Star\Component\Document\DataEntry\Domain\Model\AlwaysReturnSchema;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\DataEntry\Infrastructure\Persistence\InMemory\RecordCollection;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class CreateRecordHandlerTest extends TestCase
{
    public function test_it_should_create_record_without_values(): void
    {
        $handler = new CreateRecordHandler(
            $records = new RecordCollection(),
            new DocumentTypeCollection(),
            new AlwaysReturnSchema(DocumentTypeBuilder::startDocumentTypeFixture()->getSchema())
        );
        $this->assertCount(0, $records);

        $handler(new CreateRecord(DocumentTypeId::random(), DocumentId::random(), []));

        $this->assertCount(1, $records);
    }

    public function test_it_should_create_record_with_values(): void
    {
        $handler = new CreateRecordHandler(
            $records = new RecordCollection(),
            new DocumentTypeCollection(),
            new AlwaysReturnSchema(
                DocumentTypeBuilder::startDocumentTypeFixture()
                    ->createText('key')->endProperty()
                    ->getSchema()
            )
        );
        $this->assertCount(0, $records);

        $handler(new CreateRecord(
            DocumentTypeId::random(),
            $recordId = DocumentId::random(),
            [
                'key' => StringValue::fromString('value'),
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
        new CreateRecord(DocumentTypeId::random(), DocumentId::random(), ['value']);
    }

    public function test_it_should_throw_exception_when_value_not_scalar(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Value in value map "stdClass" was expected to be an instances of "%s".',
                RecordValue::class
            )
        );
        new CreateRecord(DocumentTypeId::random(), DocumentId::random(), ['property' => new \stdClass()]);
    }
}
