<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;

final class TextBuilder
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var SchemaBuilder
     */
    private $builder;

    public function __construct(string $property, DocumentSchema $schema, SchemaBuilder $builder)
    {
        $this->property = $property;
        $this->schema = $schema;
        $this->builder = $builder;
    }

    public function required(): self
    {
        $this->schema->addConstraint($this->property, new RequiresValue());

        return $this;
    }

    public function endProperty(): SchemaBuilder
    {
        return $this->builder;
    }
}
