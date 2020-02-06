<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Stub;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class StubSchema implements DocumentSchema
{
    /**
     * @var bool
     */
    private $required;

    /**
     * @var bool
     */
    private $multiple;

    private function __construct(bool $required = false, bool $multiple = true)
    {
        $this->required = $required;
        $this->multiple = $multiple;
    }

    public function getIdentity(): DocumentId
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @param StrategyToHandleValidationErrors $strategy
     *
     * @return RecordValue
     */
    public function createValue(
        string $propertyName,
        $rawValue,
        StrategyToHandleValidationErrors $strategy
    ): RecordValue {
        return StringValue::fromString($rawValue);
    }

    public static function allRequired(): self
    {
        return new self(true);
    }

    public static function allOptional(): self
    {
        return new self();
    }

    public static function singleValue(): self
    {
        return new self(false, true);
    }
}
