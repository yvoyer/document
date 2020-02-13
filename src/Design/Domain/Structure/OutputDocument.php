<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class OutputDocument implements DocumentVisitor
{
    public function visitDocument(DocumentId $id): void
    {
        $this->writeLine(\sprintf('Document: "%s"', $id->toString()));
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->writeLine(
            \sprintf(
                'Property: %s (%s)',
                $name->toString(),
                $type->toString()
            )
        );
        $this->writeLine('  Constraints:');

        return false;
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->writeLine(
            \sprintf(
                '    - %s(%s)',
                $constraintName,
                \json_encode($constraint->toData()->toArray()['arguments'])
            )
        );
    }

    protected function writeLine(string $text): void
    {
        echo($text . "\n");
    }
}
