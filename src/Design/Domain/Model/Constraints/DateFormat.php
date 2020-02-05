<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class DateFormat implements PropertyConstraint
{
    /**
     * @var string
     */
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    /**
     * @param PropertyName $name
     * @param mixed $value
     * @param ErrorList $errors
     */
    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        var_dump($value);
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
