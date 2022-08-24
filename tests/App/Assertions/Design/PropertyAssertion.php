<?php declare(strict_types=1);

namespace App\Tests\Assertions\Design;

use App\Tests\Assertions\TranslationsToAssertion;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Assert;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Types\StringType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use function array_merge;
use function var_dump;

final class PropertyAssertion
{
    private Connection $connection;
    private array $data;

    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $code,
        Connection $connection
    ) {
        $expr = $connection->createExpressionBuilder();
        $this->connection = $connection;
        $this->data = array_merge(
            $this->connection->createQueryBuilder()
                ->select('*')
                ->from('document_type_property')
                ->where(
                    $expr->eq('code', $expr->literal($code->toString())),
                    $expr->eq('document_type_id', $expr->literal($typeId->toString()))
                )
                ->fetchAssociative(),
            [
                'translations' => TranslationsToAssertion::rowsToMap(
                    ...$connection->createQueryBuilder()
                        ->select('*')
                        ->from('document_type_property_translation', 'translation')
                        ->innerJoin(
                            'translation',
                            'document_type_property',
                            'document_type_property',
                            $expr->eq('document_type_property.id', 'translation.object_id')
                        )
                        ->where($expr->eq('document_type_property.document_type_id', $expr->literal($typeId->toString())))
                        ->fetchAllAssociative()
                )
            ]
        );
    }

    public function debug(): self
    {
        var_dump($this->data);

        return $this;
    }

    public function assertTypeIsText(): self
    {
        $data = TypeData::fromString($this->data['type']);
        Assert::assertInstanceOf(StringType::class, $data->createType());

        return $this;
    }

    public function assertContainsNoParameters(): self
    {
        Assert::assertSame('[]', $this->data['parameters']);

        return $this;
    }

    public function assertNameIsTranslatedTo(string $expected, string $locale): self
    {
        Assert::assertArrayHasKey('name', $this->data['translations']);
        Assert::assertArrayHasKey($locale, $this->data['translations']['name']);
        Assert::assertSame($expected, $this->data['translations']['name'][$locale]);

        return $this;
    }
}
