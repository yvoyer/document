<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;

interface PropertyParameter extends CanBeValidated
{
    /**
     * Allow execution of a transformation prior to the setting of the value
     *
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toWriteFormat(RecordValue $value): RecordValue;

    /**
     * Allow execution of a transformation prior to the return of the value
     *
     * @param RecordValue $value
     * @return RecordValue
     */
    public function toReadFormat(RecordValue $value): RecordValue;

    public function toParameterData(): ParameterData;

    public static function fromParameterData(ParameterData $data): PropertyParameter;
}
