<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

final class ValidationFailedForProperty extends \LogicException
{
    /**
     * @var ErrorList
     */
    private $errors;

    public function __construct(ErrorList $errors)
    {
        $this->errors = $errors;
        parent::__construct(\sprintf('Validation error: %s', $errors->toJson()));
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }
}
