<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

final class DateBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint($this->constraints()->required());

        return $this;
    }

    public function beforeDate(string $date): self
    {
        $this->withConstraint($this->constraints()->beforeDate($date));

        return $this;
    }

    public function afterDate(string $date): self
    {
        $this->withConstraint($this->constraints()->afterDate($date));

        return $this;
    }

    public function betweenDate(string $start, string $end): self
    {
        $this->withConstraint($this->constraints()->betweenDate($start, $end));

        return $this;
    }

    public function requireFormat(string $format): self
    {
        $this->withConstraint($this->constraints()->dateFormat($format));

        return $this;
    }
}
