<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class DoNotTransform implements ValueTransformer
{
    public function transform($rawValue): RecordValue
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
