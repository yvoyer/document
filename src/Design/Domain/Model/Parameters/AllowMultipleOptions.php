<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

final class AllowMultipleOptions implements PropertyParameter
{
    private function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if (\count($value) > 1) {// todo change to RequireOptionCount
            $errors->addError(
                $name,
                'en',
                \sprintf(
                    'Property named "%s" allows only one option, "%s" given.',
                    $name,
                    $value->toTypedString()
                )
            );
        }
    }

    public function getName(): string
    {
        return 'allow-multiple';
    }

    public function onAdd(DocumentSchema $schema): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public function toParameterData(): ParameterData
    {
        return new ParameterData($this->getName(), self::class);
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self();
    }
}
