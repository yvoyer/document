<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameter;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameterHandler;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class AddPropertyParameterHandlerTest extends TestCase
{
    public function test_it_should_add_parameter(): void
    {
        $document = DocumentBuilder::createDocument()
            ->createText($property = 'name')->endProperty()
            ->getDocument();

        $handler = new AddPropertyParameterHandler(
            new DocumentCollection($document)
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyParameter($property, 'param'));

        $handler->__invoke(
            new AddPropertyParameter(
                $document->getIdentity(),
                PropertyName::fromString($property),
                'param',
                ParameterData::fromParameter(new NullParameter())
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyParameter($property, 'param'));
    }
}
