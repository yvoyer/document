<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Builder;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;

final class DocumentBuilder
{
    private DocumentRecord $record;
    private DocumentTypeBuilder $builder;

    public function __construct(
        DocumentRecord $record,
        DocumentTypeBuilder $builder
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

    public function endRecord(): DocumentTypeBuilder
    {
        return $this->builder;
    }
}
