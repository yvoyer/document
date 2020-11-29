<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

final class DateBuilder extends PropertyBuilder
{
    public function required(): self
    {
        $this->withConstraint('required', $this->constraints()->required());

        return $this;
    }

    public function beforeDate(string $date): self
    {
        $this->withConstraint('before-date', $this->constraints()->beforeDate($date));

        return $this;
    }

    public function afterDate(string $date): self
    {
        $this->withConstraint('after-date', $this->constraints()->afterDate($date));

        return $this;
    }

    public function betweenDate(string $start, string $end): self
    {
        $this->withConstraint('between', $this->constraints()->betweenDate($start, $end));

        return $this;
    }

    public function outputAsFormat(string $format): self
    {
        $this->withParameter('format', $this->parameters()->dateFormat($format));

        return $this;
    }
}
