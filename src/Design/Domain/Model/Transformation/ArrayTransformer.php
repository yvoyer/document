<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class ArrayTransformer implements ValueTransformer
{
    /**
     * @var ValueTransformer[]
     */
    private $transformers;

    public function __construct(ValueTransformer ...$transformers)
    {
        $this->transformers = $transformers;
    }

    public function transform($rawValue): RecordValue
    {
        foreach ($this->transformers as $transformer) {
            $rawValue = $transformer->transform($rawValue);
        }

        return $rawValue;
    }
}
