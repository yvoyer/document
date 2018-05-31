<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentCollection;

final class CreatePropertyHandlerTest extends TestCase
{
    /**
     * @var CreatePropertyHandler
     */
    private $handler;

    /**
     * @var DocumentCollection
     */
    private $documents;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DocumentDesigner
     */
    private $document;

    public function setUp()
    {
        $this->document = $this->createMock(DocumentDesigner::class);
        $this->handler = new CreatePropertyHandler(
            $this->documents = new DocumentCollection()
        );
    }

    public function test_it_should_create_a_property()
    {
        $this->documents->saveDocument($id = new DocumentId('id'), $this->document);
        $this->document
            ->expects($this->once())
            ->method('createProperty');

        $this->handler->__invoke(
            CreateProperty::fromString(
                $id->toString(),
                PropertyDefinition::fromString('name', StringType::class)
            )
        );
    }

    /**
     * @expectedException        \Star\Component\Identity\Exception\EntityNotFoundException
     * @expectedExceptionMessage with identity 'invalid' could not be found.
     */
    public function test_it_should_throw_exception_when_document_not_found()
    {
        $handler = $this->handler;
        $handler(
            CreateProperty::fromString(
                'invalid',
                PropertyDefinition::fromString('name', StringType::class)
            )
        );
    }
}
