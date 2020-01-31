<?php declare(strict_types=1);

namespace Star\Component\Document\Tools;

use Star\Component\Document\Design\Domain\Model\Constraints\RequiresValue;
use Star\Component\Document\Design\Domain\Model\Constraints\RequiresSingleOption;
use Star\Component\Document\Design\Domain\Model\DocumentDesigner;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class PropertyBuilder
{
    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @var DocumentBuilder
     */
    private $builder;

    public function __construct(
        PropertyName $name,
        DocumentDesigner $document,
        DocumentBuilder $builder
    ) {
        $this->name = $name;
        $this->document = $document;
        $this->builder = $builder;
    }

    public function required(): self
    {
        $this->document->addPropertyConstraint(
            $this->name,
            'required',
            new RequiresValue()
        );

        return $this;
    }

    public function singleOption(): self
    {
        $this->document->addPropertyConstraint(
            $this->name,
            'single-option',
            new RequiresSingleOption()
        );

        return $this;
    }

    public function endProperty(): DocumentBuilder
    {
        return $this->builder;
    }
}
