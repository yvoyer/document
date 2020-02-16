<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraintHandler;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\ConstraintFactory;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class AddPropertyConstraintHandlerTest extends TestCase
{
    /**
     * @var AddPropertyConstraintHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    public function setUp(): void
    {
        $this->documents = new DocumentCollection();
        $this->handler = new AddPropertyConstraintHandler(
            $this->documents,
            new ConstraintFactory(['const' => NoConstraint::class])
        );
    }

    public function test_it_should_change_the_attribute_of_the_property(): void
    {
        $document = DocumentBuilder::createDocument('d')
            ->createText($name = 'name')->endProperty()
            ->getDocument();
        $this->documents->saveDocument($document);

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertFalse($visitor->hasPropertyConstraint($name, 'const'));

        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                PropertyName::fromString($name),
                'const',
                []
            )
        );

        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        self::assertTrue($visitor->hasPropertyConstraint($name, 'const'));
    }

    public function test_it_should_throw_exception_when_property_not_found_in_document(): void
    {
        $document = DocumentBuilder::createDocument('d')->getDocument();
        $this->documents->saveDocument($document);

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->handler->__invoke(
            new AddPropertyConstraint(
                $document->getIdentity(),
                PropertyName::fromString('not found'),
                'const',
                []
            )
        );
    }
}
