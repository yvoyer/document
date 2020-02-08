<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class OutputDocument implements DocumentVisitor
{
    public function visitDocument(DocumentId $id): void
    {
        $this->writeLine(\sprintf('Document: "%s"', $id->toString()));
    }

    public function visitProperty(PropertyName $name, PropertyType $type): void
    {
        $this->writeLine(
            \sprintf(
                'Property: %s (%s)',
                $name->toString(),
                $type->toData()
            )
        );
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function visitValueTransformer(
        PropertyName $propertyName,
        string $constraintName,
        TransformerIdentifier $identifier
    ): void {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    protected function writeLine(string $text): void
    {
        echo($text . "\n");
    }
}
