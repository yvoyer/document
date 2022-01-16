<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Builder;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Builder\DocumentBuilder;

final class RecordBuilder
{
    private DocumentRecord $record;
    private DocumentBuilder $builder;

    public function __construct(
        DocumentRecord $record,
        DocumentBuilder $builder
    ) {
        $this->record = $record;
        $this->builder = $builder;
    }

    public function setValue(string $property, RecordValue $value): self
    {
        $this->record->setValue($property, $value, $this->builder->getErrorStrategyHandler());

        return $this;
    }

    public function getRecord(): DocumentRecord
    {
        return $this->record;
    }

    public function endRecord(): DocumentBuilder
    {
        return $this->builder;
    }
}
