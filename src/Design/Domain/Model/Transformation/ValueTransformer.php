<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

interface ValueTransformer
{
    /**
     * @param mixed $rawValue
     *
     * @return mixed
     */
    public function transform($rawValue);
}
