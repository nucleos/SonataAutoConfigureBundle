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

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_auto_configure');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->getAdminNode());
        $rootNode->append($this->getEntityNode());
        $rootNode->append($this->getControllerNode());

        return $treeBuilder;
    }

    private function getAdminNode(): NodeDefinition
    {
        $node = (new TreeBuilder('admin'))->getRootNode();

        $node
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
        ;

        return $node;
    }

    private function getEntityNode(): NodeDefinition
    {
        $node = (new TreeBuilder('entity'))->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('namespaces')
                    ->defaultValue([[
                        'namespace'    => 'App\Entity',
                        'manager_type' => 'orm',
                    ]])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('namespace')->cannotBeEmpty()->end()
                            ->scalarNode('manager_type')->defaultValue('orm')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $node;
    }

    private function getControllerNode(): NodeDefinition
    {
        $node = (new TreeBuilder('controller'))->getRootNode();

        $node
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
        ;

        return $node;
    }
}
