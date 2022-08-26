<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Design\Domain\Model\PropertyCode;

final class ReferencePropertyNotFound extends \RuntimeException
{
    public function __construct(PropertyCode $code)
    {
        parent::__construct(
            sprintf('The property with code "%s" could not be found.', $code->toString())
        );
    }
}
