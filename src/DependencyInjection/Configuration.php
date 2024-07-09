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
            ->scalarNode('source')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('source_path_prefix')->defaultValue('')->end()
            ->scalarNode('cache')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('cache_path_prefix')->defaultValue('')->end()
            ->scalarNode('temp_dir')->defaultValue('%kernel.cache_dir%/glide')->end()
            ->booleanNode('group_cache_in_folders')->defaultTrue()->end()
            ->booleanNode('cache_with_file_extensions')->defaultFalse()->end()
            ->scalarNode('watermarks')->defaultNull()->end()
            ->scalarNode('watermarks_path_prefix')->defaultValue('')->end()
            ->enumNode('driver')->values(['gd', 'imagick'])->defaultValue('gd')->end()
            ->integerNode('max_image_size')->defaultNull()->end()
            ->arrayNode('defaults')->useAttributeAsKey('name')->prototype('scalar')->end()->end()
            ->arrayNode('presets')
                ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifTrue(function ($v) {
                                return is_array($v) && !array_key_exists('name', $v);
                            })
                            ->then(function ($v) {
                                return ['name' => $v];
                            })
                ->end()
            ->useAttributeAsKey('name')
            ->prototype('scalar')->end()
            ->end()
            ->end()
            ->scalarNode('base_url')->defaultValue('')->end()
            ->scalarNode('signature_key')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Extracts the keys from an array of arrays.
     *
     * @param array $value The array of arrays.
     *
     * @return array The array of keys.
     */
    private function extractKeys(array $value): array
    {
        $keys = [];
        foreach ($value as $item) {
            if (is_array($item) && array_key_exists('name', $item)) {
                $keys[] = $item['name'];
            }
        }
        return $keys;
    }
}
