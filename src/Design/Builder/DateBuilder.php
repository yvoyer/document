<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use Star\Component\Document\Design\Domain\Model\Constraints;

final class DateBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', new Constraints\RequiresValue());

        return $this;
    }

    public function beforeDate(string $date): self
    {
        $this->withConstraint('past-date', new Constraints\BeforeDate($date));

        return $this;
    }

    public function afterDate(string $date): self
    {
        $this->withConstraint('future-date', new Constraints\AfterDate($date));

        return $this;
    }

    public function requireFormat(string $format): self
    {
        $this->withConstraint('date-format', new Constraints\DateFormat($format));

        return $this;
    }
}
