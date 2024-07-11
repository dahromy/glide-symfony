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
                ->scalarNode('group_cache_in_folders')->defaultTrue()->end()
                ->enumNode('driver')->values(['gd', 'imagick'])->defaultValue('gd')->end()
                ->arrayNode('max_image_size')
                    ->children()
                        ->integerNode('width')->end()
                        ->integerNode('height')->end()
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
                        ->scalarNode('path')->end()
                        ->enumNode('driver')->values(['gd', 'imagick'])->end()
                    ->end()
                ->end()
                ->booleanNode('response_sends_content')->defaultTrue()->end()
                ->scalarNode('response_ttl')->defaultNull()->end()
                ->booleanNode('verify_signature')->defaultTrue()->end()
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
