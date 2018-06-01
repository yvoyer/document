<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\DataEntry\Domain\Model\DocumentRecord;

final class RecordBuilder
{
    /**
     * @var DocumentRecord
     */
    private $record;

    /**
     * @var DocumentBuilder
     */
    private $builder;

    /**
     * @param DocumentRecord $record
     * @param DocumentBuilder $builder
     */
    public function __construct(DocumentRecord $record, DocumentBuilder $builder)
    {
        $this->record = $record;
        $this->builder = $builder;
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return RecordBuilder
     */
    public function setValue(string $property, $value): self
    {
        $this->record->setValue($property, $value);

        return $this;
    }

    /**
     * @return DocumentRecord
     */
    public function getRecord(): DocumentRecord
    {
        return $this->record;
    }

    /**
     * @return DocumentBuilder
     */
    public function endRecord(): DocumentBuilder
    {
        return $this->builder;
    }
}
