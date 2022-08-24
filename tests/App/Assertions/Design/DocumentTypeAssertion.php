<?php declare(strict_types=1);

namespace App\Tests\Assertions\Design;

use App\Tests\Assertions\TranslationsToAssertion;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Assert;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use function array_merge;
use function var_dump;

final class DocumentTypeAssertion
{
    private DocumentTypeId $typeId;
    private Connection $connection;
    private array $data;

    public function __construct(
        DocumentTypeId $typeId,
        Connection $connection
    ) {
        $this->typeId = $typeId;
        $this->connection = $connection;
        $expr = $this->connection->createExpressionBuilder();
        $this->data = array_merge(
            $this->connection->createQueryBuilder()
                ->select('*')
                ->from('document_type')
                ->where($expr->eq('id', $expr->literal($typeId->toString())))
                ->fetchAssociative(),
            [
                'translations' => TranslationsToAssertion::rowsToMap(
                    ...$this->connection->createQueryBuilder()
                        ->select('*')
                        ->from('document_type_translation')
                        ->where($expr->eq('object_id', $expr->literal($typeId->toString())))
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

    public function assertNameIsTranslatedTo(string $expected, string $locale): self
    {
        Assert::assertArrayHasKey('name', $this->data['translations']);
        Assert::assertArrayHasKey($locale, $this->data['translations']['name']);
        Assert::assertSame($expected, $this->data['translations']['name'][$locale]);

        return $this;
    }

    public function assertPropertyExists(string $code): self
    {
        $expr = $this->connection->createExpressionBuilder();
        Assert::assertArrayHasKey(
            $code,
            $this->connection->createQueryBuilder()
                ->select('code')
                ->from('document_type_property')
                ->where($expr->eq('document_type_id', $expr->literal($this->typeId->toString())))
                ->fetchAllAssociativeIndexed()
       );

        return $this;
    }

    public function assertPropertyCount(int $expected): self
    {
        $expr = $this->connection->createExpressionBuilder();
        Assert::assertSame(
            $expected,
            $this->connection->createQueryBuilder()
                ->select('COUNT(*)')
                ->from('document_type_property')
                ->where($expr->eq('document_type_id', $expr->literal($this->typeId->toString())))
                ->fetchOne()
        );

        return $this;
    }

    public function enterProperty(string $code): PropertyAssertion
    {
        return new PropertyAssertion(
            $this->typeId,
            PropertyCode::fromString($code),
            $this->connection
        );
    }
}
