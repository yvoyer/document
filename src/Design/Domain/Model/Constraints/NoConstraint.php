<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class NoConstraint implements PropertyConstraint, DocumentConstraint
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name = 'no-constraint')
    {
        $this->name = $name;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
    }

    public function onPublish(DocumentDesigner $document): void
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, ['name' => $this->name]);
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        return new self($data->getArgument('name'));
    }
}
