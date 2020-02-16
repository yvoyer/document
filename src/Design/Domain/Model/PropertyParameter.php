<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;

interface PropertyParameter extends CanBeValidated
{
    public function toParameterData(): ParameterData;

    public function getName(): string;

    public function onCreateDefaultValue(RecordValue $value): RecordValue;

    public static function fromParameterData(ParameterData $data): PropertyParameter;
}
