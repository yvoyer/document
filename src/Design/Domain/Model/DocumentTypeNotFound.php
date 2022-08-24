<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use RuntimeException;
use function sprintf;

final class DocumentTypeNotFound extends RuntimeException
{
    public function __construct(string $id)
    {
        parent::__construct(
            sprintf('Document with id "%s" could not be found.', $id)
        );
    }
}
