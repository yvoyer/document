<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class NotFoundTransformer extends \Exception
{
    public function __construct(TransformerIdentifier $identifier)
    {
        parent::__construct(
            \sprintf('Transformer with id "%s" could not be found.', $identifier->toString())
        );
    }
}
