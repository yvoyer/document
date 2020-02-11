<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\Values\DateParser;

final class DateFormat implements PropertyConstraint
{
    /**
     * @var string
     */
    private $format;

    public function __construct(string $format)
    {
        DateParser::assertValidFormat($format);
        $this->format = $format;
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty()) {
            return;
        }

        $date = DateParser::fromFormat($this->format, $value->toString());
        $messageFormat = 'The date value "%s" is not in format "%s", provided date resulted in "%s".';
        if ($date->toString() !== $value->toString()) {
            $errors->addError(
                $name,
                'en',
                \sprintf($messageFormat, $value->toTypedString(), $this->format, $date->toString())
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, ['format' => $this->format]);
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        return new self($data->getArgument('format'));
    }
}
