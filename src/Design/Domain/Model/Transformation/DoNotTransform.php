<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class DoNotTransform implements ValueTransformer
{
    /**
     * @param mixed $rawValue
     *
     * @return mixed
     */
    public function transform($rawValue)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
