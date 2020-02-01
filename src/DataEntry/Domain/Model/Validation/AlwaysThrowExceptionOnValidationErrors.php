<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

final class AlwaysThrowExceptionOnValidationErrors implements StrategyToHandleValidationErrors
{
    public function handleFailure(ErrorList $errors): void
    {
        throw new ValidationFailedForProperty($errors);
    }
}
