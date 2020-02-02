<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

interface StrategyToHandleValidationErrors
{
    public function handleFailure(ErrorList $errors): void;
}
