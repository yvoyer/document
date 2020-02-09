<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class DateFormat implements PropertyConstraint
{
    /**
     * @var string
     */
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, [$this->format]);
    }
}
