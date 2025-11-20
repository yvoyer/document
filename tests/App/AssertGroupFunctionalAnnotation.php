<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use PHPUnit\Framework\Assert;

trait AssertGroupFunctionalAnnotation
{
    /**
     * @beforeClass
     */
    public static function make_sure_test_class_has_group_functional_annotation(): void {
        $class = new \ReflectionClass(static::class);

        $comment = (string)$class->getDocComment();
        Assert::assertContains(
            '@group functional',
            $comment,
            \sprintf(
                'Classes extending "%s" should be marked "@group functional" in the docblock to make unit test suite faster.',
                RegressionTestCase::class
            )
        );
    }
}
