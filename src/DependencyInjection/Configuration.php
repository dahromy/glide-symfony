<?php

namespace DahRomy\Glide\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
            ->scalarNode('source')->defaultValue('%kernel.project_dir%/public')->end()
            ->scalarNode('source_path_prefix')->defaultValue('')->end()
            ->scalarNode('cache')->defaultValue('%kernel.project_dir%/var/cache/glide')->end()
            ->scalarNode('cache_path_prefix')->defaultValue('')->end()
            ->scalarNode('temp_dir')->defaultValue('%kernel.project_dir%/var/cache/glide')->end()
            ->booleanNode('group_cache_in_folders')->defaultTrue()->end()
            ->booleanNode('cache_with_file_extensions')->defaultFalse()->end()
            ->scalarNode('cache_path_callable')->defaultNull()->end()
            ->scalarNode('watermarks')->defaultValue('%kernel.project_dir%/public/watermarks')->end()
            ->scalarNode('watermarks_path_prefix')->defaultValue('')->end()
            ->enumNode('driver')
                ->values(['gd', 'imagick'])
                ->defaultValue('gd')
            ->end()
            ->scalarNode('max_image_size')->defaultValue('2000*2000')->end()
            ->arrayNode('defaults')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('q')->min(0)->max(100)->end()
                    ->enumNode('fm')->values(['jpg', 'png', 'gif', 'webp', 'auto'])->end()
                    ->integerNode('or')->min(0)->max(360)->end()
                    ->booleanNode('flip')->end()
                    ->scalarNode('crop')->end()
                    ->integerNode('w')->min(1)->end()
                    ->integerNode('h')->min(1)->end()
                    ->enumNode('fit')->values(['contain', 'max', 'fill', 'stretch', 'crop'])->end()
                    ->floatNode('dpr')->min(0.1)->max(8)->end()
                    ->integerNode('bri')->min(-100)->max(100)->end()
                    ->integerNode('con')->min(-100)->max(100)->end()
                    ->floatNode('gam')->min(0.1)->max(9.99)->end()
                    ->integerNode('sharp')->min(0)->max(100)->end()
                    ->floatNode('blur')->min(0)->max(100)->end()
                    ->integerNode('pixel')->min(0)->max(1000)->end()
                    ->enumNode('filt')->values(['greyscale', 'sepia'])->end()
                    ->scalarNode('mark')->end()
                    ->integerNode('markw')->min(0)->end()
                    ->integerNode('markh')->min(0)->end()
                    ->integerNode('markx')->end()
                    ->integerNode('marky')->end()
                    ->integerNode('markpad')->min(0)->end()
                    ->enumNode('markpos')->values(['top-left', 'top', 'top-right', 'left', 'center', 'right', 'bottom-left', 'bottom', 'bottom-right'])->end()
                    ->integerNode('markalpha')->min(0)->max(100)->end()
                    ->scalarNode('bg')->end()
                    ->scalarNode('border')->end()
                ->end()
            ->end()
            ->arrayNode('presets')
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                            ->integerNode('q')->min(0)->max(100)->end()
                            ->enumNode('fm')->values(['jpg', 'png', 'gif', 'webp', 'auto'])->end()
                            ->integerNode('or')->min(0)->max(360)->end()
                            ->booleanNode('flip')->end()
                            ->scalarNode('crop')->end()
                            ->integerNode('w')->min(1)->end()
                            ->integerNode('h')->min(1)->end()
                            ->enumNode('fit')->values(['contain', 'max', 'fill', 'stretch', 'crop'])->end()
                            ->floatNode('dpr')->min(0.1)->max(8)->end()
                            ->integerNode('bri')->min(-100)->max(100)->end()
                            ->integerNode('con')->min(-100)->max(100)->end()
                            ->floatNode('gam')->min(0.1)->max(9.99)->end()
                            ->integerNode('sharp')->min(0)->max(100)->end()
                            ->floatNode('blur')->min(0)->max(100)->end()
                            ->integerNode('pixel')->min(0)->max(1000)->end()
                            ->enumNode('filt')->values(['greyscale', 'sepia'])->end()
                            ->scalarNode('mark')->end()
                            ->integerNode('markw')->min(0)->end()
                            ->integerNode('markh')->min(0)->end()
                            ->integerNode('markx')->end()
                            ->integerNode('marky')->end()
                            ->integerNode('markpad')->min(0)->end()
                            ->enumNode('markpos')->values(['top-left', 'top', 'top-right', 'left', 'center', 'right', 'bottom-left', 'bottom', 'bottom-right'])->end()
                            ->integerNode('markalpha')->min(0)->max(100)->end()
                            ->scalarNode('bg')->end()
                            ->scalarNode('border')->end()
                    ->end()
                ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
