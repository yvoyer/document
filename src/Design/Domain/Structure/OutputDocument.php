<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentProperty;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class OutputDocument implements DocumentVisitor
{
    public function visitDocument(DocumentId $id): void
    {
        $this->writeLine(\sprintf('Document: "%s"', $id->toString()));
    }

    public function visitProperty(PropertyDefinition $definition): void
    {
        $this->writeLine(
            \sprintf(
                'Property: %s (%s): %s',
                $definition->getName()->toString(),
                $definition->getType()->toString(),
                \implode(', ', $definition->getConstraints())
            )
        );
    }

    /**
     * @param DocumentProperty[] $properties
     */
    public function visitEnded(array $properties): void
    {
    }

    protected function writeLine(string $text): void
    {
        echo($text . "\n");
    }
}
