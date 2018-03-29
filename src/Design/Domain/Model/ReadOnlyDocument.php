<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface ReadOnlyDocument
{
    /**
     * @return bool
     */
    public function isPublished(): bool;
}
