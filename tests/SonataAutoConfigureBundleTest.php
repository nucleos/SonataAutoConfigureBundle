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
use Prophecy\Argument;
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
        $containerBuilder = $this->prophesize(ContainerBuilder::class);

        $containerBuilder
            ->addCompilerPass(
                Argument::type(AutoConfigureAdminClassesCompilerPass::class),
                Argument::cetera()
            )
            ->shouldBeCalledTimes(1)
            ->willReturn($containerBuilder)
        ;

        $containerBuilder
            ->addCompilerPass(
                Argument::type(AutoConfigureAdminExtensionsCompilerPass::class),
                Argument::cetera()
            )
            ->shouldBeCalledTimes(1)
            ->willReturn($containerBuilder)
        ;

        $this->bundle->build($containerBuilder->reveal());
    }
}
