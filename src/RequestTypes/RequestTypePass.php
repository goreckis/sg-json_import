<?php

declare(strict_types=1);

namespace App\RequestTypes;

use App\Service\RequestTypeParser;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RequestTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $taggedServices = $container->findTaggedServiceIds('app.request_types');
        $definition = $container->findDefinition(RequestTypeParser::class);

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addTypeParser', [new Reference($id)]);
        }
    }

}
