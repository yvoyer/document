<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\DocumentProperty;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class AtLeastNumberOfRequiredProperty implements DocumentVisitor, DocumentConstraint
{
    /**
     * @var int
     */
    private $number;

    public function __construct(int $number)
    {
        Assertion::greaterThan($number, 0, 'Number of required field "%s" is not greater than "%s".');
        $this->number = $number;
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitProperty(PropertyDefinition $definition): void
    {
    }

    /**
     * @param DocumentProperty[] $properties
     */
    public function visitEnded(array $properties): void
    {
        if (\count($properties) < $this->number) {
            throw new MissingRequiredProperty(
                \sprintf(
                    'Document must have at least "%s" required property, got "%s".',
                    $this->number,
                    \count($properties)
                )
            );
        }
    }

    public function onPublish(DocumentDesigner $document): void
    {
        $document->acceptDocumentVisitor($this);
    }
}
