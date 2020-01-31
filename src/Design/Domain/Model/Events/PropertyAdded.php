<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\DomainEvent\DomainEvent;

final class PropertyAdded implements DomainEvent
{
    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var PropertyType
     */
    private $type;

    /**
     * @var PropertyConstraint
     */
    private $constraint;

    public function __construct(
        PropertyName $name,
        PropertyType $type,
        PropertyConstraint $constraint
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->constraint = $constraint;
    }

    public function name(): PropertyName
    {
        return $this->name;
    }

    public function type(): PropertyType
    {
        return $this->type;
    }

    public function constraint(): PropertyConstraint
    {
        return $this->constraint;
    }
}
