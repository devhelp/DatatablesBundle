<?php

namespace Devhelp\DatatablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('devhelp_datatables');
        $rootNode->
            children()
            ->scalarNode('default_per_page')
            ->isRequired()
            ->cannotBeEmpty()
            ->end();
        $rootNode->
            children()
            ->arrayNode('grids')
            ->prototype('array')
            ->children()
                ->scalarNode('model')->isRequired()->end()
                ->scalarNode('default_per_page')->end()
                ->scalarNode('order_by')->end()
                ->enumNode('order_type')->values(array('asc', 'desc'))->end()
                ->arrayNode('columns')
                ->prototype('array')
                    ->children()
                    ->scalarNode('mData')->isRequired()->end()
                    ->scalarNode('bSearchable')->isRequired()->end()
                    ->scalarNode('sName')->isRequired()->end()
            ->end();
        return $treeBuilder;
    }

}
