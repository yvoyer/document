<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class StringToDateTime implements ValueTransformer
{
    /**
     * @param mixed $rawValue
     *
     * @return string|\DateTimeInterface
     */
    public function transform($rawValue)
    {
        if (empty($rawValue)) {
            return '';
        }

        return new \DateTimeImmutable($rawValue);
    }
}
