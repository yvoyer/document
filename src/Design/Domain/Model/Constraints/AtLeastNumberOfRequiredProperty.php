<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

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

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->count ++;

        return false;
    }

    public function enterConstraints(PropertyName $propertyName): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
    }

    public function enterParameters(PropertyName $propertyName): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function visitParameter(PropertyName $propertyName, PropertyParameter $parameter): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function onPublish(DocumentDesigner $document): void
    {
        $document->acceptDocumentVisitor($this);
        if ($this->count < $this->number) {
            throw new MissingRequiredProperty(
                \sprintf(
                    'Document must have at least "%s" required property, got "%s".',
                    $this->number,
                    $this->count
                )
            );
        }
    }
}
