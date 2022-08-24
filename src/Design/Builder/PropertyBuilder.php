<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Builder;

use DateTimeImmutable;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

abstract class PropertyBuilder
{
    private PropertyCode $code;
    private DocumentDesigner $document;
    private DocumentTypeBuilder $builder;

    final public function __construct(
        PropertyCode $code,
        DocumentDesigner $document,
        DocumentTypeBuilder $builder
    ) {
        $this->code = $code;
        $this->document = $document;
        $this->builder = $builder;
    }

    public function withConstraint(string $constraintName, PropertyConstraint $constraint): self
    {
        $this->document->addPropertyConstraint($this->code, $constraintName, $constraint, new DateTimeImmutable());

        return $this;
    }

    public function withParameter(string $parameterName, PropertyParameter $parameter): self
    {
        $this->document->addPropertyParameter($this->code, $parameterName, $parameter, new DateTimeImmutable());

        return $this;
    }

    public function endProperty(): DocumentTypeBuilder
    {
        return $this->builder;
    }

    public function buildDocument(): DocumentDesigner
    {
        return $this->builder->getDocumentType();
    }

    protected function constraints(): ConstraintBuilder
    {
        return $this->builder::constraints();
    }

    protected function parameters(): ParameterBuilder
    {
        return $this->builder::parameters();
    }
}
