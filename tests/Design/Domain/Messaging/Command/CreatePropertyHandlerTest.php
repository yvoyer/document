<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\NullableValue;
use Star\Component\Document\Design\Domain\Model\PropertyName;
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
     * @var \PHPUnit_Framework_MockObject_MockObject
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
        $handler = $this->handler;
        $this->documents->saveDocument($id = new DocumentId('id'), $this->document);
        $this->document
            ->expects($this->once())
            ->method('createProperty');

        $handler(
            new CreateProperty($id, new PropertyName('name'), new NullableValue())
        );
    }

    /**
     * @expectedException        \Star\Component\Identity\Exception\EntityNotFoundException
     * @expectedExceptionMessage Object of class 'Star\Component\Document\Design\Domain\Model\DocumentDesigner' with identity 'invalid' could not be found.
     */
    public function test_it_should_throw_exception_when_document_not_found()
    {
        $handler = $this->handler;
        $handler(
            new CreateProperty(new DocumentId('invalid'), new PropertyName('name'), new NullableValue())
        );
    }
}
