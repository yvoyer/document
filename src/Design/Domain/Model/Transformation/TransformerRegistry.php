<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use Star\Component\Document\Design\Domain\Exception\DuplicateTransformer;
use Star\Component\Document\Design\Domain\Exception\NotFoundTransformer;

final class TransformerRegistry implements TransformerFactory
{
    /**
     * @var ValueTransformer[]
     */
    private $transformers = [];

    /**
     * @param string $transformer
     *
     * @return ValueTransformer
     * @throws NotFoundTransformer
     */
    public function createTransformer(string $transformer): ValueTransformer
    {
        if (! $this->isRegistered($transformer)) {
            throw new NotFoundTransformer(
                sprintf('Transformer "%s" was not found.', $transformer)
            );
        }

        return $this->transformers[$transformer];
    }

    /**
     * @param string $id
     * @param ValueTransformer $transformer
     * @throws DuplicateTransformer
     */
    public function registerTransformer(string $id, ValueTransformer $transformer)
    {
        if ($this->isRegistered($id)) {
            throw new DuplicateTransformer(
                sprintf('Transformer "%s" is already registered.', $id)
            );
        }

        $this->transformers[$id] = $transformer;
    }

    /**
     * @param string $transformer
     *
     * @return bool
     */
    private function isRegistered(string $transformer): bool
    {
        return array_key_exists($transformer, $this->transformers);
    }
}
