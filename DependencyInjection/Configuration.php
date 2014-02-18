<?php

namespace Devhelp\DatatablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for DatatablesBundle.
 *
 * @author <michal@devhelp.pl>
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
                ->scalarNode('routing')->isRequired()->end()
                ->booleanNode('use_filters')->defaultTrue()->end()
                ->scalarNode('default_per_page')->defaultValue(10)->end()
                ->arrayNode('columns')
                ->prototype('array')
                    ->children()
                    ->scalarNode('title')->isRequired()->end()
                    ->scalarNode('data')->isRequired()->end()
                    ->scalarNode('alias')->isRequired()->end()
                    ->scalarNode('searchable')->defaultValue(1)->end()
                    ->scalarNode('visible')->defaultValue(1)->end()
                    ->scalarNode('width')->defaultValue('20px')->end()
            ->end();
        return $treeBuilder;
    }

}
