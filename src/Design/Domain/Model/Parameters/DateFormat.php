<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateParser;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\Types\DateType;
use function date;
use function sprintf;
use function strtotime;

final class DateFormat implements PropertyParameter
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

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        if ($value->isEmpty()) {
            return $value;
        }

        return StringValue::fromString(date($this->format, (int) strtotime($value->toString())));
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if (! $value->isEmpty() && ! $value instanceof DateValue) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'The date format expected a record value of type "%s", "%s" given.',
                    (new DateType())->toHumanReadableString(),
                    $value->toTypedString()
                )
            );
        }
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter($this, ['format' => $this->format]);
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self($data->getArgument('format'));
    }
}
