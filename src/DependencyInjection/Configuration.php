<?php

/*
 * This file is part of the TLA Media GrabzitBundle.
 *
 * (c) TLA Media <kontakt@tlamedia.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tla\GrabzitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
final class Configuration implements ConfigurationInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('tla_grabzit');

	    if (method_exists($treeBuilder,'getRootNode')) {
		    $rootNode = $treeBuilder->getRootNode();
	    } else {
		    $rootNode = $treeBuilder->root('tla_grabzit');
	    }

        $rootNode
            ->children()
                ->scalarNode('key')->defaultNull()->end()
                ->scalarNode('secret')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
