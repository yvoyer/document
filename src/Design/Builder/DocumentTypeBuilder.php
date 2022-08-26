<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\DataEntry\Builder\DocumentBuilder;
use Star\Component\Document\DataEntry\Domain\Model\DocumentAggregate;
use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\Design\Domain\Model\Behavior\DocumentBehavior;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentTypeBuilder
{
    private DocumentTypeAggregate $document;
    private StrategyToHandleValidationErrors $strategy;

    private function __construct(
        DocumentTypeId $id,
        DocumentTypeName $type,
        DocumentOwner $owner,
        AuditDateTime $createdAt
    ) {
        $this->document = DocumentTypeAggregate::draft(
            $id,
            $type,
            $owner,
            $createdAt
        );
        $this->strategy = new AlwaysThrowExceptionOnValidationErrors();
    }

    public function createText(string $code): StringBuilder
    {
        return new StringBuilder(
            $this->createProperty($code, new Types\StringType()),
            $this->document,
            $this
        );
    }

    public function createBoolean(string $code): BooleanBuilder
    {
        return new BooleanBuilder(
            $this->createProperty($code, new Types\BooleanType()),
            $this->document,
            $this
        );
    }

    public function createDate(string $code): DateBuilder
    {
        return new DateBuilder(
            $this->createProperty($code, new Types\DateType()),
            $this->document,
            $this
        );
    }

    public function createNumber(string $code): NumberBuilder
    {
        return new NumberBuilder(
            $this->createProperty($code, new Types\NumberType()),
            $this->document,
            $this
        );
    }

    public function attachBehavior(string $code, DocumentBehavior $behavior): DocumentTypeBuilder
    {
        $this->createProperty($code, new Types\BehaviorType($behavior));

        return $this;
    }

    public function createListOfOptions(string $code, OptionListValue $options): CustomListBuilder
    {
        return new CustomListBuilder(
            $this->createProperty($code, new Types\ListOfOptionsType('list', $options)),
            $this->document,
            $this
        );
    }

    /**
     * @param DocumentId|null $recordId
     * @param RecordValue[] $recordValues
     * @return DocumentBuilder
     */
    public function startDocument(DocumentId $recordId = null, array $recordValues = []): DocumentBuilder
    {
        if (!$recordId) {
            $recordId= DocumentId::random();
        }

        return new DocumentBuilder(
            DocumentAggregate::withValues($recordId, $this->document->getSchema(), $recordValues),
            $this
        );
    }

    public function withConstraint(string $name, DocumentConstraint $constraint): self
    {
        $this->document->addDocumentConstraint($name, $constraint);

        return $this;
    }

    public function getErrorStrategyHandler(): StrategyToHandleValidationErrors
    {
        return $this->strategy;
    }

    public function getDocumentType(): DocumentTypeAggregate
    {
        return $this->document;
    }

    public function getSchema(): SchemaMetadata
    {
        return $this->document->getSchema();
    }

    private function createProperty(string $code, PropertyType $type): PropertyCode
    {
        $this->document->addProperty(
            $code = PropertyCode::fromString($code),
            PropertyName::fromLocalizedString($code->toString(), $this->document->getDefaultLocale()),
            $type,
            AuditDateTime::fromNow()
        );

        return $code;
    }

    public static function constraints(): ConstraintBuilder
    {
        return new ConstraintBuilder();
    }

    public static function parameters(): ParameterBuilder
    {
        return new ParameterBuilder();
    }

    public static function startDocumentTypeFixture(string $id = null): self {
        if (null === $id) {
            $id = DocumentTypeId::random()->toString();
        }

        return self::startDocumentType(
            DocumentTypeId::fromString($id),
            DocumentTypeName::random(),
            new NullOwner(),
            AuditDateTime::fromNow()
        );
    }

    public static function startDocumentType(
        DocumentTypeId $id,
        DocumentTypeName $name,
        DocumentOwner $owner,
        AuditDateTime $createdAt
    ): self {
        return new self(
            $id,
            $name,
            $owner,
            $createdAt
        );
    }
}
