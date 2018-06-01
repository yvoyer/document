<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Types;

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
        $this->assertInstanceOf(
            Types\StringType::class,
            $document->getPropertyDefinition('name')->getType()
        );
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

    public function test_it_should_create_a_boolean_property()
    {
        $document = DocumentBuilder::createBuilder('id')
            ->createBooleanProperty('bool')->endProperty()
            ->build();
        $this->assertInstanceOf(
            Types\BooleanType::class,
            $document->getPropertyDefinition('bool')->getType()
        );
    }

    public function test_it_should_create_a_date_property()
    {
        $document = DocumentBuilder::createBuilder('id')
            ->createDateProperty('date')->endProperty()
            ->build();
        $this->assertInstanceOf(
            Types\DateType::class,
            $document->getPropertyDefinition('date')->getType()
        );
    }

    public function test_it_should_build_a_record_with_all_types_of_properties()
    {
        $builder = DocumentBuilder::createBuilder('doc')
            ->createTextProperty('text')->endProperty()
            ->createBooleanProperty('bool')->endProperty()
            ->createDateProperty('date')->endProperty()
            ->startRecord('record')
            ->setValue('text', 'my text')
            ->setValue('bool', true)
            ->setValue('date', '2000-01-01');
        $record = $builder->getRecord();
        $document = $builder->endRecord()->build();

        $this->assertInstanceOf(DocumentRecord::class, $record);
        $this->assertInstanceOf(DocumentDesigner::class, $document);

        $this->assertFalse($document->isPublished());

        $this->assertInstanceOf(
            Types\StringType::class,
            $document->getPropertyDefinition('text')->getType()
        );
        $this->assertSame('my text', $record->getValue('text')->toString());

        $this->assertInstanceOf(
            Types\BooleanType::class,
            $document->getPropertyDefinition('bool')->getType()
        );
        $this->assertSame('true', $record->getValue('bool')->toString());

        $this->assertInstanceOf(
            Types\DateType::class,
            $document->getPropertyDefinition('date')->getType()
        );
        $this->assertSame('2000-01-01', $record->getValue('date')->toString());
    }
}
