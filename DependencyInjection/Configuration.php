<?php

namespace Noxlogic\RateLimitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 */
class Configuration implements ConfigurationInterface
{
    const HTTP_TOO_MANY_REQUESTS = 429;

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('noxlogic_rate_limit')
            ->canBeDisabled()
            ->children()
                ->enumNode('storage_engine')
                    ->values(array('redis','memcache'))
                    ->defaultValue('redis')
                    ->info('The storage engine where all the rates will be stored')
                ->end()
                ->scalarNode('redis_client')
                    ->defaultValue('default_client')
                    ->info('The redis client to use for the redis storage engine')
                ->end()
                ->integerNode('rate_response_code')
                    ->min(400)
                    ->max(499)
                    ->defaultValue(static::HTTP_TOO_MANY_REQUESTS)
                    ->info('The HTTP status code to return when a client hits the rate limit')
                ->end()
                ->scalarNode('rate_response_message')
                    ->defaultValue('You exceeded the rate limit')
                    ->info('The HTTP message to return when a client hits the rate limit')
                ->end()
                ->booleanNode('display_headers')
                    ->defaultTrue()
                    ->info('Should the ratelimit headers be automatically added to the response?')
                ->end()
                ->arrayNode('headers')
                    ->addDefaultsIfNotSet()
                    ->info('What are the different header names to add')
                    ->children()
                        ->scalarNode('limit')->defaultValue('X-RateLimit-Limit')->end()
                        ->scalarNode('remaining')->defaultValue('X-RateLimit-Remaining')->end()
                        ->scalarNode('reset')->defaultValue('X-RateLimit-Reset')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
