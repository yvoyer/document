<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentBuilderTest extends TestCase
{
    public function test_it_should_build_a_document_with_a_text_property()
    {
        $document = DocumentBuilder::createDocument('id')
            ->createText('name')->endProperty()
            ->getDocument();

        $this->assertInstanceOf(ReadOnlyDocument::class, $document);
        $this->assertInstanceOf(DocumentDesigner::class, $document);

        $this->assertFalse($document->isPublished());
        $this->assertInstanceOf(
            Types\StringType::class,
            $document->getPropertyDefinition(PropertyName::fromString('name'))->getType()
        );
    }

    public function test_it_should_create_a_boolean_property()
    {
        $document = DocumentBuilder::createDocument('id')
            ->createBoolean('bool')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\BooleanType::class,
            $document->getPropertyDefinition(PropertyName::fromString('bool'))->getType()
        );
    }

    public function test_it_should_create_a_date_property()
    {
        $document = DocumentBuilder::createDocument('id')
            ->createDate('date')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\DateType::class,
            $document->getPropertyDefinition(PropertyName::fromString('date'))->getType()
        );
    }

    public function test_it_should_create_a_number_property()
    {
        $document = DocumentBuilder::createDocument('id')
            ->createNumber('number')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\NumberType::class,
            $document->getPropertyDefinition(PropertyName::fromString('number'))->getType()
        );
    }

    public function test_it_should_create_a_custom_list_property()
    {
        $name = $name = PropertyName::fromString('name');
        $document = DocumentBuilder::createDocument('id')
            ->createCustomList($name->toString(), 'option')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\CustomListType::class,
            $document->getPropertyDefinition($name)->getType()
        );
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_text_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createText('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Property named \"name\" is required, but empty value given.');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_boolean_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createBoolean('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Property named \"name\" is required, but empty value given.');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_date_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createDate('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Property named \"name\" is required, but empty value given.');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_number_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createNumber('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Property named \"name\" is required, but empty value given.');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_list_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createCustomList('name', 'option')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_more_than_one_value_on_single_value_property()
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createCustomList('name', 'option 1', 'option 2', 'option 3')->singleOption()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $builder->setValue('name', [0, "2"]);
    }

    public function test_it_should_create_a_property(): void
    {
        var_dump(
        DocumentBuilder::createDocument()
            ->createBoolean('name')->endProperty()
            ->startRecord()
            ->setValue('name', true)
            ->getRecord()->uncommitedEvents()
    );
    }

    public function test_it_should_build_a_record_with_all_types_of_properties()
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createText('text')->endProperty()
            ->createBoolean('bool')->endProperty()
            ->createDate('date')->endProperty()
            ->createNumber('int')->endProperty()
            ->createNumber('float')->endProperty()
            ->createCustomList('custom-list-single', 'option 1', 'option 2', 'option 3')->singleOption()->endProperty()
            ->createCustomList('custom-list-multi', 'option 4', 'option 5', 'option 6')->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('text', 'my text')
            ->setValue('bool', true)
            ->setValue('date', '2000-01-01')
            ->setValue('int', 123)
            ->setValue('float', 12.34)
            ->setValue('custom-list-single', ['2'])
            ->setValue('custom-list-multi', [1, '3'])
        ;
        $record = $builder->getRecord();
        $document = $builder->endRecord()->getDocument();

        $this->assertInstanceOf(DocumentRecord::class, $record);
        $this->assertInstanceOf(DocumentDesigner::class, $document);

        $this->assertFalse($document->isPublished());

        $this->assertInstanceOf(
            Types\StringType::class,
            $document->getPropertyDefinition(PropertyName::fromString('text'))->getType()
        );
        $this->assertSame('my text', $record->getValue('text')->toString());

        $this->assertInstanceOf(
            Types\BooleanType::class,
            $document->getPropertyDefinition(PropertyName::fromString('bool'))->getType()
        );
        $this->assertSame('true', $record->getValue('bool')->toString());

        $this->assertInstanceOf(
            Types\DateType::class,
            $document->getPropertyDefinition(PropertyName::fromString('date'))->getType()
        );
        $this->assertSame('2000-01-01', $record->getValue('date')->toString());

        $this->assertInstanceOf(
            Types\NumberType::class,
            $document->getPropertyDefinition(PropertyName::fromString('int'))->getType()
        );
        $this->assertSame('123', $record->getValue('int')->toString());

        $this->assertInstanceOf(
            Types\NumberType::class,
            $document->getPropertyDefinition(PropertyName::fromString('float'))->getType()
        );
        $this->assertSame('12.34', $record->getValue('float')->toString());

        $this->assertInstanceOf(
            Types\CustomListType::class,
            $document->getPropertyDefinition(PropertyName::fromString('custom-list-single'))->getType()
        );
        $this->assertSame('option 2', $record->getValue('custom-list-single')->toString());

        $this->assertInstanceOf(
            Types\CustomListType::class,
            $document->getPropertyDefinition(PropertyName::fromString('custom-list-multi'))->getType()
        );
        $this->assertSame('option 4;option 6', $record->getValue('custom-list-multi')->toString());
    }
}
