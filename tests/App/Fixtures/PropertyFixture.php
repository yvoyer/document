<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use DateTimeImmutable;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Messaging\Command\AddPropertyConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class PropertyFixture
{
    private DocumentTypeId $typeId;
    private PropertyCode $code;
    private DocumentTypeFixture $parent;
    private ApplicationFixtureBuilder $fixture;

    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $code,
        DocumentTypeFixture $parent,
        ApplicationFixtureBuilder $fixtures
    ) {
        $this->typeId = $typeId;
        $this->code = $code;
        $this->parent = $parent;
        $this->fixture = $fixtures;
    }

    public function required(): PropertyFixture
    {
        $this->fixture->doCommand(
            new AddPropertyConstraint(
                $this->typeId,
                $this->code,
                'required',
                [],
                new DateTimeImmutable()
            )
        );

        return $this;
    }

    public function endProperty(): DocumentTypeFixture
    {
        return $this->parent;
    }
}
