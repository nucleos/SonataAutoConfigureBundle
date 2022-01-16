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

use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminClassesCompilerPass;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminExtensionsCompilerPass;
use Nucleos\SonataAutoConfigureBundle\SonataAutoConfigureBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SonataAutoConfigureBundleTest extends TestCase
{
    /**
     * @var SonataAutoConfigureBundle
     */
    private $bundle;

    protected function setUp(): void
    {
        $this->bundle = new SonataAutoConfigureBundle();
    }

    public function testBundle(): void
    {
        static::assertInstanceOf(Bundle::class, $this->bundle);
    }

    public function testCompilerPasses(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder
            ->expects(static::exactly(2))
            ->method('addCompilerPass')
            ->withConsecutive(
                [
                    static::isInstanceOf(AutoConfigureAdminClassesCompilerPass::class),
                    static::anything(),
                ],
                [
                    static::isInstanceOf(AutoConfigureAdminExtensionsCompilerPass::class),
                    static::anything(),
                ]
            )
            ->willReturn($containerBuilder)
        ;

        $this->bundle->build($containerBuilder);
    }
}
