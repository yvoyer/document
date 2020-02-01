<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Exception;

use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class NotFoundTransformer extends \Exception implements DesignException
{
    public function __construct(TransformerIdentifier $identifier)
    {
        parent::__construct(
            \sprintf('Transformer with id "%s" could not be found.', $identifier->toString()),
            self::NOT_FOUND_TRANSFORMER
        );
    }
}
