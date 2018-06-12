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

    public function setUp()
    {
        $this->registry = new TransformerRegistry();
    }

    public function test_it_should_throw_exception_when_not_registered()
    {
        $this->expectException(NotFoundTransformer::class);
        $this->expectExceptionMessage('Transformer "no found" was not found.');
        $this->registry->createTransformer('no found');
    }

    public function test_it_should_throw_exception_when_already_registered()
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

    public function test_it_should_create_the_transformer()
    {
        $this->registry->registerTransformer(
            'id',
            $transformer = $this->createMock(ValueTransformer::class)
        );
        $this->assertSame($transformer, $this->registry->createTransformer('id'));
    }
}
