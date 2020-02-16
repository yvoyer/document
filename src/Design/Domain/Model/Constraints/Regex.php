<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function preg_last_error;
use function preg_match;
use function sprintf;

final class Regex implements PropertyConstraint
{
    /**
     * @var string
     */
    private $pattern;

    public function __construct(string $pattern)
    {
        Assertion::notEmpty($pattern, 'Pattern "%s" is empty, but non empty value was expected.');
        @preg_match($pattern, '');
        Assertion::same(preg_last_error(), 0, sprintf('Pattern "%s" is not a valid regex.', $pattern));
        $this->pattern = $pattern;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if (! preg_match($this->pattern, $value->toString())) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'Value "%s" do not match pattern "%s".',
                    $value->toTypedString(),
                    $this->pattern
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class, ['pattern' => $this->pattern]);
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new static($data->getArgument('pattern'));
    }
}
