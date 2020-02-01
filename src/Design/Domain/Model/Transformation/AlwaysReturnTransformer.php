<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class AlwaysReturnTransformer implements TransformerFactory
{
    /**
     * @var ValueTransformer
     */
    private $transformer;

    public function __construct(ValueTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createTransformer(TransformerIdentifier $identifier): ValueTransformer
    {
        return $this->transformer;
    }

    public function transformerExists(TransformerIdentifier $identifier): bool
    {
        return true;
    }
}
