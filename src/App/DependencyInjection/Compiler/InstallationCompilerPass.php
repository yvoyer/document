<?php declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Installation\CheckInstallationSteps;
use App\Installation\InstallationStep;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class InstallationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition(CheckInstallationSteps::class);
        $steps = $container->findTaggedServiceIds(InstallationStep::TAG_NAME);
        foreach ($steps as $serviceId => $tags) {
            $registry->addMethodCall('registerStep', [new Reference($serviceId)]);
        }
    }
}
