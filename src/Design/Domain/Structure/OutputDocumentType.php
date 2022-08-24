<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class OutputDocumentType implements DocumentTypeVisitor
{
    public function visitDocumentType(DocumentTypeId $id): void
    {
        $this->writeLine(\sprintf('Document: "%s"', $id->toString()));
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool
    {
        $this->writeLine(
            \sprintf(
                'Property: %s (%s)',
                $code->toString(),
                $type->toHumanReadableString()
            )
        );

        return false;
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
        $this->writeLine('  Constraints:');
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
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

    public function enterPropertyParameters(PropertyCode $code): void
    {
        $this->writeLine('  Parameters:');
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->writeLine(
            \sprintf(
                '    - %s(%s)',
                $parameterName,
                \json_encode($parameter->toParameterData()->toArray()['arguments'])
            )
        );
    }

    protected function writeLine(string $text): void
    {
        echo($text . "\n");
    }
}
