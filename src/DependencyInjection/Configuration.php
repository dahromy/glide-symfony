<?php

namespace DahRomy\Glide\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dahromy_glide');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('source')->defaultValue('%kernel.project_dir%/public/uploads')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('cache')->defaultValue('%kernel.project_dir%/var/cache/glide')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('base_url')->defaultValue('')->end()
                ->scalarNode('signature_key')->defaultValue('%env(GLIDE_SIGNATURE_KEY)%')->end()
                ->booleanNode('group_cache_in_folders')->defaultTrue()->end()
                ->enumNode('driver')->values(['gd', 'imagick'])->defaultValue('gd')->end()
                ->arrayNode('max_image_size')
                    ->children()
                        ->integerNode('width')->defaultNull()->end()
                        ->integerNode('height')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('defaults')->useAttributeAsKey('name')->prototype('scalar')->end()->end()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->arrayNode('watermarks')
                    ->children()
                        ->scalarNode('path')->defaultNull()->end()
                        ->enumNode('driver')->values(['gd', 'imagick'])->defaultNull()->end()
                    ->end()
                ->end()
                ->booleanNode('response_sends_content')->defaultTrue()->end()
                ->integerNode('response_ttl')->defaultNull()->end()
                ->booleanNode('verify_signature')->defaultTrue()->end()
                ->arrayNode('server')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('source')->defaultNull()->end()
                        ->scalarNode('cache')->defaultNull()->end()
                        ->scalarNode('cache_path_prefix')->defaultNull()->end()
                        ->scalarNode('base_url')->defaultNull()->end()
                        ->booleanNode('group_cache_in_folders')->defaultTrue()->end()
                        ->scalarNode('cache_with_file_extensions')->defaultNull()->end()
                        ->enumNode('response_type')->values(['data_url', 'image', 'redirect'])->defaultNull()->end()
                        ->integerNode('cacheMaxAge')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('api')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('dpr')->defaultTrue()->end()
                        ->booleanNode('mark')->defaultTrue()->end()
                        ->booleanNode('markw')->defaultTrue()->end()
                        ->booleanNode('markh')->defaultTrue()->end()
                        ->booleanNode('markx')->defaultTrue()->end()
                        ->booleanNode('marky')->defaultTrue()->end()
                        ->booleanNode('markpad')->defaultTrue()->end()
                        ->booleanNode('markpos')->defaultTrue()->end()
                        ->booleanNode('markalpha')->defaultTrue()->end()
                        ->booleanNode('background')->defaultTrue()->end()
                        ->booleanNode('border')->defaultTrue()->end()
                        ->booleanNode('dpr')->defaultTrue()->end()
                        ->booleanNode('fit')->defaultTrue()->end()
                        ->booleanNode('h')->defaultTrue()->end()
                        ->booleanNode('w')->defaultTrue()->end()
                        ->booleanNode('q')->defaultTrue()->end()
                        ->booleanNode('fm')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
