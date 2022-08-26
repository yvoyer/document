<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Infrastructure\Persistence\Doctrine\DBAL;

use Star\Component\Document\Tests\App\RegressionTestCase;

/**
 * @group functional
 */
final class PropertyProjectionTest extends RegressionTestCase
{
    public function test_it_should_list_properties_of_a_document_type(): void
    {
        $client = $this->createTestClient();
        $fixture = $client->createFixtureBuilder();

        $memberId = $fixture
            ->newMember()
            ->getMemberId();
        $typeId = $fixture
            ->newDocumentType('list', 'en', $memberId)
            ->getDocumentTypeId();

        $fixture->assertDocumentType($typeId)
            ->assertPropertyCount(0);

        $fixture->loadDocumentType($typeId)
            ->withTextProperty('text', 'en');

        $fixture->assertDocumentType($typeId)
            ->assertPropertyCount(1)
            ->assertPropertyExists('text')
            ->enterProperty('text')
            ->assertNameIsTranslatedTo('text', 'en')
            ->assertTypeIsText();
    }
}
