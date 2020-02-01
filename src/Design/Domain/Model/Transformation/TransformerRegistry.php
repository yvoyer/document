<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class TransformerRegistry implements TransformerFactory
{
    /**
     * @var ValueTransformer[]
     */
    private $transformers = [];

    public function createTransformer(TransformerIdentifier $id): ValueTransformer
    {
        if (! $this->transformerExists($id)) {
            throw new NotFoundTransformer($id);
        }

        return $this->transformers[$id->toString()];
    }

    public function registerTransformer(string $id, ValueTransformer $transformer): void
    {
        if ($this->transformerExists(TransformerIdentifier::fromString($id))) {
            throw new DuplicateTransformer(
                sprintf('Transformer "%s" is already registered.', $id)
            );
        }

        $this->transformers[$id] = $transformer;
    }

    public function transformerExists(TransformerIdentifier $identifier): bool
    {
        return array_key_exists($identifier->toString(), $this->transformers);
    }
}
