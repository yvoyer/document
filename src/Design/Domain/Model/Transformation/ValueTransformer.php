<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

interface ValueTransformer
{
    /**
     * @param mixed $rawValue
     *
     * @return RecordValue
     */
    public function transform($rawValue): RecordValue;
}
