<?php

declare(strict_types=1);

/*
 * This file is part of the SonataAutoConfigureBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\Reader;
use Nucleos\SonataAutoConfigureBundle\Annotation\AdminExtensionOptions;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class AutoConfigureAdminExtensionsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $annotationReader = $container->get('annotation_reader');

        \assert($annotationReader instanceof Reader);

        foreach ($container->findTaggedServiceIds('sonata.admin.extension') as $id => $attributes) {
            $definition = $container->getDefinition($id);

            if (!$definition->isAutoconfigured()) {
                continue;
            }

            $definitionClass = $definition->getClass();

            $annotation = $annotationReader->getClassAnnotation(
                new ReflectionClass($definitionClass),
                AdminExtensionOptions::class
            );

            if (!$annotation) {
                continue;
            }

            $container->removeDefinition($id);

            $definition = $container->setDefinition(
                $id,
                (new Definition($definitionClass))
                    ->setAutoconfigured(true)
                    ->setAutowired(true)
            );

            if (!$this->hasTargets($annotation)) {
                $definition->addTag('sonata.admin.extension', $annotation->getOptions());

                continue;
            }

            foreach ($annotation->target as $target) {
                $definition->addTag(
                    'sonata.admin.extension',
                    $this->getTagAttributes($target, $annotation)
                );
            }
        }
    }

    private function hasTargets(AdminExtensionOptions $annotation): bool
    {
        return \is_array($annotation->target) && \count($annotation->target) > 0;
    }

    private function getTagAttributes(string $target, AdminExtensionOptions $annotation): array
    {
        $attributes['target'] = $target;

        if ($annotation->priority) {
            $attributes['priority'] = $annotation->priority;
        }

        return $attributes;
    }
}
