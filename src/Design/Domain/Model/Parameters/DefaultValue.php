<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class DefaultValue implements PropertyParameter
{
    /**
     * @var RecordValue
     */
    private $value;

    public function __construct(RecordValue $value)
    {
        $this->value = $value;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function toParameterData(): ParameterData
    {
        return ParameterData::fromParameter($this, ['value' => $this->value->toString()]);
    }

    public function getName(): string
    {
        return 'default-value';
    }

    public function onCreateDefaultValue(RecordValue $value): RecordValue
    {
        return $this->value;
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self(RawValue::fromMixed($data->getArgument('value'))->toRecordValue());
    }
}
