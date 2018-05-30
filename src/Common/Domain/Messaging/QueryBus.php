<?php declare(strict_types=1);

namespace Star\Component\Document\Common\Domain\Messaging;

use React\Promise\PromiseInterface;

interface QueryBus
{
    /**
     * @param Query $query
     *
     * @return PromiseInterface
     */
    public function handleQuery(Query $query): PromiseInterface;
}
