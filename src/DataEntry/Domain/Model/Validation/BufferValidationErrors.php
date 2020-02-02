<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

final class BufferValidationErrors implements StrategyToHandleValidationErrors
{
    /**
     * @var ErrorList
     */
    private $errors;

    public function __construct()
    {
        $this->errors = new ErrorList();
    }

    public function handleFailure(ErrorList $errors): void
    {
        $this->errors = $errors;
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
