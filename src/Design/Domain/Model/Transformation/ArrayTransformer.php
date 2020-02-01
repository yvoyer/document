<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class ArrayTransformer implements ValueTransformer
{
    /**
     * @var ValueTransformer[]
     */
    private $transformers;

    /**
     * @param ValueTransformer[] ...$transformers
     */
    public function __construct(ValueTransformer ...$transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * @param mixed $rawValue
     *
     * @return mixed
     */
    public function transform($rawValue)
    {
        foreach ($this->transformers as $transformer) {
            $rawValue = $transformer->transform($rawValue);
        }

        return $rawValue;
    }
}
