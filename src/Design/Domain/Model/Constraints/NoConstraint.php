<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class NoConstraint implements PropertyConstraint, DocumentConstraint
{
    public function validate(PropertyDefinition $definition, $value): void
    {
    }

    public function onPublish(DocumentDesigner $document): void
    {
    }
}
