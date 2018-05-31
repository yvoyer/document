<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Tools;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;

final class DocumentBuilderTest extends TestCase
{
    public function test_it_should_build_a_document_with_a_text_property()
    {
        $document = DocumentBuilder::createBuilder('id')
            ->createTextProperty('name')->endProperty()
            ->build();

        $this->assertInstanceOf(ReadOnlyDocument::class, $document);
        $this->assertInstanceOf(DocumentDesigner::class, $document);

        $this->assertFalse($document->isPublished());
        $this->assertFalse($document->getPropertyDefinition('name')->isRequired());
    }

    public function test_it_should_build_a_document_with_a_required_text_property()
    {
        $document = DocumentBuilder::createBuilder('id')
            ->createTextProperty('name')->required()->endProperty()
            ->build();

        $this->assertInstanceOf(ReadOnlyDocument::class, $document);
        $this->assertInstanceOf(DocumentDesigner::class, $document);

        $this->assertFalse($document->isPublished());
        $this->assertTrue($document->getPropertyDefinition('name')->isRequired());
    }
}
