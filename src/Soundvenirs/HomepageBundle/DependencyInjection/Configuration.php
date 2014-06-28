<?php

namespace Soundvenirs\HomepageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('soundvenirs_homepage');

        $rootNode
            ->children()
            ->scalarNode('soundfiles_path')
            ->end();

        return $treeBuilder;
    }
}
