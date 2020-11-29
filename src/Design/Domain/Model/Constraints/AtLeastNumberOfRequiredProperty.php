<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use RuntimeException;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function sprintf;

final class AtLeastNumberOfRequiredProperty implements DocumentVisitor, DocumentConstraint
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $count = 0;

    public function __construct(int $number)
    {
        Assertion::greaterThan($number, 0, 'Number of required field "%s" is not greater than "%s".');
        $this->number = $number;
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->count ++;

        return false;
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function onRegistered(DocumentDesigner $document): void
    {
        $document->acceptDocumentVisitor($this);
        if ($this->count < $this->number) {
            throw new MissingRequiredProperty(
                sprintf(
                    'Document must have at least "%s" required property, got "%s".',
                    $this->number,
                    $this->count
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
