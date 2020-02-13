<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\Schema\CallbackSchemaFactory;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

final class CallbackSchemaFactoryTest extends TestCase
{
    public function test_it_should_create_schema_from_callback(): void
    {
        $factory = new CallbackSchemaFactory(
            function (DocumentId $id): DocumentSchema {
                return new DocumentSchema($id);
            }
        );
        $this->assertSame(
            '{"id":"did","properties":[]}',
            $factory->createSchema(DocumentId::fromString('did'))->toString()
        );
    }
}
