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

use Generator;
use Nucleos\SonataAutoConfigureBundle\Attribute\AdminExtension;
use ReflectionAttribute;
use ReflectionClass;
use Sonata\AdminBundle\Admin\AdminExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class AutoConfigureAdminExtensionsCompilerPass implements CompilerPassInterface
{
    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('sonata.admin.extension') as $id => $attributes) {
            $definition = $container->getDefinition($id);

            if (!$definition->isAutoconfigured()) {
                continue;
            }

            $definitionClass = $definition->getClass();

            if (null === $definitionClass) {
                continue;
            }

            $attributes = $this->getAttributes($definitionClass);

            foreach ($attributes as $attribute) {
                $container->removeDefinition($id);

                $definition = $container->setDefinition(
                    $id,
                    (new Definition($definitionClass))
                        ->setAutoconfigured(true)
                        ->setAutowired(true)
                );

                if (!$this->hasTargets($attribute)) {
                    $definition
                        ->addTag('sonata.admin.extension', $attribute->getOptions())
                    ;

                    continue;
                }

                foreach ($attribute->getTarget() as $target) {
                    $definition->addTag(
                        'sonata.admin.extension',
                        $this->getTagAttributes($target, $attribute)
                    );
                }
            }
        }
    }

    private function hasTargets(AdminExtension $attribute): bool
    {
        return \is_array($attribute->getTarget()) && \count($attribute->getTarget()) > 0;
    }

    /**
     * @return array<string, mixed>
     */
    private function getTagAttributes(string $target, AdminExtension $attribute): array
    {
        $attributes['target'] = $target;

        if (null !== $attribute->getPriority()) {
            $attributes['priority'] = $attribute->getPriority();
        }

        return $attributes;
    }

    /**
     * @return Generator<array-key, AdminExtension>
     */
    private function getAttributes(string $class): iterable
    {
        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->implementsInterface(AdminExtensionInterface::class)) {
            return;
        }

        $attributes = $reflectionClass->getAttributes(AdminExtension::class, ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
