<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\DuplicateTransformer;
use Star\Component\Document\Design\Domain\Exception\NotFoundTransformer;

final class TransformerRegistryTest extends TestCase
{
    /**
     * @var TransformerRegistry
     */
    private $registry;

    public function setUp(): void
    {
        $this->registry = new TransformerRegistry();
    }

    public function test_it_should_throw_exception_when_not_registered(): void
    {
        $this->expectException(NotFoundTransformer::class);
        $this->expectExceptionMessage('Transformer with id "no found" could not be found.');
        $this->registry->createTransformer(TransformerIdentifier::fromString('no found'));
    }

    public function test_it_should_throw_exception_when_already_registered(): void
    {
        $this->expectException(DuplicateTransformer::class);
        $this->expectExceptionMessage('Transformer "id" is already registered.');
        $this->registry->registerTransformer(
            'id',
            $transformer = $this->createMock(ValueTransformer::class)
        );
        $this->registry->registerTransformer(
            'id',
            $transformer = $this->createMock(ValueTransformer::class)
        );
    }

    public function test_it_should_create_the_transformer(): void
    {
        $id = TransformerIdentifier::random();
        $this->registry->registerTransformer(
            $id->toString(),
            $transformer = $this->createMock(ValueTransformer::class)
        );
        $this->assertSame($transformer, $this->registry->createTransformer($id));
    }
}
