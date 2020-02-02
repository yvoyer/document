<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class NeverFindTransformer implements TransformerFactory
{
    public function createTransformer(TransformerIdentifier $identifier): ValueTransformer
    {
        throw new NotFoundTransformer($identifier);
    }

    public function transformerExists(TransformerIdentifier $identifier): bool
    {
        return false;
    }
}
