<?php

namespace Aws\Symfony\DependencyInjection;

use Aws;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        // Maintain backwars compatibility, only merge when AWS_MERGE_CONFIG is set
        $mergeConfig = getenv('AWS_MERGE_CONFIG') ?: false;
        $treeType = 'variable';

        if ($mergeConfig) {
            $treeType = 'array';
        }

        $treeBuilder = new TreeBuilder('aws', $treeType);

        // If not AWS_MERGE_CONFIG, return empty, variable TreeBuilder
        if (!$mergeConfig) {
            return $treeBuilder;
        }

        $rootNode = $treeBuilder->getRootNode();

        // Define TreeBuilder to allow config validation and merging
        $rootNode
            ->ignoreExtraKeys(false)
            ->children()
                ->variableNode('credentials')->end()
                ->variableNode('debug')->end()
                ->variableNode('stats')->end()
                ->scalarNode('endpoint')->end()
                ->variableNode('endpoint_discovery')->end()
                ->arrayNode('http')
                    ->children()
                        ->floatNode('connect_timeout')->end()
                        ->booleanNode('debug')->end()
                        ->booleanNode('decode_content')->end()
                        ->integerNode('delay')->end()
                        ->variableNode('expect')->end()
                        ->variableNode('proxy')->end()
                        ->scalarNode('sink')->end()
                        ->booleanNode('synchronous')->end()
                        ->booleanNode('stream')->end()
                        ->floatNode('timeout')->end()
                        ->scalarNode('verify')->end()
                    ->end()
                ->end()
                ->scalarNode('profile')->end()
                ->scalarNode('region')->end()
                ->integerNode('retries')->end()
                ->scalarNode('scheme')->end()
                ->scalarNode('service')->end()
                ->scalarNode('signature_version')->end()
                ->variableNode('ua_append')->end()
                ->variableNode('validate')->end()
                ->scalarNode('version')->end()
            ->end()
        ;

        //Setup config trees for each of the services
        foreach (array_column(Aws\manifest(), 'namespace') as $awsService) {
            $rootNode
                ->children()
                    ->arrayNode($awsService)
                        ->ignoreExtraKeys(false)
                        ->children()
                            ->variableNode('credentials')->end()
                            ->variableNode('debug')->end()
                            ->variableNode('stats')->end()
                            ->scalarNode('endpoint')->end()
                            ->variableNode('endpoint_discovery')->end()
                            ->arrayNode('http')
                                ->children()
                                    ->floatNode('connect_timeout')->end()
                                    ->booleanNode('debug')->end()
                                    ->booleanNode('decode_content')->end()
                                    ->integerNode('delay')->end()
                                    ->variableNode('expect')->end()
                                    ->variableNode('proxy')->end()
                                    ->scalarNode('sink')->end()
                                    ->booleanNode('synchronous')->end()
                                    ->booleanNode('stream')->end()
                                    ->floatNode('timeout')->end()
                                    ->scalarNode('verify')->end()
                                ->end()
                            ->end()
                            ->scalarNode('profile')->end()
                            ->scalarNode('region')->end()
                            ->integerNode('retries')->end()
                            ->scalarNode('scheme')->end()
                            ->scalarNode('service')->end()
                            ->scalarNode('signature_version')->end()
                            ->variableNode('ua_append')->end()
                            ->variableNode('validate')->end()
                            ->scalarNode('version')->end()
                        ->end()
                    ->end()
                ->end()
            ;
        }

        return $treeBuilder;
    }
}
