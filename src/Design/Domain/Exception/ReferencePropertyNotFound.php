<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

use Star\Component\Document\Design\Domain\Model\PropertyName;

final class ReferencePropertyNotFound extends \RuntimeException
{
    /**
     * @param PropertyName $name
     */
    public function __construct(PropertyName $name)
    {
        parent::__construct(
            sprintf('The property with name "%s" could not be found.', $name->toString())
        );
    }
}
