<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameter;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyParameterHandler;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class AddPropertyParameterHandlerTest extends TestCase
{
    public function test_it_should_add_parameter(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture()
            ->createText($code = 'name')->endProperty()
            ->getDocumentType();

        $handler = new AddPropertyParameterHandler(
            new DocumentTypeCollection($document)
        );
        $code = PropertyCode::fromString($code);

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyParameter($code, 'param'));

        $handler->__invoke(
            new AddPropertyParameter(
                $document->getIdentity(),
                $code,
                'param',
                ParameterData::fromParameter(new NullParameter()),
                AuditDateTime::fromNow()
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyParameter($code, 'param'));
    }
}
