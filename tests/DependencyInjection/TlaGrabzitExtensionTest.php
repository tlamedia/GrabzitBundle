<?php

/*
 * This file is part of the TLA Media GrabzitBundle.
 *
 * (c) TLA Media <kontakt@tlamedia.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tla\GrabzitBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Tla\GrabzitBundle\DependencyInjection\TlaGrabzitExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TlaGrabzitExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new TlaGrabzitExtension();
        $extension->load([], $container);
        $this->assertTrue($container->hasDefinition('tla_grabzit.client'));
        $this->assertTrue($container->hasDefinition('tla_grabzit.imageoptions'));
    }
}