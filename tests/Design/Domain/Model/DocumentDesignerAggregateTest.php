<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;

final class DocumentDesignerAggregateTest extends TestCase
{
    /**
     * @var DocumentDesignerAggregate
     */
    private $document;

    public function setUp()
    {
        $this->document = new DocumentDesignerAggregate(new DocumentId('id'));
    }

    public function test_it_should_publish_document()
    {
        $this->assertFalse($this->document->isPublished());

        $this->document->publish();

        $this->assertTrue($this->document->isPublished());
    }

    public function test_it_should_create_property()
    {
        $this->assertAttributeCount(0, 'properties', $this->document);

        $this->document->createProperty(
            new PropertyName('name'),
            new NullableValue()
        );

        $this->assertAttributeCount(1, 'properties', $this->document);
    }
}
