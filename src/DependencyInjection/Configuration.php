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

namespace Nucleos\SonataAutoConfigureBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_auto_configure');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('sonata_auto_configure');
        }

        $rootNode
            ->children()
                ->arrayNode('admin')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('suffix')
                            ->defaultValue('Admin')
                        ->end()
                        ->scalarNode('manager_type')
                            ->defaultValue('orm')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('label_catalogue')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('label_translator_strategy')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('translation_domain')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('group')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('pager_type')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('namespaces')
                            ->defaultValue([['namespace' => 'App\Entity', 'manager_type' => 'orm']])
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('namespace')->cannotBeEmpty()->end()
                                    ->scalarNode('manager_type')->defaultValue('orm')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('controller')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('suffix')
                            ->defaultValue('Controller')
                        ->end()
                        ->arrayNode('namespaces')
                            ->scalarPrototype()->end()
                            ->defaultValue(['App\Controller\Admin'])
                            ->requiresAtLeastOneElement()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
