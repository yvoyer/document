<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class Regex implements PropertyConstraint
{
    /**
     * @var string
     */
    private $pattern;

    public function __construct(string $pattern)
    {
        Assertion::notEmpty($pattern, 'Pattern "%s" is empty, but non empty value was expected.');
        @\preg_match($pattern, '');
        Assertion::same(\preg_last_error(), 0, \sprintf('Pattern "%s" is not a valid regex.', $pattern));
        $this->pattern = $pattern;
    }

    /**
     * @param PropertyName $name
     * @param mixed $value
     * @param ErrorList $errors
     */
    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        Assertion::string($value);
        if (! \preg_match($this->pattern, $value)) {
            $errors->addError(
                $name->toString(),
                'en',
                \sprintf(
                    'Value "%s" do not match pattern "%s".',
                    $value,
                    $this->pattern
                )
            );
        }
    }
}
