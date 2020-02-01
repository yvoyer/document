<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Exception\NotFoundTransformer;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Transformation\AlwaysReturnTransformer;
use Star\Component\Document\Design\Domain\Model\Transformation\NeverFindTransformer;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\Document\Design\Domain\Model\Transformation\ValueTransformer;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;
use Star\Component\Document\Tools\DocumentBuilder;

final class AddValueTransformerOnPropertyHandlerTest extends TestCase
{
    public function test_it_should_add_transformer_to_property(): void
    {
        $property = PropertyName::fixture();
        $document = DocumentBuilder::createDocument()
            ->createText($property->toString())->endProperty()
            ->getDocument();

        $handler = new AddValueTransformerOnPropertyHandler(
            new AlwaysReturnTransformer($this->createMock(ValueTransformer::class)),
            new DocumentCollection($document)
        );

        $transformer = TransformerIdentifier::random();

        $this->assertFalse($document->getPropertyDefinition($property)->hasTransformer($transformer));

        $handler->__invoke(
            new AddValueTransformerOnProperty(
                $document->getIdentity(),
                $property,
                $transformer
            )
        );

        $this->assertTrue($document->getPropertyDefinition($property)->hasTransformer($transformer));
    }

    public function test_it_should_throw_exception_when_transformer_do_not_exists_in_factory(): void
    {
        $handler = new AddValueTransformerOnPropertyHandler(
            new NeverFindTransformer(),
            new DocumentCollection()
        );

        $this->expectException(NotFoundTransformer::class);
        $this->expectExceptionMessage('Transformer with id "not-found" could not be found.');
        $handler->__invoke(
            new AddValueTransformerOnProperty(
                DocumentId::random(),
                PropertyName::fixture(),
                TransformerIdentifier::fromString('not-found')
            )
        );
    }
}
