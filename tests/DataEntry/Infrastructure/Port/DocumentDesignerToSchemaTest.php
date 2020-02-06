<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Infrastructure\Port;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Model\Transformation\AlwaysReturnTransformer;
use Star\Component\Document\Design\Domain\Model\Transformation\ValueTransformer;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class DocumentDesignerToSchemaTest extends TestCase
{
    public function test_it_should_transform_value_before_validation(): void
    {
        $document = DocumentBuilder::createDocument()
            ->createText('name')->transformedWith('transformer')->endProperty()
            ->getDocument();

        $factory = new DocumentDesignerToSchema(
            new DocumentCollection($document),
            new AlwaysReturnTransformer(
                new class() implements ValueTransformer {
                    public function transform($rawValue): RecordValue
                    {
                        return StringValue::fromString(\strrev($rawValue));
                    }
                }
            )
        );
        $schema = $factory->createSchema($document->getIdentity());
        $value = $schema->createValue(
            'name',
            'raw-value',
            $this->createMock(StrategyToHandleValidationErrors::class)
        );
        $this->assertSame('eulav-war', $value->toString());
    }
}
