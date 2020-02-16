<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters\Stubs;

use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ParameterAlwaysInError implements PropertyParameter
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        $errors->addError($propertyName, 'en', $this->message);
    }

    public function toParameterData(): ParameterData
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
