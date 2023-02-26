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

namespace Nucleos\SonataAutoConfigureBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SonataAutoConfigureBundle\DependencyInjection\Compiler\AutoConfigureAdminExtensionsCompilerPass;
use Nucleos\SonataAutoConfigureBundle\DependencyInjection\SonataAutoConfigureExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\ExtensionWithoutOptions;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\GlobalExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\MultipleTargetedExtension;
use Nucleos\SonataAutoConfigureBundle\Tests\Fixtures\Admin\Extension\TargetedWithPriorityExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class AutoConfigureExtensionsCompilerPassTest extends TestCase
{
    private AutoConfigureAdminExtensionsCompilerPass $autoconfigureExtensionsCompilerPass;

    private ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        $this->autoconfigureExtensionsCompilerPass = new AutoConfigureAdminExtensionsCompilerPass();
        $this->containerBuilder                    = new ContainerBuilder();

        $this->containerBuilder->registerExtension(new SonataAutoConfigureExtension());
    }

    /**
     * @dataProvider processData
     *
     * @param string[] $expectedTags
     */
    public function testProcess(string $extensionServiceId, array $expectedTags = []): void
    {
        $this->loadConfig();

        $this->containerBuilder->setDefinition(
            $extensionServiceId,
            (new Definition($extensionServiceId))->addTag('sonata.admin.extension')->setAutoconfigured(true)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        $extensionDefinition = $this->containerBuilder->getDefinition($extensionServiceId);

        $actualTags = $extensionDefinition->getTag('sonata.admin.extension');
        static::assertGreaterThan(0, $actualTags);
        foreach ($expectedTags as $i => $expectedTag) {
            static::assertArrayHasKey($i, $actualTags);
            static::assertSame($expectedTag, $actualTags[$i]);
        }
    }

    /**
     * @return mixed[]
     */
    public function processData(): iterable
    {
        yield [ExtensionWithoutOptions::class];

        yield [GlobalExtension::class, [
            [
                'global' => true,
            ],
        ]];

        yield [TargetedWithPriorityExtension::class, [
            [
                'target'   => 'app.admin.category',
                'priority' => 5,
            ],
        ]];

        yield [MultipleTargetedExtension::class, [
            [
                'target' => 'app.admin.category',
            ], [
                'target' => 'app.admin.media',
            ],
        ]];
    }

    public function testProcessSkipAutoConfigured(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            TargetedWithPriorityExtension::class,
            (new Definition(TargetedWithPriorityExtension::class))->addTag('sonata.admin.extension')->setAutoconfigured(false)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition(TargetedWithPriorityExtension::class);
        $tag        = $definition->getTag('sonata.admin.extension');
        static::assertEmpty(reset($tag));
    }

    public function testProcessSkipIfAttributeMissing(): void
    {
        $this->loadConfig();
        $this->containerBuilder->setDefinition(
            ExtensionWithoutOptions::class,
            (new Definition(ExtensionWithoutOptions::class))->addTag('sonata.admin.extension')->setAutoconfigured(true)
        );

        $this->autoconfigureExtensionsCompilerPass->process($this->containerBuilder);

        $definition = $this->containerBuilder->getDefinition(ExtensionWithoutOptions::class);
        $tag        = $definition->getTag('sonata.admin.extension');
        static::assertEmpty(reset($tag));
    }

    /**
     * @param mixed[] $config
     */
    private function loadConfig(array $config = []): void
    {
        (new SonataAutoConfigureExtension())->load([
            'sonata_auto_configure' => $config,
        ], $this->containerBuilder);
    }
}
