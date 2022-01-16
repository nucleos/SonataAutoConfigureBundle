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

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Generator;
use Nucleos\SonataAutoConfigureBundle\Attribute\Admin;
use Nucleos\SonataAutoConfigureBundle\Exception\EntityNotFound;
use ReflectionAttribute;
use ReflectionClass;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class AutoConfigureAdminClassesCompilerPass implements CompilerPassInterface
{
    /**
     * @var mixed[]
     */
    private array $entityNamespaces;

    /**
     * @var string[]
     */
    private array $controllerNamespaces;

    private string $controllerSuffix;

    private string $managerType;

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function process(ContainerBuilder $container): void
    {
        $adminSuffix                = $container->getParameter('sonata.auto_configure.admin.suffix');
        $this->managerType          = $container->getParameter('sonata.auto_configure.admin.manager_type');
        $this->entityNamespaces     = $container->getParameter('sonata.auto_configure.entity.namespaces');
        $this->controllerNamespaces = $container->getParameter('sonata.auto_configure.controller.namespaces');
        $this->controllerSuffix     = $container->getParameter('sonata.auto_configure.controller.suffix');

        $attributeDefaults = $this->getAttributeDefaults($container);

        $inflector = InflectorFactory::create()->build();

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {
            $definition = $container->getDefinition($id);

            if (!$definition->isAutoconfigured()) {
                continue;
            }

            $adminClass = $definition->getClass();

            if (null === $adminClass) {
                continue;
            }

            $adminClassAsArray = explode('\\', $adminClass);

            $name = end($adminClassAsArray);

            if (null !== $adminSuffix) {
                $name = preg_replace("/{$adminSuffix}$/", '', $name);
            }

            $attributes = $this->getAttributes($adminClass);

            foreach ($attributes as $attribute) {
                $this->setDefaultValuesForAttribute($inflector, $attribute, $name, $attributeDefaults);

                $container->removeDefinition($id);
                $definition = $container->setDefinition(
                    $attribute->getAdminCode() ?? $id,
                    (new Definition($adminClass))
                        ->addTag('sonata.admin', $attribute->getOptions())
                        ->setArguments([
                            $attribute->getAdminCode(),
                            $attribute->getEntity(),
                            $attribute->getController(),
                        ])
                        ->setAutoconfigured(true)->setAutowired(true)
                );

                if (null !== $attribute->getTranslationDomain()) {
                    $definition->addMethodCall('setTranslationDomain', [$attribute->getTranslationDomain()]);
                }

                if (\is_array($attribute->getTemplates())) {
                    foreach ($attribute->getTemplates() as $key => $template) {
                        $definition->addMethodCall('setTemplate', [$key, $template]);
                    }
                }

                if (\is_array($attribute->getChildren())) {
                    foreach ($attribute->getChildren() as $childId) {
                        $definition->addMethodCall('addChild', [new Reference($childId)]);
                    }
                }
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function setDefaultValuesForAttribute(Inflector $inflector, Admin $attribute, string $name, array $defaults): void
    {
        if (null === $attribute->getLabel()) {
            $attribute->setLabel($inflector->capitalize(str_replace('_', ' ', $inflector->tableize($name))));
        }

        if (null === $attribute->getLabelCatalogue()) {
            $attribute->setLabelCatalogue($defaults['label_catalogue']);
        }

        if (null === $attribute->getLabelTranslatorStrategy()) {
            $attribute->setLabelTranslatorStrategy($defaults['label_translator_strategy']);
        }

        if (null === $attribute->getTranslationDomain()) {
            $attribute->setTranslationDomain($defaults['translation_domain']);
        }

        if (null === $attribute->getGroup()) {
            $attribute->setGroup($defaults['group']);
        }

        if (null === $attribute->getPagerType()) {
            $attribute->setPagerType($defaults['pager_type']);
        }

        if (null === $attribute->getEntity() && true === $attribute->getAutowireEntity()) {
            [$entity, $managerType] = $this->findEntity($name);

            if (null !== $entity) {
                $attribute->setEntity($entity);
            }

            if (null === $attribute->getManagerType()) {
                $attribute->setManagerType($managerType);
            }
        }

        if (null === $attribute->getManagerType()) {
            $attribute->setManagerType($this->managerType);
        }

        if (null === $attribute->getController()) {
            $attribute->setController($this->findController($name.$this->controllerSuffix));
        }
    }

    private function findEntity(string $name): array
    {
        foreach ($this->entityNamespaces as $namespaceOptions) {
            if (class_exists($className = "{$namespaceOptions['namespace']}\\{$name}")) {
                return [$className, $namespaceOptions['manager_type']];
            }
        }

        throw new EntityNotFound($name, $this->entityNamespaces);
    }

    private function findController(string $name): ?string
    {
        foreach ($this->controllerNamespaces as $namespace) {
            if (class_exists($className = "{$namespace}\\{$name}")) {
                return $className;
            }
        }

        return null;
    }

    /**
     * @return Generator<array-key, Admin>
     */
    private function getAttributes(string $class): iterable
    {
        $reflectionClass = new ReflectionClass($class);

        $attributes = $reflectionClass->getAttributes(Admin::class, ReflectionAttribute::IS_INSTANCEOF);

        if (!$reflectionClass->implementsInterface(AdminInterface::class)) {
            return;
        }

        foreach ($attributes as $attribute) {
            yield $attribute->newInstance();
        }

        if (0 === \count($attributes)) {
            yield new Admin();
        }
    }

    private function getAttributeDefaults(ContainerBuilder $container): array
    {
        $defaults                              = [];
        $defaults['label_catalogue']           = $container->getParameter('sonata.auto_configure.admin.label_catalogue');
        $defaults['label_translator_strategy'] = $container->getParameter('sonata.auto_configure.admin.label_translator_strategy');
        $defaults['translation_domain']        = $container->getParameter('sonata.auto_configure.admin.translation_domain');
        $defaults['group']                     = $container->getParameter('sonata.auto_configure.admin.group');
        $defaults['pager_type']                = $container->getParameter('sonata.auto_configure.admin.pager_type');

        return $defaults;
    }
}
