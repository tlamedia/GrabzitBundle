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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 */
final class TlaGrabzitExtension extends Extension
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('tla_grabzit.key', $config['key']);

        $def = $container->getDefinition('tla_grabzit.client');
        $def->replaceArgument(0, $config['key']);
        $def->replaceArgument(1, $config['secret']);
    }
}
