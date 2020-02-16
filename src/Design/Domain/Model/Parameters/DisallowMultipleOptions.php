<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use function sprintf;

final class DisallowMultipleOptions implements PropertyParameter
{
    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if (! $value->isList()) {
            return;
        }

        if (count($value) > 1) {
            $errors->addError(
                $name,
                'en',
                sprintf(
                    'Property named "%s" allows only 1 option, "%s" given.',
                    $name,
                    $value->toTypedString()
                )
            );
        }
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter($this);
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self();
    }
}
