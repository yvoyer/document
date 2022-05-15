<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\DataEntry\Builder\RecordBuilder;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\DataEntry\Domain\Model\Validation\AlwaysThrowExceptionOnValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\Design\Domain\Model\Behavior\DocumentBehavior;
use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types;

final class DocumentBuilder
{
    private DocumentId $id;
    private DocumentAggregate $document;
    private StrategyToHandleValidationErrors $strategy;
    private const DEFAULT_LOCALE = 'en';

    private function __construct(
        DocumentId $id,
        DocumentName $type,
        DocumentOwner $owner,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->document = DocumentAggregate::draft($id, $type, $owner, $createdAt);
        $this->strategy = new AlwaysThrowExceptionOnValidationErrors();
    }

    public function createText(string $name): StringBuilder
    {
        $this->createProperty($name, new Types\StringType());

        return new StringBuilder(PropertyName::fromString($name, self::DEFAULT_LOCALE), $this->document, $this);
    }

    public function createBoolean(string $name): BooleanBuilder
    {
        $this->createProperty($name, new Types\BooleanType());

        return new BooleanBuilder(PropertyName::fromString($name, self::DEFAULT_LOCALE), $this->document, $this);
    }

    public function createDate(string $name): DateBuilder
    {
        $this->createProperty($name, new Types\DateType());

        return new DateBuilder(PropertyName::fromString($name, self::DEFAULT_LOCALE), $this->document, $this);
    }

    public function createNumber(string $name): NumberBuilder
    {
        $this->createProperty($name, new Types\NumberType());

        return new NumberBuilder(PropertyName::fromString($name, self::DEFAULT_LOCALE), $this->document, $this);
    }

    public function attachBehavior(string $name, DocumentBehavior $behavior): DocumentBuilder
    {
        $this->createProperty($name, new Types\BehaviorType($behavior));

        return $this;
    }

    public function createListOfOptions(string $name, OptionListValue $options): CustomListBuilder
    {
        $this->createProperty(
            $name,
            new Types\ListOfOptionsType('list', $options)
        );

        return new CustomListBuilder(
            PropertyName::fromString($name, self::DEFAULT_LOCALE),
            $this->document,
            $this
        );
    }

    /**
     * @param RecordId|null $recordId
     * @param RecordValue[] $recordValues
     * @return RecordBuilder
     */
    public function startRecord(RecordId $recordId = null, array $recordValues = []): RecordBuilder
    {
        if (!$recordId) {
            $recordId= RecordId::random();
        }

        return new RecordBuilder(
            RecordAggregate::withValues($recordId, $this->document->getSchema(), $recordValues),
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

    public function getDocument(): DocumentAggregate
    {
        return $this->document;
    }

    public function getSchema(): SchemaMetadata
    {
        return $this->document->getSchema();
    }

    public function createProperty(string $name, PropertyType $type): void
    {
        $this->document->addProperty(
            PropertyName::fromString($name, self::DEFAULT_LOCALE),
            $type,
            new DateTimeImmutable()
        );
    }

    public static function constraints(): ConstraintBuilder
    {
        return new ConstraintBuilder();
    }

    public static function parameters(): ParameterBuilder
    {
        return new ParameterBuilder();
    }

    public static function createDocument(string $id = null): self
    {
        if (null === $id) {
            $id = DocumentId::random()->toString();
        }

        return new self(
            DocumentId::fromString($id),
            DocumentName::defaultName(),
            new NullOwner(),
            new DateTimeImmutable()
        );
    }
}
