<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

interface TransformerFactory
{
    /**
     * @param TransformerIdentifier $identifier
     * @return ValueTransformer
     * @throws NotFoundTransformer
     */
    public function createTransformer(TransformerIdentifier $identifier): ValueTransformer;

    public function transformerExists(TransformerIdentifier $identifier): bool;
}
