<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class PropertyFixture
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyName
     */
    private $propertyName;

    /**
     * @var DocumentFixture
     */
    private $parent;

    /**
     * @var ApplicationFixtureBuilder
     */
    private $fixture;

    public function __construct(
        DocumentId $documentId,
        PropertyName $propertyName,
        DocumentFixture $parent,
        ApplicationFixtureBuilder $fixtures
    ) {
        $this->documentId = $documentId;
        $this->propertyName = $propertyName;
        $this->parent = $parent;
        $this->fixture = $fixtures;
    }

    public function required(): PropertyFixture
    {
        $this->fixture->doCommand(
            new AddPropertyConstraint(
                $this->documentId,
                $this->propertyName,
                'required',
                []
            )
        );

        return $this;
    }

    public function endProperty(): DocumentFixture
    {
        return $this->parent;
    }
}
