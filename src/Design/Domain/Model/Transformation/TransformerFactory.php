<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\Design\Domain\Exception\NotFoundTransformer;

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
