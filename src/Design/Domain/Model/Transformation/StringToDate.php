<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class StringToDate implements ValueTransformer
{
    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

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
