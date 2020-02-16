<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\ReadOnlyDocument;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentBuilderTest extends TestCase
{
    public function test_it_should_build_a_document_with_a_text_property(): void
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

    public function test_it_should_create_a_boolean_property(): void
    {
        $document = DocumentBuilder::createDocument('id')
            ->createBoolean('bool')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\BooleanType::class,
            $document->getPropertyDefinition(PropertyName::fromString('bool'))->getType()
        );
    }

    public function test_it_should_create_a_date_property(): void
    {
        $document = DocumentBuilder::createDocument('id')
            ->createDate('date')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\DateType::class,
            $document->getPropertyDefinition(PropertyName::fromString('date'))->getType()
        );
    }

    public function test_it_should_create_a_number_property(): void
    {
        $document = DocumentBuilder::createDocument('id')
            ->createNumber('number')->endProperty()
            ->getDocument();
        $this->assertInstanceOf(
            Types\NumberType::class,
            $document->getPropertyDefinition(PropertyName::fromString('number'))->getType()
        );
    }

    public function test_it_should_create_a_custom_list_property(): void
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

    public function test_it_should_throw_exception_when_setting_empty_on_required_text_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createText('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_boolean_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createBoolean('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_date_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createDate('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_number_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createNumber('name')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_list_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createCustomList('name', 'option')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "name" allows only "1" option(s), "empty()" given.]'
        );
        $builder->setValue('name', '');
    }

    public function test_it_should_throw_exception_when_setting_more_than_one_value_on_single_value_property(): void
    {
        $builder = DocumentBuilder::createDocument('id')
            ->createCustomList('name', 'option 1', 'option 2', 'option 3')->required()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "name" allows only "1" option(s), "list([option 1;option 2])" given.]'
        );
        $builder->setValue('name', [1, "2"]);
    }

    public function test_it_should_build_a_record_with_text_property(): void
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createText('optional')->endProperty()
            ->createText('regex')->matchesRegex('/\w+/')->endProperty()
            ->createText('required')->required()->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('regex', 'Text')
            ->setValue('required', 'My required value')
        ;
        $record = $builder->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('Text', $record->getValue('regex')->toString());
        $this->assertSame('My required value', $record->getValue('required')->toString());
    }

    public function test_it_should_build_a_record_with_boolean_property(): void
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createBoolean('optional')->endProperty()
            ->createBoolean('required')->required()->endProperty()
            ->createBoolean('default')->defaultValue(true)->endProperty()
            ->createBoolean('label')->labeled('Vrai', 'Faux')->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('required', true)
            ->setValue('label', false)
        ;
        $record = $builder->getRecord();

        $this->assertSame('false', $record->getValue('optional')->toString());
        $this->assertSame('true', $record->getValue('required')->toString());
        $this->assertSame('true', $record->getValue('default')->toString());
        $this->assertSame('false', $record->getValue('label')->toString());
    }

    public function test_it_should_build_a_record_with_date_property(): void
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createDate('optional')->endProperty()
            ->createDate('required')->required()->endProperty()
            ->createDate('before')->beforeDate('2000-01-01')->endProperty()
            ->createDate('after')->afterDate('2000-01-01')->endProperty()
            ->createDate('format')->requireFormat('Y')->endProperty()
            ->createDate('between')->betweenDate('2000-01-01', '2000-12-31')->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('required', '2010-01-02')
            ->setValue('before', '1999-12-31')
            ->setValue('after', '2000-01-02')
            ->setValue('format', '2000')
            ->setValue('between', '2000-07-01')
        ;
        $record = $builder->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('2010-01-02', $record->getValue('required')->toString());
        $this->assertSame('1999-12-31', $record->getValue('before')->toString());
        $this->assertSame('2000-01-02', $record->getValue('after')->toString());
        $this->assertSame('2000', $record->getValue('format')->toString());
        $this->assertSame('2000-07-01', $record->getValue('between')->toString());
    }

    public function test_it_should_build_a_record_with_all_number_property(): void
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createNumber('optional')->endProperty()
            ->createNumber('required')->required()->endProperty()
            ->createNumber('float')->asFloat()->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('required', 1234)
            ->setValue('float', 12.34)
        ;
        $record = $builder->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('1234', $record->getValue('required')->toString());
        $this->assertSame('12.34', $record->getValue('float')->toString());
    }

    public function test_it_should_build_a_record_with_custom_list_property(): void
    {
        $builder = DocumentBuilder::createDocument('doc')
            ->createCustomList('optional', 'option 1')->endProperty()
            ->createCustomList('required-single', 'option 2', 'option 3')
            ->required()
            ->endProperty()
            ->createCustomList('required-multi', 'option 4', 'option 5', 'option 6')
            ->allowMultiOption()
            ->endProperty()
            ->startRecord(RecordId::random())
            ->setValue('required-single', ['2'])
            ->setValue('required-multi', [1, '3'])
        ;
        $record = $builder->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('2', $record->getValue('required-single')->toString());
        $this->assertSame('1;3', $record->getValue('required-multi')->toString());
    }
}
