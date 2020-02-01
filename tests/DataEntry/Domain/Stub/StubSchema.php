<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Stub;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\DataEntry\Domain\Model\DocumentSchema;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;

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

    /**
     * @return DocumentId
     */
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
        return new class($rawValue) implements RecordValue
        {
            /**
             * @var mixed
             */
            private $value;

            /**
             * @param mixed $value
             */
            public function __construct($value)
            {
                $this->value = $value;
            }

            public function toString(): string
            {
                return strval($this->value);
            }
        };
    }

    /**
     * @return StubSchema
     */
    public static function allRequired(): self
    {
        return new self(true);
    }

    /**
     * @return StubSchema
     */
    public static function allOptional(): self
    {
        return new self();
    }

    /**
     * @return StubSchema
     */
    public static function singleValue(): self
    {
        return new self(false, true);
    }
}
