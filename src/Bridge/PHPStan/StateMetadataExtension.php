<?php declare(strict_types=1);

namespace Star\Component\Document\Bridge\PHPStan;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Star\Component\State\StateMetadata;

final class StateMetadataExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return StateMetadata::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'transit';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $reflection = $scope->getClassReflection();
        if ($reflection && $reflection->isSubclassOf(StateMetadata::class)) {
            return new ObjectType($reflection->getName());
        }

        return new ClassStringType();
    }
}
