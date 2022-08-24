<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Builder;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class DocumentTypeBuilderTest extends TestCase
{
    public function test_it_should_build_a_document_with_a_text_property(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createText('name')->endProperty()
            ->getSchema();

        $this->assertSame(
            'string',
            $document->getPropertyMetadata('name')->toTypedString()
        );
    }

    public function test_it_should_create_a_boolean_property(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createBoolean('bool')->endProperty()
            ->getSchema();
        $this->assertSame(
            'boolean',
            $document->getPropertyMetadata('bool')->toTypedString()
        );
    }

    public function test_it_should_create_a_date_property(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createDate('date')->endProperty()
            ->getSchema();
        $this->assertSame(
            'date',
            $document->getPropertyMetadata('date')->toTypedString()
        );
    }

    public function test_it_should_create_a_number_property(): void
    {
        $document = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createNumber('number')->endProperty()
            ->getSchema();
        $this->assertSame(
            'number',
            $document->getPropertyMetadata('number')->toTypedString()
        );
    }

    public function test_it_should_create_a_custom_list_property(): void
    {
        $name = PropertyName::fromLocalizedString('name', 'en');
        $document = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createListOfOptions($name->toString(), OptionListValue::withElements(3))->endProperty()
            ->getSchema();
        $this->assertSame(
            'list',
            $document->getPropertyMetadata('name')->toTypedString()
        );
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_text_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createText('name')->required()->endProperty();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->startRecord(RecordId::random());
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_boolean_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createBoolean('name')->required()->endProperty();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->startRecord(
            RecordId::random(),
            [
                'name' => new EmptyValue(),
            ]
        );
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_date_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createDate('name')->required()->endProperty();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->startRecord(RecordId::random());
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_number_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createNumber('name')->required()->endProperty();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage('Validation error: [Property named "name" is required, but "empty()" given.]');
        $builder->startRecord(RecordId::random());
    }

    public function test_it_should_throw_exception_when_setting_empty_on_required_list_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createListOfOptions('name', OptionListValue::withElements(1))->required()->endProperty();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "name" requires at least 1 option(s), "empty()" given.]'
        );
        $builder->startRecord(RecordId::random());
    }

    public function test_it_should_throw_exception_when_setting_more_than_one_value_on_single_value_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('id')
            ->createListOfOptions('name', OptionListValue::withElements(3))->singleOption()->endProperty()
            ->startRecord(RecordId::random());

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "name" allows only 1 option, "list(1;2)" given.]'
        );
        $builder->setValue('name', ArrayOfInteger::withValues(1, 2));
    }

    public function test_it_should_build_a_record_with_text_property(): void
    {
        $record = DocumentTypeBuilder::startDocumentTypeFixture('doc')
            ->createText('optional')->endProperty()
            ->createText('regex')->matchesRegex('/\w+/')->endProperty()
            ->createText('required')->required()->endProperty()
            ->startRecord(
                RecordId::random(),
                [
                    'regex' => StringValue::fromString('Text'),
                    'required' => StringValue::fromString('My required value'),
                ]
            )
            ->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('Text', $record->getValue('regex')->toString());
        $this->assertSame('My required value', $record->getValue('required')->toString());
    }

    public function test_it_should_build_a_record_with_boolean_property(): void
    {
        $record = DocumentTypeBuilder::startDocumentTypeFixture('doc')
            ->createBoolean('optional')->endProperty()
            ->createBoolean('required')->required()->endProperty()
            ->createBoolean('default')->defaultValue(true)->endProperty()
            ->createBoolean('label')->labeled('Vrai', 'Faux')->endProperty()
            ->startRecord(
                RecordId::random(),
                [
                    'required' => BooleanValue::trueValue(),
                    'label' => BooleanValue::falseValue(),
                ]
            )
            ->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('true', $record->getValue('required')->toString());
        $this->assertSame('true', $record->getValue('default')->toString());
        $this->assertSame('false', $record->getValue('label')->toString());
    }

    public function test_it_should_build_a_record_with_date_property(): void
    {
        $builder = DocumentTypeBuilder::startDocumentTypeFixture('doc')
            ->createDate('optional')->endProperty()
            ->createDate('required')->required()->endProperty()
            ->createDate('before')->beforeDate('2000-01-01')->endProperty()
            ->createDate('after')->afterDate('2000-01-01')->endProperty()
            ->createDate('format')->outputAsFormat('Y')->endProperty()
            ->createDate('between')->betweenDate('2000-01-01', '2000-12-31')->endProperty()
            ->startRecord(
                RecordId::random(),
                [
                    'required' => DateValue::fromString('2010-01-02'),
                    'before' => DateValue::fromString('1999-12-31'),
                    'after' => DateValue::fromString('2000-01-02'),
                    'format' => DateValue::fromString('2000-01-02'),
                    'between' => DateValue::fromString('2000-07-01'),
                ]
            );
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
        $record = DocumentTypeBuilder::startDocumentTypeFixture('doc')
            ->createNumber('optional')->endProperty()
            ->createNumber('required')->required()->endProperty()
            ->createNumber('float')->asFloat()->endProperty()
            ->startRecord(
                RecordId::random(),
                [
                    'required' => IntegerValue::fromInt(1234),
                    'float' => FloatValue::fromFloat(12.34),
                ]
            )
            ->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame('1234', $record->getValue('required')->toString());
        $this->assertSame('12.34', $record->getValue('float')->toString());
    }

    public function test_it_should_build_a_record_with_custom_list_property(): void
    {
        $record = DocumentTypeBuilder::startDocumentTypeFixture('doc')
            ->createListOfOptions('optional', OptionListValue::withElements(3))->endProperty()
            ->createListOfOptions('required', OptionListValue::withElements(3))->required()->endProperty()
            ->createListOfOptions('single', OptionListValue::withElements(3))->singleOption()->endProperty()
            ->createListOfOptions('multi', OptionListValue::withElements(3))->endProperty()
            ->startRecord(
                RecordId::random(),
                [
                    'required' => ArrayOfInteger::withValues(1, 3),
                    'single' => ArrayOfInteger::withValues(2),
                    'multi' => ArrayOfInteger::withValues(1, 3),
                ]
            )->getRecord();

        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertSame(
            '[{"id":1,"value":"Option 1","label":"Label 1"},'
            . '{"id":3,"value":"Option 3","label":"Label 3"}]',
            $record->getValue('required')->toString()
        );
        $this->assertSame('options(Label 2)', $record->getValue('single')->toTypedString());
    }
}
