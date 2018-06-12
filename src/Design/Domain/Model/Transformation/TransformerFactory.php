<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

interface TransformerFactory
{
    /**
     * @param string $transformer
     *
     * @return ValueTransformer
     */
    public function createTransformer(string $transformer): ValueTransformer;
}
