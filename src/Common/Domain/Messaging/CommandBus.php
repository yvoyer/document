<?php declare(strict_types=1);

namespace Star\Component\Document\Common\Domain\Messaging;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handleCommand(Command $command): void;
}
