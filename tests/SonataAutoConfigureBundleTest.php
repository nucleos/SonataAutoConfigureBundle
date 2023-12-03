<?php

declare(strict_types=1);

/*
 * This file is part of the SonataAutoConfigureBundle package.
 *
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SonataAutoConfigureBundle\Tests;

use Nucleos\SonataAutoConfigureBundle\SonataAutoConfigureBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SonataAutoConfigureBundleTest extends TestCase
{
    private SonataAutoConfigureBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new SonataAutoConfigureBundle();
    }

    public function testCompilerPasses(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder
            ->expects(self::exactly(2))
            ->method('addCompilerPass')
            ->willReturn($containerBuilder)
        ;

        $this->bundle->build($containerBuilder);
    }
}
