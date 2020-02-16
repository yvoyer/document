<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class AllowMultipleOptions implements PropertyParameter
{
    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
    }

    public function getName(): string
    {
        return 'allow-multiple';
    }

    public function onCreateDefaultValue(RecordValue $value): RecordValue
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
